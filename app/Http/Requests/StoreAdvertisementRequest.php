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
        // BUSINESS RULE: Max 4 ads per user
        return $this->user()->advertisements()->count() < 4;
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

    public function messages(): array
    {
        return [
            'authorize' => 'Je hebt het limiet van 4 advertenties bereikt.',
        ];
    }
}
