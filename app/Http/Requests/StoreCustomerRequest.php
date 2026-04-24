<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'type'          => 'required|in:individual,corporate',
            'name'          => 'required|string|max:255',
            'nuit'          => 'nullable|string|max:20',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'address'       => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender'        => 'nullable|in:M,F,other',
            'notes'         => 'nullable|string',
            'credit_limit'  => 'nullable|numeric|min:0',
            'is_active'     => 'boolean',
        ];
    }
}
