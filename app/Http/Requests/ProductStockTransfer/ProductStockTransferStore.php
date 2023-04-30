<?php

namespace App\Http\Requests\ProductStockTransfer;

use Illuminate\Foundation\Http\FormRequest;

class ProductStockTransferStore extends FormRequest
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
            'from_warehouse_id' => 'required|integer',
            'to_warehouse_id' => 'required|integer',
            'stock_details_id' => 'required|integer',
            'to_warehouse_detail_id' => 'nullable|string',
            'from_warehouse_detail_id' => 'nullable|string',
            'quantity' => 'required|integer',
            'memo_no' => 'nullable|string',

        ];

        return $rules;
    }


    public function messages()
    {
        return [
            'from_warehouse_id.required' => 'Warehouse From is required',
            'to_warehouse_id.required' => 'Warehouse To is required',
            'stock_details_id.required' => 'Product name is required',
            'quantity.required' => 'Quantity is required',

        ];
    }
}
