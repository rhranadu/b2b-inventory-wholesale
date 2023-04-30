<?php

namespace App\Http\Requests\UserRole;

use Illuminate\Foundation\Http\FormRequest;

class UserRoleStore extends FormRequest
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
            'name' => 'required|string|unique:user_roles,name',
            'user_type_id' => 'required|integer',
        ];
    }


    public function messages()
    {
        return [
            'user_type_id.required' => 'The User type field is required!',
        ];
    }
}
