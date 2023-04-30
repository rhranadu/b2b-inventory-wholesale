<?php

namespace App\Http\Requests\State;

use Illuminate\Foundation\Http\FormRequest;

class StateStore extends FormRequest
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
            'country_id' => 'required|integer',
        ];
    }


    public function messages()
    {
        return [

        ];
    }
}
