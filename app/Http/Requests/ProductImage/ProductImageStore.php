<?php

namespace App\Http\Requests\ProductImage;

use Illuminate\Foundation\Http\FormRequest;

class ProductImageStore extends FormRequest
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
        $data = $this->all();
//        dd($data['img']);
        return [
            'product_id' => 'required|integer',
            "img"    => "required|array|min:1|max:5",
            'img.*' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=1200,min_height=1200',
        ];

//        $request = $this->instance()->all();
//        $images = $request['img'];
//        $rules = [
//            'product_id' => 'required|integer',
//            'img' => 'required|min:1|max:5'
//        ];
//        foreach($images as $key => $file) {
//            $rules['img.'.$key] = 'mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=1200,min_height=1200';
//        }
//        return $rules;
    }


    public function messages()
    {
        return [
            'img.*.required' => 'The Image Field is required',
            'img.*.mimes' => 'Only jpeg,png,jpg,gif and svg images are allowed',
            'img.*.max' => 'Maximum file size to upload is 2MB (2048 KB)',
            'img.*.dimensions' => 'The Image has invalid image dimensions correct dimension is (1200*1200) ',
        ];
    }
}
