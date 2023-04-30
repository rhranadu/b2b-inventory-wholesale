<?php

namespace App\Http\Requests\ProductAttributeMap;

use Illuminate\Foundation\Http\FormRequest;

class ProductAttributeMapUpdate extends FormRequest
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
            'product_attribute_id' => 'required|integer',
            'value' => 'required',
        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
