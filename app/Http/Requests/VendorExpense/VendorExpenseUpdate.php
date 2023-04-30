<?php

namespace App\Http\Requests\VendorExpense;

use Illuminate\Foundation\Http\FormRequest;

class VendorExpenseUpdate extends FormRequest
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
            'particulars' => 'required|string',
            'expense_date' => 'required|date',
            'pay_amount' => 'required',
        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
