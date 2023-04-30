<?php

namespace App\Http\Requests\SupplierPaymentMethod;

use Illuminate\Foundation\Http\FormRequest;

class PaymentSubmitStore extends FormRequest
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
        $rules = [
            'supplier_payment_method_id' => 'required|string',
            'amount' => 'required',
            'payment_date' => 'required|date',
            'particulars' => 'nullable',
        ];
        if($request['payment_options'] == '2'){
            $rules['purchase_id'] =  'required|string';
        }
        if($request['payment_type'] == 'bank_transfer'){
            $rules['transaction_no'] =  'nullable|string';
            $rules['transaction_date'] =  'required';
        }else
        if($request['payment_type'] == 'cheque'){
            $rules['cheque_no'] =  'required';
            $rules['cheque_date'] =  'required';
        }else
        if($request['payment_type'] == 'online_banking'){
            $rules['transaction_no'] =  'required';
            $rules['transaction_date'] =  'required';
        }else
        if($request['payment_type'] == 'mobile_banking'){
            $rules['transaction_no'] =  'required|string';
            $rules['transaction_date'] =  'required';
        }else
        if($request['payment_type'] == 'card'){
            $rules['transaction_no'] =  'required|string';
            $rules['transaction_date'] =  'required';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'supplier_payment_method_id.required' => 'The Payment Type field is required',
            'purchase_id.required' => 'Please select a Invoice',
        ];
    }
}
