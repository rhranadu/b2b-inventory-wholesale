<?php

namespace App\Http\Requests\SupplierPaymentMethod;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodUpdate extends FormRequest
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
//        $id = isset($request['supplier_payment_method']) ? $request['supplier_payment_method'] : null;

    $rules = [
            'payment_type' => 'required|string',
            'visible_name' => 'nullable|string',
//            'visible_name' => 'nullable|string|unique:supplier_payment_methods,visible_name,'. $this->supplier['id'],
        ];

        if($request['payment_type'] == 'bank_transfer'){
            $rules['account_no'] =  'required|string';
            $rules['bank_name'] =  'required|string';
            $rules['branch_name'] =  'required|string';
            $rules['bank_account_name'] =  'required|string';
        }
        if($request['payment_type'] == 'cheque'){
            $rules['account_no'] =  'required|string';
            $rules['bank_name'] =  'required|string';
            $rules['branch_name'] =  'required|string';
            $rules['bank_account_name'] =  'required|string';
        }
        if($request['payment_type'] == 'online_banking'){
            $rules['account_no'] =  'required|string';
            $rules['bank_name'] =  'required|string';
            $rules['branch_name'] =  'required|string';
            $rules['bank_account_name'] =  'required|string';
        }
        if($request['payment_type'] == 'mobile_banking'){
            $rules['account_no'] =  'required|string';
            $rules['mobile_service_name'] =  'required|string';
        }


        return $rules;
    }
}
