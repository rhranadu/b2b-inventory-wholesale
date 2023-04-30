<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class VendorUpdate extends FormRequest
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
            'email' => 'required|string|unique:vendors,email,'. $this->vendor->id,
            'phone' => 'required|string',
            'address' => 'nullable|string|max:1024',
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
