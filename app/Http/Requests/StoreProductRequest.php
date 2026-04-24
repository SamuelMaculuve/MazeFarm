<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('product')?->id;

        return [
            'generic_name'          => 'required|string|max:255',
            'commercial_name'       => 'nullable|string|max:255',
            'category_id'           => 'nullable|exists:product_categories,id',
            'pharmaceutical_form'   => 'nullable|string|max:60',
            'dosage'                => 'nullable|string|max:60',
            'unit'                  => 'required|string|max:30',
            'barcode'               => "nullable|string|max:50|unique:products,barcode,{$id}",
            'fnm_code'              => 'nullable|string|max:20',
            'requires_prescription' => 'boolean',
            'is_controlled'         => 'boolean',
            'is_narcotic'           => 'boolean',
            'minimum_stock'         => 'required|integer|min:0',
            'reorder_point'         => 'required|integer|min:0',
            'purchase_price'        => 'required|numeric|min:0',
            'sale_price'            => 'required|numeric|min:0',
            'description'           => 'nullable|string',
            'manufacturer'          => 'nullable|string|max:255',
            'is_active'             => 'boolean',
        ];
    }
}
