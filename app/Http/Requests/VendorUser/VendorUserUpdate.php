<?php

namespace App\Http\Requests\VendorUser;

use Illuminate\Foundation\Http\FormRequest;

class VendorUserUpdate extends FormRequest
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
            'email' => 'required|string|unique:vendor_users,email,'. $this->user['id'],
            'password' => 'sometimes|nullable|string',
            'mobile' => 'required|string',
            'country_id' => 'nullable|integer',
            'state_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'post_code' => 'nullable|integer',
            'user_role_id' => 'sometimes|required|integer',
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
