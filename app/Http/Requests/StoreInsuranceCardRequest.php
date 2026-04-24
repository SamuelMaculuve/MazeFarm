<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInsuranceCardRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'customer_id'           => 'required|exists:customers,id',
            'insurance_company_id'  => 'required|exists:insurance_companies,id',
            'card_number'           => 'required|string|max:60',
            'policy_number'         => 'nullable|string|max:60',
            'employee_number'       => 'nullable|string|max:60',
            'employer_name'         => 'nullable|string|max:255',
            'coverage_pct'          => 'nullable|numeric|min:0|max:100',
            'copay_amount'          => 'nullable|numeric|min:0',
            'coverage_limit_annual' => 'nullable|numeric|min:0',
            'monthly_limit'         => 'nullable|numeric|min:0',
            'expiry_date'           => 'nullable|date',
            'valid_from'            => 'nullable|date',
            'notes'                 => 'nullable|string',
            'is_active'             => 'boolean',
        ];
    }
}
