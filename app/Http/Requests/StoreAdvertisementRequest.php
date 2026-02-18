<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Advertisement;

class StoreAdvertisementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Enforce max 4 ads rule only on creation - Handled in rules() per type now
        if ($this->isMethod('post')) {
            return true; 
        }

        // For updates, check policy or ownership
        $advertisement = $this->route('advertisement');
        if ($advertisement && $advertisement->user_id !== $this->user()->id) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'price'       => ['required', 'numeric', 'min:0'],
            'type'        => [
                'required', 
                'in:sell,rent,auction',
                function ($attribute, $value, $fail) {
                    $user = $this->user();
                    $query = $user->advertisements()->where('type', $value);

                    // Bij update: sluit de huidige advertentie uit van de telling
                    if ($this->route('advertisement')) {
                         $query->where('id', '!=', $this->route('advertisement')->id);
                    }

                    $count = $query->count();
                    
                    if ($count >= 4) {
                        $fail("Je mag maximaal 4 {$value} advertenties hebben.");
                    }
                }
            ],
            'image'       => ['nullable', 'image', 'max:10240', 'dimensions:min_width=100,min_height=100'], // Max 10MB, Min 100x100px
            'related_ads' => ['nullable', 'array'],
            'related_ads.*' => ['exists:advertisements,id'],
            'expires_at'  => ['nullable', 'date', 'after:today', 'required_if:type,auction'],
        ];
    }

    protected function failedAuthorization()
    {
        throw new \Illuminate\Auth\Access\AuthorizationException(
            $this->isMethod('post') 
                ? 'Je hebt het limiet van 4 advertenties bereikt.' 
                : 'Dit is niet jouw advertentie.'
        );
    }
}
