<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ParentProductStore extends FormRequest
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
            'name' => 'required|string',
            'slug' => 'required|string',
            'product_model' => 'nullable|string',
            'model_no' => 'nullable|string',
            'qr_code' => 'nullable|string',
            'sku' => 'nullable|string',
            'product_specification' => 'nullable',
            'product_details' => 'nullable',
            'status' => 'nullable|integer',
//            'min_price' => 'required|numeric',
//            'max_price' => 'required|numeric',
//            'alert_quantity' => 'required|integer',
//            'tax_id' => 'nullable|integer',
            'product_category_id' => 'required|integer',
            'product_brand_id' => 'required|integer',
            'manufacturer_id' => 'required|integer',
        ];
//        if($request['parenttype'] == 'parenttype'){
//            $rules['min_price'] = 'nullable';
//            $rules['max_price'] = 'nullable';
//            $rules['alert_quantity'] = 'nullable';
//            $rules['tax_id'] = 'nullable';
//        }else{
//            $rules['min_price'] = 'required|numeric';
//            $rules['max_price'] = 'required|numeric';
//            $rules['alert_quantity'] = 'required|integer';
//            $rules['tax_id'] = 'required|integer';
//        }
        return $rules;
    }


    public function messages()
    {
        return [
            'name.required' => 'name field is required',
            'slug.required' => 'slug field is required',
            'tax_id.integer' => 'Tax field must be an integer',
//            'alert_quantity.required' => 'Product alert quantity  is required',
//            'alert_quantity.integer' => 'Product alert quantity  field must be an integer',
            'product_category_id.required' => 'Product Category field is required',
            'product_category_id.integer' => 'Product Category field must be an integer',
            'product_brand_id.required' => 'Product Brand field is required',
            'product_brand_id.integer' => 'Product Brand field must be an integer',
            'manufacturer_id.required' => 'Product Manufacturer field is required',
            'manufacturer_id.integer' => 'Product Manufacturer field must be an integer',
        ];
    }
}
