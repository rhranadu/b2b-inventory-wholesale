@extends('layouts.crud-master')
@section('title', 'Supplier Payment Method Add')
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
                                method="POST" action="{{ route('admin.supplier_payment.store') }}" accept-charset="UTF-8"
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
                                                    <option value="1" selected>Pay As Whole</option>
                                                    <option value="2" >Pay Per Invoice</option>
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
                                                    <select name="purchase_id" id="purchaseId" class="form-control">
                                                    </select><br/>
                                                    @error('purchase_id')
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
                                                <input type="hidden" value="{{$supplier->id}}" name="supplier_id">
                                                <input type="hidden" value="" name="payment_type" id="payment_type">
                                                <div class="bootstrap-select">
                                                    <label>Payment Type<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                    <select name="supplier_payment_method_id" id="supplier_payment_method_id" class="selectpicker form-control" data-live-search="true">
                                                        <option value="">*Select Payment Type</option>
                                                        @foreach($supplier_payment_methods as $supplier_payment_method)
                                                            <option value="{{ $supplier_payment_method->id }}" data-type="{{ $supplier_payment_method->payment_type }}">{{ $supplier_payment_method->visible_name }}</option>
                                                        @endforeach
                                                    </select><br/>
                                                    @error('supplier_payment_method_id')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group ">
                                                <div class="bootstrap-select ">
                                                    <label for="#">Payment Date<span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                    <input required name="payment_date" data-date="" data-date-format="DD MMMM YYYY"
                                                           type="date" class="form-control"
                                                           id="payment_date">
                                                    <p id=""></p>
                                                </div>
                                            </div>
                                        </div>
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
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group ">
                                                <label for="particulars">Particulars</label>
                                                <input class="form-control" id="particulars"
                                                       value=""
                                                       autocomplete="off" name="particulars" type="text">
                                                @error('particulars')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group ">
                                                <label for="note">Note</label>
                                                <textarea class="form-control" id="note"
                                                          value=""
                                                          autocomplete="off" name="note" type="text"></textarea>
                                                @error('note')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

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
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="transaction_no_optional">
                                            <div class="form-group ">
                                                <label for="transaction_no_optional">Transaction No.</label>
                                                <input class="form-control"
                                                       value=""
                                                       autocomplete="off" name="transaction_no_optional" type="text">
                                                @error('transaction_no_optional')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="transaction_date" >
                                            <div class="form-group ">
                                                <div class="bootstrap-select ">
                                                    <label for="#">Transaction Date <span style="color: red; font-size: 10px"><sup>*</sup></span></label>
                                                    <input  name="transaction_date" data-date="" data-date-format="DD MMMM YYYY"
                                                            type="date" class="form-control"
                                                    >
                                                    <p id=""></p>
                                                    @error('transaction_date')
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
                                                    <label for="img">Bank/Cheque Receipt </label>
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
                                <button type="submit" class="btn btn-success waves-effect">Submit</button>
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
        $("#purchaseId").select2({
            width: '100%',
            ajax: {
                url: "{{ url("admin/supplier-invoices").'/'.$supplier->id }}",
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
        $('#cheque_no').hide();
        $('#cheque_date').hide();
        $('#transaction_date').hide();
        $('#transaction_no').hide();
        $('#transaction_no_optional').hide();
        $('#bank_receipt_img').hide();

        @error('cheque_no')
        $('#cheque_no').show();
        $('#cheque_date').show();
        @enderror

        @error('transaction_no')
        $('#transaction_no').show();
        $('#transaction_date').show();
        @enderror

        $("#supplier_payment_method_id").change(function () {
            var id = $(this).val();
            var type = $(this).find(":selected").data('type');
            $('#payment_type').val(type);
            if (type === 'cheque'){
                $('#transaction_date').hide();
                $('#transaction_no').hide();
                $('#transaction_no_optional').hide();
                $('#cheque_date').show();
                $('#cheque_no').show();
            }else if (type === 'bank_transfer'){
                $('#transaction_date').show();
                $('#transaction_no_optional').show();
                $('#transaction_no').hide();
                $('#cheque_date').hide();
                $('#cheque_no').hide();
            }else if (type === 'online_banking'){
                $('#transaction_date').show();
                $('#transaction_no').show();
                $('#transaction_no_optional').hide();
                $('#cheque_date').hide();
                $('#cheque_no').hide();
            }else if (type === 'card'){
                $('#transaction_date').show();
                $('#transaction_no').show();
                $('#transaction_no_optional').hide();
                $('#cheque_date').hide();
                $('#cheque_no').hide();
            }else if (type === 'mobile_banking'){
                $('#transaction_date').show();
                $('#transaction_no').show();
                $('#transaction_no_optional').hide();
                $('#cheque_date').hide();
                $('#cheque_no').hide();
            }else {
                $('#cheque_date').hide();
                $('#cheque_no').hide();
                $('#transaction_date').hide();
                $('#transaction_no').hide();
                $('#transaction_no_optional').hide();
            }
            if (type === 'cheque' || type === 'bank_transfer' || type === 'online_banking'){
                $('#bank_receipt_img').show();
            }else{
                $('#bank_receipt_img').hide();
            }
        })
    </script>
@endpush
