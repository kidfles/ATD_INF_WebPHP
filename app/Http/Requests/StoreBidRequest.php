<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Business Rule: Max 4 active bids
        return $this->user()->bids()->count() < 4;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'], // In real app, check > current highest
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Vul een bedrag in.',
            'authorize' => 'Je hebt het limiet van 4 actieve biedingen bereikt.',
        ];
    }
}
