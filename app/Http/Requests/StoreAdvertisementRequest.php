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
            // The rubric says: "Je mag maar maximaal 4 advertenties aanmaken"
        }
        
        // For updates, check policy or ownership
        $advertisement = $this->route('advertisement');
        if ($advertisement && $advertisement->user_id !== $this->user()->id) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'type' => ['required', Rule::in(['sell', 'rent', 'auction'])],
            'upsells' => ['array'], // List of ad IDs
            'upsells.*' => ['exists:advertisements,id'],
        ];
    }
}
