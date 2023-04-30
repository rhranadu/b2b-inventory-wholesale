<?php

namespace App\Http\Requests\WarehouseDetail;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseDetailStore extends FormRequest
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
        $data = $this->all();
        $id = NULL;
        $id = $data['warehouse_id'];
//        if($this->warehouseDetail)
//        {
//            $id = $this->warehouseDetail->warehouse_id;
//        }
//        dd($id);
        return [
            'warehouse_id' => 'required|integer',
            'section_code' => 'nullable|string',
            'section_name' => 'required|string|max:512',
            'parent_section_id' => 'nullable|integer',
            'status' => 'nullable|integer',
        ];
    }


    public function messages()
    {
        return [
            'warehouse_id.required' => 'The warehouse field is required',
        ];
    }
}
