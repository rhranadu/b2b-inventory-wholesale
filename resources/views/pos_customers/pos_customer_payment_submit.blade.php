@extends('layouts.crud-master')
@section('title', 'Pos Customer Payment Add')
@push('css')
    <style>
        input[type=file]{
            /*width:90px;*/
            /*color:transparent;*/
        }
    </style>
@endpush
@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    @include('component.message')
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form
                                method="POST" action="{{ route('admin.pos_customer_payment.store') }}" accept-charset="UTF-8"
                                enctype="multipart/form-data">
                            @csrf
                                <div class="form-element-list">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                                <div class="form-group">
                                            {{--                                        <div class="bootstrap-select">--}}
                                            <label>
                                                Payment Options<span style="color: red; font-size: 10px"><sup>*</sup></span>
                                            </label>
                                            <select name="payment_options" id="payment_options" class="form-control">
                                                <option value="1" {{ $sale_id != 0 ? 'disabled' : '' }}{{ $sale_id == 0 ? 'selected' : '' }}>Pay As Whole</option>
                                                <option value="2" {{ $sale_id != 0 ? 'selected' : '' }}>Pay Per Invoice</option>
                                            </select><br/>
                                            @error('payment_options')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                            {{--                                        </div>--}}
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" id="pay_per_invoice">
                                            <div class="form-group">
                                                <div id="purchase_id" class=" d-none">
                                                    <label>Payment For<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                    <select name="sale_id" id="saleId" class="form-control">
                                                        @if(!empty($sale_id) && !empty($sale_data)):
                                                            <option value="{{ $sale_id }}" >{{$sale_data['invoice_no']}} [Due: {{$sale_data['due_payment']}}]</option>
                                                        @endif
                                                    </select><br/>
                                                    @error('sale_id')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <input type="hidden" value="{{$pos_customer->id}}" name="pos_customer_id">
                                                <input type="hidden" value="" name="payment_type" id="payment_type">
                                                <div class="">
                                                    <div class="form-group">
                                                        <label for="paymentMethod">Payment Type:</label>
{{--                                                        <select id="paymentMethod" name="paymentMethod" class="form-group form-control float-left payment_type">--}}
{{--                                                            <option selected value="cash">Cash</option>--}}
{{--                                                            <option value="card">Card</option>--}}
{{--                                                            <option value="cheque">Check</option>--}}
{{--                                                            <option value="bank_transfer">Bank Transfer</option>--}}
{{--                                                            <option value="online_banking">Online Banking</option>--}}
{{--                                                            <option value="mobile_banking">Mobile Banking</option>--}}
                                                        <select id="paymentMethod" name="paymentMethod" class="selectpicker form-control"
                                                                data-live-search="true">
                                                            <option {{ old('paymentMethod') == "" ? "selected" : "" }} value="">Select Payment Type</option>
                                                            <option {{ old('paymentMethod') == "cash" ? "selected" : "" }} value="cash" >{{ 'Cash' }}</option>
                                                            <option {{ old('paymentMethod') == "bank_transfer" ? "selected" : "" }} value="bank_transfer" >{{ 'Bank Transfer' }}</option>
                                                            <option {{ old('paymentMethod') == "cheque" ? "selected" : "" }} value="cheque" >{{ 'Cheque' }}</option>
                                                            <option {{ old('paymentMethod') == "card" ? "selected" : "" }} value="card" >{{ 'Card' }}</option>
                                                            <option {{ old('paymentMethod') == "online_banking" ? "selected" : "" }} value="online_banking" >{{ 'Online Banking' }}</option>
                                                            <option {{ old('paymentMethod') == "mobile_banking" ? "selected" : "" }} value="mobile_banking" >{{ 'Mobile Banking' }}</option>
                                                        </select>
                                                    </div>
                                                    @error('paymentMethod')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
{{--                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">--}}
{{--                                            <div class="form-group ">--}}
{{--                                                <div class="bootstrap-select ">--}}
{{--                                                    <label for="#">Payment Date<span style="color: red; font-size: 10px"><sup>*</sup></span></label>--}}
{{--                                                    <input required name="payment_date" data-date="" data-date-format="DD MMMM YYYY"--}}
{{--                                                           type="date" class="form-control"--}}
{{--                                                           id="payment_date">--}}
{{--                                                    <p id=""></p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group ">
                                                <label for="amount">Amount<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                <input class="form-control" id="amount"
                                                        value=""
                                                        autocomplete="off" name="amount" type="number" min="0">
                                                @error('amount')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group ">
                                                <label for="note">Note/Comment</label>
                                                <textarea class="form-control" id="note"
                                                          value=""
                                                          autocomplete="off" name="comment" type="text"></textarea>
                                                @error('comment')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
{{--                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">--}}
{{--                                            <div class="form-group ">--}}
{{--                                                <label for="particulars">Particulars<span style="color: red; font-size: 10px"><sup>*</sup></span></label>--}}
{{--                                                <input class="form-control" id="particulars"--}}
{{--                                                        value=""--}}
{{--                                                        autocomplete="off" name="particulars" type="text">--}}
{{--                                                @error('particulars')--}}
{{--                                                <strong class="text-danger" role="alert">--}}
{{--                                                    <span>{{ $message }}</span>--}}
{{--                                                </strong>--}}
{{--                                                @enderror--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="bank_name">
                                            <div class="form-group ">
                                                <label for="bank_name">Bank Name<span style="color: red; font-size: 10px"><sup></sup></span></label>
                                                <input class="form-control"
                                                       value=""
                                                       autocomplete="off" name="bank_name" type="text">
                                                @error('bank_name')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="branch_name">
                                            <div class="form-group ">
                                                <label for="branch_name">Branch Name<span style="color: red; font-size: 10px"><sup></sup></span></label>
                                                <input class="form-control"
                                                       value=""
                                                       autocomplete="off" name="branch_name" type="text">
                                                @error('branch_name')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="bank_account_name">
                                            <div class="form-group ">
                                                <label for="bank_account_name">Bank Account Name<span style="color: red; font-size: 10px"><sup></sup></span></label>
                                                <input class="form-control"
                                                       value=""
                                                       autocomplete="off" name="bank_account_name" type="text">
                                                @error('bank_account_name')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="account_no">
                                            <div class="form-group ">
                                                <label for="account_no">Account No.<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                <input class="form-control"
                                                       value=""
                                                       autocomplete="off" name="account_no" type="text">
                                                @error('account_no')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="transaction_no">
                                            <div class="form-group ">
                                                <label for="transaction_no">Transaction No.<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                <input class="form-control"
                                                       value=""
                                                       autocomplete="off" name="transaction_no" type="text">
                                                @error('transaction_no')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="swift_code">
                                            <div class="form-group ">
                                                <label for="swift_code">Swift Code<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                <input class="form-control"
                                                       value=""
                                                       autocomplete="off" name="swift_code" type="text">
                                                @error('swift_code')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="mobile_service_name">
                                            <div class="form-group ">
                                                <label for="mobile_service_name">Mobile Service Name<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                <input class="form-control"
                                                       value=""
                                                       autocomplete="off" name="mobile_service_name" type="text">
                                                @error('mobile_service_name')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="cheque_no">
                                            <div class="form-group ">
                                                <label for="cheque_no">Cheque No.<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                <input class="form-control"
                                                            value=""
                                                            autocomplete="off" name="cheque_no" type="text">
                                                @error('cheque_no')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="cheque_date">
                                            <div class="form-group ">
                                                <div class="bootstrap-select ">
                                                    <label for="#">Cheque Date<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                    <input  name="cheque_date" data-date="" data-date-format="DD MMMM YYYY"
                                                            type="date" class="form-control"
                                                            >
                                                    <p id=""></p>
                                                    @error('cheque_date')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="transaction_date">
                                            <div class="form-group ">
                                                <div class="bootstrap-select ">
                                                    <label for="transaction_date">Transaction Date<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                    <input  name="transaction_date" data-date="" data-date-format="DD MMMM YYYY"
                                                            type="date" class="form-control"
                                                            >
                                                    @error('transaction_date')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="card_name">
                                            <div class="form-group ">
                                                <label for="card_name">Card Name<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                <input class="form-control"
                                                        value=""
                                                        autocomplete="off" name="card_name" type="text">
                                                @error('card_name')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="card_number">
                                            <div class="form-group ">
                                                <label for="card_number">Card Number<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                <input class="form-control"
                                                        value=""
                                                        autocomplete="off" name="card_number" type="text">
                                                @error('card_number')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="payment_date" >
                                            <div class="form-group ">
                                                <div class="bootstrap-select ">
                                                    <label for="payment_date">Payment Date</label>
                                                    <input  name="payment_date" data-date="" data-date-format="DD MMMM YYYY"
                                                            type="date" class="form-control"
                                                            >
                                                    @error('payment_date')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="bank_receipt_img" >
                                                <div class="form-group ">
                                                    <div class="bootstrap-select ">
                                                        <label for="img">Bank/Cheque Receipt<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                        <input class="form-control" title="Bank/Cheque Receipt" value="{{ old('img') }}" autocomplete="off" name="image"
                                                            type="file">
                                                        @error('image')
                                                        <strong class="text-danger" role="alert">
                                                            <span>{{ $message }}</span>
                                                        </strong>
                                                        @enderror
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success waves-effect">Receive Payment</button>
                            </form>


                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $("#payment_options").on('change', function (e) {
            if($("#payment_options").val() == 2){
                $("#purchase_id").removeClass('d-none')
            }else {
                $("#purchase_id").removeClass('d-none').addClass('d-none')
            }
        });
        if($("#payment_options").val() == 2){
            $("#purchase_id").removeClass('d-none')
        }else {
            $("#purchase_id").removeClass('d-none').addClass('d-none')
        }

        $("#saleId").select2({
            width: '100%',
            allowClear: true,
            ajax: {
                url: "{{ url("admin/pos_customer-invoices/").'/'.$pos_customer->id.'?sale_id='.$sale_id }}",
                dataType: 'json',
                type: 'GET',
                data: function (params) {
                    return {search: params.term};
                },
                processResults: function (data, params) {

                    return {
                        results: $.map(data, function (item, i) {
                            return {id: i, text: item}
                        }),

                    };
                },
                cache: true
            },
            placeholder: 'Search for a invoice',
        });
        $('#bank_name').hide();
        $('#branch_name').hide();
        $('#bank_account_name').hide();
        $('#account_no').hide();
        $('#swift_code').hide();
        $('#mobile_service_name').hide();
        $('#cheque_date').hide();
        $('#cheque_no').hide();
        $('#payment_date').hide();
        $('#bank_receipt_img').hide();
        $('#transaction_no').hide();
        $('#transaction_date').hide();
        $('#card_name').hide();
        $('#card_number').hide();

        if (($('#paymentMethod option:selected').text() == "Bank Transfer")){
            $('#bank_name').show();
            $('#branch_name').show();
            $('#bank_account_name').show();
            $('#account_no').show();
            $('#swift_code').show();
            $('#payment_date').show();
            $('#mobile_service_name').hide();
            $('#transaction_no').show();
            $('#transaction_date').show();
            $('#bank_receipt_img').show();
        }else if (($('#paymentMethod option:selected').text() == "Cheque")) {
            $('#bank_name').show();
            $('#branch_name').show();
            $('#bank_account_name').show();
            $('#account_no').show();
            $('#swift_code').show();
            $('#mobile_service_name').hide();
            $('#payment_date').show();
            $('#bank_receipt_img').show();
            $('#transaction_no').hide();
            $('#transaction_date').hide();
            $('#card_name').hide();
            $('#card_number').hide();
            $('#cheque_date').show();
            $('#cheque_no').show();
        }else if (($('#paymentMethod option:selected').text() == "Online Banking") ) {
            $('#bank_name').show();
            $('#branch_name').show();
            $('#bank_account_name').show();
            $('#account_no').show();
            $('#swift_code').show();
            $('#mobile_service_name').hide();
            $('#payment_date').show();
            $('#bank_receipt_img').hide();
            $('#transaction_no').show();
            $('#transaction_date').show();
            $('#card_name').hide();
            $('#card_number').hide();
            $('#cheque_date').hide();
            $('#cheque_no').hide();
        }else if (($('#paymentMethod option:selected').text() == "Cash")){
            $('#bank_name').hide();
            $('#branch_name').hide();
            $('#bank_account_name').hide();
            $('#account_no').hide();
            $('#swift_code').hide();
            $('#payment_date').show();
            $('#mobile_service_name').hide();
            $('#transaction_date').hide();
        }else if (($('#paymentMethod option:selected').text() == "Mobile Banking")){
            $('#bank_name').hide();
            $('#branch_name').hide();
            $('#bank_account_name').hide();
            $('#account_no').show();
            $('#swift_code').hide();
            $('#mobile_service_name').show();
            $('#payment_date').show();
            $('#transaction_no').show();
            $('#transaction_date').show();
        }else if (($('#paymentMethod option:selected').text() == "Card")){
            $('#bank_name').hide();
            $('#branch_name').hide();
            $('#bank_account_name').hide();
            $('#account_no').hide();
            $('#swift_code').hide();
            $('#payment_date').show();
            $('#mobile_service_name').hide();
            $('#transaction_no').show();
            $('#transaction_date').show();
            $('#card_name').show();
            $('#card_number').show();
        }else {
            $('#bank_name').hide();
            $('#branch_name').hide();
            $('#bank_account_name').hide();
            $('#account_no').hide();
            $('#swift_code').hide();
            $('#mobile_service_name').hide();
            $('#payment_date').hide();
            $('#transaction_no').hide();
            $('#transaction_date').hide();
            $('#card_name').hide();
            $('#card_number').hide();
        }

        @error('cheque_no')
        $('#cheque_no').show();
        $('#cheque_date').show();
        @enderror

        @error('transaction_no')
        $('#transaction_no').show();
        $('#transaction_date').show();
        @enderror

        $("#paymentMethod").change(function () {
            var type = $(this).val();
            // var type = $(this).find(":selected").data('type');
            $('#payment_type').val(type);
            if (type === 'cheque' ){
                $('#bank_name').show();
                $('#branch_name').show();
                $('#bank_account_name').show();
                $('#account_no').show();
                $('#swift_code').show();
                $('#mobile_service_name').hide();
                $('#payment_date').show();
                $('#bank_receipt_img').show();
                $('#transaction_no').hide();
                $('#transaction_date').hide();
                $('#card_name').hide();
                $('#card_number').hide();
                $('#cheque_date').show();
                $('#cheque_no').show();
            }else  if (type === 'bank_transfer' ){
                $('#bank_name').show();
                $('#branch_name').show();
                $('#bank_account_name').show();
                $('#account_no').show();
                $('#swift_code').show();
                $('#mobile_service_name').hide();
                $('#cheque_no').hide();
                $('#cheque_date').hide();
                $('#payment_date').show();
                $('#bank_receipt_img').show();
                $('#transaction_no').show();
                $('#transaction_date').show();
                $('#card_name').hide();
                $('#card_number').hide();
            }else  if (type === 'card' ){
                $('#bank_name').hide();
                $('#branch_name').hide();
                $('#bank_account_name').hide();
                $('#account_no').hide();
                $('#swift_code').hide();
                $('#mobile_service_name').hide();
                $('#cheque_no').hide();
                $('#cheque_date').hide();
                $('#payment_date').show();
                $('#bank_receipt_img').hide();
                $('#transaction_no').show();
                $('#transaction_date').show();
                $('#card_name').show();
                $('#card_number').show();
            }else  if (type === 'online_banking' ){
                $('#bank_name').show();
                $('#branch_name').show();
                $('#bank_account_name').show();
                $('#account_no').show();
                $('#swift_code').show();
                $('#mobile_service_name').hide();
                $('#cheque_no').hide();
                $('#cheque_date').hide();
                $('#payment_date').show();
                $('#bank_receipt_img').hide();
                $('#transaction_no').show();
                $('#transaction_date').show();
                $('#card_name').hide();
                $('#card_number').hide();
            }else  if (type === 'mobile_banking' ){
                $('#bank_name').hide();
                $('#branch_name').hide();
                $('#bank_account_name').hide();
                $('#account_no').show();
                $('#swift_code').hide();
                $('#mobile_service_name').show();
                $('#cheque_no').hide();
                $('#cheque_date').hide();
                $('#payment_date').show();
                $('#bank_receipt_img').hide();
                $('#transaction_no').show();
                $('#transaction_date').show();
                $('#card_name').hide();
                $('#card_number').hide();
            }else  if (type === 'cash' ){
                $('#bank_name').hide();
                $('#branch_name').hide();
                $('#bank_account_name').hide();
                $('#account_no').hide();
                $('#swift_code').hide();
                $('#mobile_service_name').hide();
                $('#cheque_no').hide();
                $('#cheque_date').hide();
                $('#payment_date').show();
                $('#bank_receipt_img').hide();
                $('#transaction_no').hide();
                $('#transaction_date').hide();
                $('#card_name').hide();
                $('#card_number').hide();
            }else {
                $('#bank_name').hide();
                $('#branch_name').hide();
                $('#bank_account_name').hide();
                $('#account_no').hide();
                $('#swift_code').hide();
                $('#mobile_service_name').hide();
                $('#cheque_no').hide();
                $('#card_name').hide();
                $('#card_number').hide();
                $('#cheque_date').hide();
                $('#payment_date').hide();
                $('#transaction_date').hide();
                $('#transaction_no').hide();
                $('#bank_receipt_img').hide();
            }
            // if (type === 'cheque' || type === 'bank_transfer' || type === 'online_banking'){
            //     $('#bank_receipt_img').show();
            // }else{
            //     $('#bank_receipt_img').hide();
            // }
        })
    </script>
@endpush
