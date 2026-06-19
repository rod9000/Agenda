<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $productId = $this->route('product');

        return [
            'name'           => [
                'required', 'string', 'max:150',
                Rule::unique('products', 'name')->ignore($productId),
            ],
            'brand'          => 'nullable|string|max:100',
            'expiry_date'    => 'nullable|date',
            'purchase_price' => 'required|numeric|min:0',
            'quantity'       => 'nullable|integer|min:0',
            'min_stock'      => 'nullable|integer|min:0',
            'supplier'       => 'nullable|string|max:100',
            'sale_price'     => 'nullable|numeric|min:0',
        ];
    }
}
