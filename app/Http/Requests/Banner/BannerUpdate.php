<?php

namespace App\Http\Requests\Banner;

use Illuminate\Foundation\Http\FormRequest;

class BannerUpdate extends FormRequest
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
            'image' => 'required',
            'meta_info' => 'nullable|string|max:1024',
            'alt_info' => 'nullable|string|max:1024',
            'url' => 'nullable|string',
            'type' => 'required|string',
            'status' => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
//            'img.required' => 'The image field is required',

        ];
    }
}
