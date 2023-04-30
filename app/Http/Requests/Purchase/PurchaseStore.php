<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $request = $this->instance()->all();
        $rules = [
            'supplier_id' => 'required|integer',
            'invoice_no' => 'required|string',
            'date' => 'required|date',
        ];

        if (request()->isMethod('post'))
        {
            $rules['store_quantity'] = ['required', 'array',];
            $rules['store_quantity.*'] = ['required', 'numeric',];
            $rules['store_product_id'] = ['required', 'array',];
            $rules['store_product_id.*'] = ['required', 'integer',];
            $rules['store_attribute_id'] = ['required', 'array',];
            $rules['store_attribute_id.*'] = ['required', 'string',];
            $rules['store_product_attribute_map_id'] = ['required', 'array',];
            $rules['store_product_attribute_map_id.*'] = ['required', 'string',];
        }

        if (request()->isMethod('PUT') or request()->isMethod('PATCH'))
        {
            $rules['store_quantity'] = ['required', 'array',];
            $rules['store_quantity.*'] = ['required', 'numeric',];
            $rules['store_product_id'] = ['required', 'array',];
            $rules['store_product_id.*'] = ['required', 'integer',];
            $rules['store_attribute_id'] = ['required', 'array',];
            $rules['store_attribute_id.*'] = ['required', 'integer',];
            $rules['store_product_attribute_map_id'] = ['required', 'array',];
            $rules['store_product_attribute_map_id.*'] = ['required', 'integer',];
        }
        return $rules;
    }


    public function messages()
    {
        $message = [
            'supplier_id.integer' => 'Supplier field must be an Integer',
            'invoice_no.required' => 'Invoice no field must be required',
            'supplier_id.required' => 'Supplier field must be  required',
        ];

        if (request()->isMethod('post'))
        {
            $message['quantity.*'] = 'Product Quantity field is required';
            $message['product_id.*'] = 'Product name is required';
            $message['attribute_id.*'] = 'Attribute name is required';
            $message['product_attribute_map_id.*'] = 'Attribute Map Value is required';
        }

        if (request()->isMethod('PUT') or request()->isMethod('PATCH'))
        {
            $message['product_id.*'] = 'Product name is required';
            $message['attribute_id.*'] = 'Attribute name is required';
            $message['product_attribute_map_id.*'] = 'Attribute Map Value is required';
            $message['quantity.*'] = 'Product Quantity field must be required';
        }
        return $message;
    }
}
