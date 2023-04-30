<?php

namespace App\Http\Requests\Discount;

use Illuminate\Foundation\Http\FormRequest;

class DiscountStore extends FormRequest
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
//            'discount_amount' => 'required|numeric',
//            'discountable_id' => 'required|integer',
            'discountable_type' => 'required|string',
        ];

        return $rules;
    }


    public function messages()
    {
        return [
//            'discount_amount.required' => 'Discount amount is required',
//            'discountable_id.required' => 'Discountable is required',
            'discountable_type.required' => 'Discountable type is required',
        ];
    }
}
