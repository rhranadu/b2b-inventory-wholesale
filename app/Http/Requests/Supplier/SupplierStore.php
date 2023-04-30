<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class SupplierStore extends FormRequest
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
            'email' => 'nullable|string|unique:suppliers,email',
            'mobile' => 'nullable|string',
            'status' => 'nullable|integer',
            'address' => 'nullable|string|max:1024',
            'details' => 'nullable|string',
            'website' => 'nullable|string',
            'type' => 'required',
//            'img' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ];
    }
}
