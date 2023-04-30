<?php

namespace App\Http\Requests\MpUser;

use Illuminate\Foundation\Http\FormRequest;

class MpUserStore extends FormRequest
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
        return [
            'name' => 'required|string',
            'email' => 'required|string|unique:marketplace_users,email',
            'password' => 'sometimes|required|string',
            'mobile' => 'required|string',
            'user_type_id' => 'sometimes|required|integer',
            'gender' => 'required|string',
            'date_of_birth' => 'nullable|string',
            'status' => 'nullable|integer',
            'warehouse_id' => 'nullable|integer',
            'warehouse_type_name' => 'nullable|string',
            'details' => 'nullable|string|max:1024',
//            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ];
    }


    public function messages()
    {
        return [

        ];
    }
}
