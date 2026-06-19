<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $customerId = $this->route('customer');

        return [
            'name'       => 'required|string|max:100',
            'cpf'        => [
                'nullable', 'string', 'max:20',
                Rule::unique('customers', 'cpf')->ignore($customerId),
            ],
            'phone'      => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'email'      => 'nullable|email|max:100',
            'photo'      => 'nullable|string',
            'notes'      => 'nullable|string',
        ];
    }
}
