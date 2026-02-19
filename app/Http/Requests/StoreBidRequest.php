<?php declare(strict_types=1);

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
            'amount' => [
                'required', 
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) {
                    $advertisement = $this->route('advertisement');
                    
                    // Sanity check
                    if (!$advertisement) return;

                    $highestBid = $advertisement->bids()->max('amount');

                    if ($highestBid && $value <= $highestBid) {
                        $fail("Bod moet hoger zijn dan het huidige hoogste bod (€" . number_format($highestBid, 2) . ").");
                    } elseif (!$highestBid && $value < $advertisement->price) {
                        $fail("Het eerste bod moet minimaal gelijk zijn aan de startprijs (€" . number_format($advertisement->price, 2) . ").");
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Vul een bod in.',
            'amount.numeric'  => 'Het bod moet een getal zijn.',
            'amount.min'      => 'Het bod moet minimaal €0,01 zijn.',
        ];
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedAuthorization(): void
    {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'amount' => ['Je hebt het limiet van 4 actieve biedingen bereikt.'],
        ]);
    }
}
