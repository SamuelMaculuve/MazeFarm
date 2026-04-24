<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInsuranceCompanyRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'                 => 'required|string|max:255',
            'nuit'                 => 'nullable|string|max:20',
            'contact_person'       => 'nullable|string|max:255',
            'phone'                => 'nullable|string|max:20',
            'email'                => 'nullable|email|max:255',
            'address'              => 'nullable|string|max:255',
            'billing_cycle'        => 'required|in:weekly,monthly',
            'requires_preauth'     => 'boolean',
            'default_coverage_pct' => 'required|numeric|min:0|max:100',
            'notes'                => 'nullable|string',
            'is_active'            => 'boolean',
        ];
    }
}
