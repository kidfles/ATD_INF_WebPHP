<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ContractStatus;

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
            'string',
            'alpha_dash', 
            'max:255',
            Rule::unique('company_profiles')->ignore($company->id),
        ],
        // Wear & Tear Policy (Only validate if contract is approved and user can actually see these fields)
        'wear_and_tear_policy' => [
            Rule::requiredIf($company->contract_status === ContractStatus::Approved),
            'string',
            'in:none,fixed,percentage'
        ],
        'wear_and_tear_value' => [
            Rule::requiredIf(fn() => $company->contract_status === ContractStatus::Approved && $this->input('wear_and_tear_policy') !== 'none'),
            'nullable', // Allow null if not required
            'numeric',
            'min:0',
            Rule::when(
                $this->input('wear_and_tear_policy') === 'percentage',
                'max:100'
            ),
        ],
        
        // Allow the components array to be present
        'components' => ['nullable', 'array'],
        'ordered_ids' => ['nullable', 'array'],
    ];
}

    public function messages(): array
    {
        return [
            'company_name.required' => 'Bedrijfsnaam is verplicht.',
            'kvk_number.required'   => 'KVK-nummer is verplicht.',
        ];
    }
