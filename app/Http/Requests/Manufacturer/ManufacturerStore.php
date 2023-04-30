<?php

namespace App\Http\Requests\Manufacturer;

use Illuminate\Foundation\Http\FormRequest;

class ManufacturerStore extends FormRequest
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
//            'email' => 'nullable|string|unique:manufacturers,email',
            'phone' => 'nullable|string',
            'website' => 'nullable|string',
            'country_name' => 'required|string',
            'status' => 'nullable|integer',
            'description' => 'nullable',
//            'image' => 'nullable',
        ];
        if($request['parenttype']){
            $rules['email'] = 'nullable|string|unique:parent_manufacturers,email';
        }else{
            $rules['email'] = 'nullable|string|unique:manufacturers,email';
        }

        return $rules;
    }


    public function messages()
    {
        return [

        ];
    }
}
