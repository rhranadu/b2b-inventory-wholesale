<?php

namespace App\Http\Requests\ProductBrand;

use Illuminate\Foundation\Http\FormRequest;

class ProductBrandStore extends FormRequest
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
            'slug' => 'required|string',
            'img' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'website' => 'nullable|string',
            'status' => 'nullable|integer',
        ];
    }


    public function messages()
    {
        return [

        ];
    }
}
