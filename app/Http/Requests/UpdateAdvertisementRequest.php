<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdvertisementRequest extends FormRequest
{
    public function authorize(): bool
    {
        // BUSINESS RULE: Only the owner can update
        $advertisement = $this->route('advertisement');
        return $advertisement && $advertisement->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'price'       => ['required', 'numeric', 'min:0'],
            'type'        => ['required', 'in:sell,rent,auction'],
            'image'       => ['nullable', 'image', 'max:10240'], // Max 10MB, No dimension check
            'expires_at'  => ['nullable', 'date', 'after:today'],
            'related_ads' => ['nullable', 'array'],
            'related_ads.*' => ['exists:advertisements,id'],
        ];
    }
}
