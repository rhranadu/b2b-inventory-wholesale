<?php

namespace App\Http\Requests\ProductCategory;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryStore extends FormRequest
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
            'status' => 'nullable|integer',
            'parent_category_id' => 'nullable|integer',
        ];

//        if($request['modalCreate']){
//            $validator = Validator::make($request->all(), $rules);
//            if ($validator->fails()) {
//                return response()->json(['errors' => $validator->errors()], 400);
//            }
//        }

        return $rules;
    }


    public function messages()
    {
        return [

        ];
    }
}
