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
        // Enforce max 4 ads rule only on creation
        if ($this->isMethod('post')) {
            return $this->user()->advertisements()->count() < 4;
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
            'type'        => ['required', 'in:sell,rent,auction'],
            'image'       => ['nullable', 'image', 'max:2048'], // Max 2MB
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
