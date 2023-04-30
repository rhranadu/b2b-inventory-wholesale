<div class="appendModelContent">
    @if(!empty($saleData->posCustomer))
    <h4 class="p_c_name">{!! $saleData->posCustomer->name !!} <input type="hidden" class="customer_id" value="{!! 'pos_'.$saleData->posCustomer->id !!}"></h4>
    @else
    <h4 class="p_c_name">{!! $saleData->marketplaceUser->name !!} <input type="hidden" class="customer_id" value="{!! 'mp_'.$saleData->marketplaceUser->id !!}"></h4>
    @endif
    <p class="sale_pro_item">Items: {!! $saleData->items !!}</p>
    <h1>Total: <span class="sale_total">{!! $saleData->final_total !!}</span></h1>
    <h3>Paid: <span class="sale_paid">{!! $saleData->total_payment !!}</span></h3>
    <h3>Due: <span class="sale_due">{!! $saleData->due_payment !!}</span></h3>
    <div class="form-group">
        <label for="paymentMethod">Payment Type:</label>
        <select id="paymentMethod" class="form-group form-control float-left payment_type">
            <option selected value="cash">Cash</option>
            <option value="card">Card</option>
            <option value="check">Check</option>
        </select>
    </div>

    <input type="hidden" class="last_sale_id" value="{!! $saleData->id !!}">

    <div class="appendCardInput" style="display: none">

    </div>

    <div class="appendCheckInput" style="display: none">

    </div>

    <div class="form-group">
        <label for="paymentAmount">Pay Amount</label>
        <input type='hidden' name="sale_due" id="sale_due" class='form-control' autocomplete="off" value="{!! $saleData->due_payment !!}">
        <input type='text' id="paymentAmount" class='form-control pay_input_field' autocomplete="off" value="{!! $saleData->due_payment !!}">
    </div>

    <div class="appendGivBackInput">
        <div class="form-group">
            <label for="formGroupExampleInput2" class="give_back">Give Back: 0 <input type="hidden" value="0"></label>
        </div>
    </div>
</div>
