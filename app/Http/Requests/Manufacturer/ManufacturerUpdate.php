<?php

namespace App\Http\Requests\Manufacturer;

use Illuminate\Foundation\Http\FormRequest;

class ManufacturerUpdate extends FormRequest
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
//        dd($request);

        $rules = [
            'name' => 'required|string|max:255',
//            'email' => 'required|string|unique:manufacturers,email,'. $this->manufacturer->id,
            'phone' => 'nullable|string',
            'website' => 'nullable|string',
            'country_name' => 'required|string',
            'status' => 'nullable|integer',
            'description' => 'nullable',
//            'img' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ];

        if(!$request['parenttype']){
            $rules['email'] = 'nullable|string|unique:manufacturers,email,'. $this->manufacturer->id;
        }

        return $rules;
    }

    public function messages()
    {
        return [

        ];
    }
}
