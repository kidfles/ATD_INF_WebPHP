<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkUpdatePageComponentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->companyProfile !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ordered_ids' => 'present|array',
            'ordered_ids.*' => 'exists:page_components,id',
            'components' => 'present|array',
            'components.*.content' => 'present|array',
            // Specific content validation could be added here if needed, 
            // but structure varies by component type.
            // Basic array check provides some protection against arbitrary injection.
        ];
    }

    public function messages(): array
    {
        return [
            'ordered_ids.present'  => 'Volgorde is verplicht.',
            'components.present'   => 'Componenten zijn verplicht.',
        ];
    }
}
