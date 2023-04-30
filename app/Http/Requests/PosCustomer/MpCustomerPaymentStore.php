<?php

namespace App\Http\Requests\PosCustomer;

use Illuminate\Foundation\Http\FormRequest;

class MpCustomerPaymentStore extends FormRequest
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
        $rules =  [
                'mp_customer_id' => 'required',
                'paymentMethod' => 'required',
                'amount' => 'required',
            ];
        if($request['payment_options'] == '2'){
            $rules['sale_id'] =  'required';
        }
        if($request['payment_type'] == 'cash'){
            $rules['payment_date'] =  'required';
        }else
        if($request['payment_type'] == 'bank_transfer'){
//            $rules['bank_name'] =  'required';
//            $rules['branch_name'] =  'required';
//            $rules['transaction_no'] =  'required';
//            $rules['transaction_date'] =  'required';
//            $rules['payment_date'] =  'required';
//            $rules['account_no'] =  'required';
//            $rules['bank_account_name'] =  'required';
        }else
        if($request['payment_type'] == 'cheque'){
            $rules['cheque_no'] =  'required';
            $rules['cheque_date'] =  'required';
            $rules['bank_name'] =  'required';
            $rules['branch_name'] =  'required';
            $rules['transaction_date'] =  'required';
            $rules['payment_date'] =  'required';
            $rules['account_no'] =  'required';
            $rules['bank_account_name'] =  'required';
        }else
        if($request['payment_type'] == 'online_banking'){
            $rules['transaction_no'] =  'required';
            $rules['bank_name'] =  'required';
            $rules['branch_name'] =  'required';
            $rules['transaction_date'] =  'required';
            $rules['payment_date'] =  'required';
            $rules['account_no'] =  'required';
            $rules['bank_account_name'] =  'required';
        }else
        if($request['payment_type'] == 'mobile_banking'){
            $rules['account_no'] =  'required|string';
            $rules['mobile_service_name'] =  'required|string';
            $rules['transaction_no'] =  'required|string';
            $rules['transaction_date'] =  'required';
            $rules['payment_date'] =  'required';
        }else
        if($request['payment_type'] == 'card'){
            $rules['transaction_no'] =  'required|string';
            $rules['transaction_date'] =  'required';
            $rules['payment_date'] =  'required';
            $rules['card_name'] =  'required';
            $rules['card_number'] =  'required';
        }
        return $rules;
    }
}
