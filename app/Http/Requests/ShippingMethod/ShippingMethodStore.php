<?php

namespace App\Http\Requests\ShippingMethod;

use Illuminate\Foundation\Http\FormRequest;

class ShippingMethodStore extends FormRequest
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
        return [
            'name' => 'required|string',
            'vendor_id' => 'required|integer',
            'charge' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'vendor_id.required' => 'Vendor name field is required',
        ];
    }
}
