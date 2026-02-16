<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyProfileRequest extends FormRequest
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
    // app/Http/Requests/UpdateCompanyProfileRequest.php

public function rules(): array
{
    $company = $this->user()->companyProfile;
    return [
        'kvk_number' => ['required', 'digits:8'],
        'brand_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        'custom_url_slug' => [
            'required', 
            'alpha_dash', 
            \Illuminate\Validation\Rule::unique('company_profiles')->ignore($company->id),
        ],
        // Allow the components array to be present
        'components' => ['nullable', 'array'],
        'ordered_ids' => ['nullable', 'array'],
    ];
}
}
