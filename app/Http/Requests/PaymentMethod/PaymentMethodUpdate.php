<?php

namespace App\Http\Requests\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodUpdate extends FormRequest
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
            'payment_type' => 'required|string',
            'descriptions' => 'required|string',
        ];
    }


    public function messages()
    {
        return [
            'payment_type.required' => 'Payment Type field is required',
            'descriptions.required' => 'Descriptions  field is required',
        ];
    }
}
