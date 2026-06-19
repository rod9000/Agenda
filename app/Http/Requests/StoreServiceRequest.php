<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $serviceId = $this->route('service');

        return [
            'name'         => [
                'required', 'string', 'max:100',
                Rule::unique('services', 'name')->ignore($serviceId),
            ],
            'description'  => 'nullable|string',
            'duration_min' => 'required|integer|min:5',
            'price'        => 'required|numeric|min:0',
            'estimated_product_cost' => 'nullable|numeric|min:0',
            'commission_type' => 'nullable|string|in:percentage,fixed',
            'commission_value' => 'nullable|numeric|min:0',
            'commission_percent' => 'nullable|numeric|min:0|max:100',
            'color_hex'    => 'nullable|string|max:7',
            'active'       => 'nullable|boolean',
            'products'     => 'nullable|array',
            'products.*.product_id' => 'required_with:products|exists:products,id',
            'products.*.quantity' => 'required_with:products|integer|min:1',
            'products.*.is_per_session' => 'boolean',
        ];
    }
}
