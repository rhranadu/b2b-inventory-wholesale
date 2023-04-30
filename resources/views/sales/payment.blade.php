@extends('layouts.crud-master')
@section('title', 'Sale Payment')
@push('css')
    <style>
        .card {
            margin-bottom: 1.5rem
        }

        .card {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid #c8ced3;
            border-radius: .25rem
        }

        .card-header:first-child {
            border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0
        }

        .card-header {
            padding: .75rem 1.25rem;
            margin-bottom: 0;
            background-color: #f0f3f5;
            border-bottom: 1px solid #c8ced3
        }

        .card_pay {
            display: none;
        }

        .check_no_pay {
            display: none;
        }
    </style>
@endpush

@section('main_content')
    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">View Sale Payment <i class="mr-2"></i><small>View Sale Payment</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.sale.index') }}" class="btn btn-sm btn-light-success">
                    <i class="fa fa-plus"></i> Sales List
                </a>
            </div>
        </div>
        <div class="card-body">

            @include('component.message')
            <div id="ui-view" data-select2-id="ui-view">
                <div>
                    <div class="card">
                        <div class="card-header">Invoice
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>#{{ $sale->invoice_no }}</strong>
                                    <div>Date: {{ $sale->created_at }}</div>
                                    <div>
                                        {{-- <a class="btn btn-sm btn-secondary float-right mr-1 d-print-none" href="#" onclick="javascript:window.print();" data-abc="true">
                                             <i class="fa fa-print"></i> Print</a>
                                         <a class="btn btn-sm btn-info float-right mr-1 d-print-none" href="#" data-abc="true">
                                             <i class="fa fa-save"></i> Save</a>--}}
                                    </div>
                                </div>
                                <div class="col-md-4"></div>
                                <div class="col-md-4 float-right text-center">
                                    @if(isset($sale->payment))
                                        @if(isset($sale->payment->last()->status) AND $sale->payment->last()->status == 'FP')
                                            <span class="btn btn-sm btn-success">Paid</span>
                                        @elseif(isset($sale->payment->last()->status) AND $sale->payment->last()->status == 'PP')
                                            <span class="btn btn-sm btn-warning">Partial Paid</span>
                                        @else
                                            <span class="btn btn-sm btn-danger">Not Yet</span>
                                        @endif
                                    @endif

                                </div>
                            </div>


                        </div>
                        <div class="card-body">
                            <div class="row" style="padding: 20px;">
                                <div class="col-sm-4" style="margin-bottom: 30px">
                                    <h6 class="mb-3">From:</h6>
                                    <div>
                                        <h5>{{ $sale->vendor->name }},</h5>
                                    </div>
                                    <div><b>Email:</b> {{ $sale->vendor->email }}</div>
                                    <div><b>Address:</b> {{ $sale->vendor->address }}</div>
                                </div>
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4" style="margin-bottom: 30px">
                                    <h6 class="mb-3">To:</h6>
                                    <div><h5>{{ $sale->posCustomer->name }},</h5></div>
                                    <div><b>Email:</b> {{ $sale->posCustomer->email }}</div>
                                    <div><b>Phone:</b> {{ $sale->posCustomer->phone }}</div>
                                    <div><b>Address:</b> {{ $sale->posCustomer->address }}</div>
                                </div>
                            </div>

                            @if($sale->payment->count() > 0)
                                <h1 style="padding: 10px">Payment Info:</h1>
                                <hr>
                                <div class="row" style="margin-bottom: 80px">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table class="table">
                                            <thead class="thead-light">
                                            <tr>
                                                <th scope="col">SI</th>
                                                <th scope="col">Final Total</th>
                                                <th scope="col">Payment By</th>
                                                <th scope="col">Check No</th>
                                                <th scope="col">Card Name</th>
                                                <th scope="col">Curd Number</th>
                                                <th scope="col">Pay Amount</th>
                                                <th scope="col">Due Amount</th>
                                                <th scope="col">Give Back</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($sale->payment as $payment)
                                                <tr>
                                                    <td class="left">{{ $loop->index + 1 }}</td>
                                                    <td class="left">{{ $payment->final_total }}</td>
                                                    <td class="left">{{ $payment->payment_by }}</td>
                                                    <td class="left">{{ $payment->check_no?? 'NA' }}</td>
                                                    <td class="left">{{ $payment->card_name?? 'NA' }}</td>
                                                    <td class="left">{{ $payment->card_number?? 'NA' }}</td>
                                                    <td class="left">{{ $payment->pay_amount }}</td>
                                                    <td class="left">{{ $payment->due_amount }}</td>
                                                    <td class="left">{{ $payment->give_back }}</td>
                                                    <td class="left">{{ $payment->status }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            <h1 style="padding: 10px">Sale products:</h1>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table class="table">
                                        <thead class="thead-light">
                                        <tr>
                                            <th scope="col">SI</th>
                                            <th scope="col">Product</th>
                                            <th scope="col">Attribute</th>
                                            <th scope="col" width="100">Quantity</th>
                                            <th scope="col" class="text-right">Unit Price</th>
                                            <th scope="col" class="text-right">Total Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($sale->saleDetails as $item)
                                            <tr>
                                                <td class="left">{{ $loop->index + 1 }}</td>
                                                <td class="left">{{ $item->product->name }}</td>
                                                <td class="left">{{ $item->attribute->name }}
                                                    - {{ $item->attributeMap->value }}</td>
                                                <td class="left">{{ $item->quantity }}</td>
                                                <td class="text-right">{{ $item->per_price }}</td>
                                                <td class="text-right">{{ $item->total }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th colspan="5" class="text-right">Total</th>
                                            <th colspan="" class="text-right">{{ $sale->final_total }}</th>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row no-gutters">
                                <div class="col-12 col-sm-6 col-md-8"></div>
                                <div class="col-6 col-md-4">
                                    <div class="row" style="margin-top: 100px">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                            <form action="{{ route('admin.sale.payment.store', $sale->id) }}"
                                                  method="post">
                                                @csrf
                                                <table class="table table-bordered table-hover claculation_section">
                                                    <tbody>
                                                    {{--<tr>
                                                        <th class="text-center">Sub Total</th>
                                                        <td class="text-right html_subtotal">
                                                            <span style="font-size: larger; font-weight: bold">{{ $sale->sub_total }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Tax (+)</th>
                                                        <td class="text-right">
                                                            <span style="font-size: larger; font-weight: bold">{{ $sale->tax?? 0 }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Shipping Charge (+)</th>
                                                        <td class="text-right">
                                                            <span style="font-size: larger; font-weight: bold">{{ $sale->shipping_charge?? 0 }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Discount (-)</th>
                                                        <td class="text-right">
                                                            <span style="font-size: larger; font-weight: bold">{{ $sale->discount?? 0 }}</span>
                                                        </td>
                                                    </tr>--}}
                                                    <tr>
                                                        <th class="text-center">Final Total</th>
                                                        <td class="text-right">
                                                            <span
                                                                style="font-size: larger; font-weight: bold">{{ $sale->final_total }}</span>
                                                            <input type="hidden" name="final_total" class="final_total"
                                                                   value="{{ $sale->final_total }}">
                                                            <input type="hidden" name="pos_customer_id"
                                                                   value="{{ $sale->pos_customer_id }}">
                                                            <input type="hidden" class="already_pay" name="already_pay"
                                                                   value="{{$sale->payment->sum('pay_amount') }}">
                                                        </td>
                                                    </tr>

                                                    @if(isset($sale->payment))
                                                        @if($sale->payment->sum('pay_amount') < $sale->final_total)
                                                            <tr>
                                                                <th class="text-center">Payment By</th>
                                                                <td class="text-right">
                                                                    <div class="form-group">
                                                                        <select
                                                                            class="form-group form-control float-left payment_type"
                                                                            name="payment_type">
                                                                            <option value="cash">Cash</option>
                                                                            <option value="card">Card</option>
                                                                            <option value="check">Check</option>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endif

                                                    <tr class="card_pay">
                                                        <th class="text-center">Card Name</th>
                                                        <td class="text-right">
                                                            <div class="form-group">
                                                                <input type='text' name="card_name"
                                                                       class='form-control'>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr class="card_pay">
                                                        <th class="text-center">Card Number</th>
                                                        <td class="text-right">
                                                            <div class="form-group">
                                                                <input type='text' name="card_number"
                                                                       class='form-control'>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr class="check_no_pay">
                                                        <th class="text-center">Check No</th>
                                                        <td class="text-right">
                                                            <div class="form-group">
                                                                <input type='text' name="check_no" class='form-control'>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    @if(isset($sale->payment))
                                                        @if($sale->payment->sum('pay_amount') >= $sale->final_total)
                                                            <tr>
                                                                <th class="text-center">Pay Money</th>
                                                                <td class="text-right">
                                                                    <span
                                                                        style="font-size: larger; font-weight: bold">{{ $sale->payment->sum('pay_amount') }}</span>
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <th class="text-center">Pay Money</th>
                                                                <td class="text-right">
                                                                    <div class="form-group">
                                                                        <input type='text' name="pay_money"
                                                                               class='form-control pay_input_field'>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endif


                                                    @if(isset($sale->payment))
                                                        @if($sale->payment->sum('pay_amount') < $sale->final_total)
                                                            <tr>
                                                                <th class="text-center">Due Money</th>
                                                                <td class="text-right">
                                                                    <div class="form-group">
                                                                        <input type='text' readonly name="due_money"
                                                                               value="{{ $sale->final_total- $sale->payment->sum('pay_amount')}}"
                                                                               class='form-control due_money'>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endif

                                                    @if(isset($sale->payment))
                                                        @if($sale->payment->sum('pay_amount') > $sale->final_total)
                                                            <tr>
                                                                <th class="text-center">Give Back</th>
                                                                <td class="text-right">
                                                                    <span
                                                                        style="font-size: larger; font-weight: bold">{{ ($sale->payment->sum('pay_amount') - $sale->final_total) }}</span>
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr class="appendGiv">

                                                            </tr>
                                                        @endif
                                                    @endif



                                                    @if(isset($sale->payment))
                                                        @if($sale->payment->sum('pay_amount') < $sale->final_total)
                                                            <tr>
                                                                <th class="text-center"></th>
                                                                <td class="text-center">
                                                                    <button type="submit" class="btn sale_btn"
                                                                            style="background: #00c292; color: #f0f0f0">
                                                                        Sale
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endif

                                                    </tbody>
                                                </table>
                                            </form>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Search Engine End-->


@endsection



@push('script')
    <script>


        $(".payment_type").on('change', function () {
            var value = $(this).val();

            if (value == 'cash') {
                $(".card_pay").css('display', 'none');
                $(".check_no_pay").css('display', 'none');
            } else if (value == 'card') {

                $(".card_pay").show();
                $(".check_no_pay").css('display', 'none');
            } else if (value == 'check') {
                $(".check_no_pay").show();
                $(".card_pay").css('display', 'none');
            }

        });

        $(".pay_input_field").on('change keyup', function () {
            var now_pay = parseFloat($(this).val());
            var final_total = parseFloat($(".final_total").val());
            var already_pay = parseFloat($(".already_pay").val());
            var total_pay = (now_pay + already_pay);
            var due_amount = (final_total - total_pay);

            if (now_pay) {
                $(".due_money").val(due_amount)
            } else {
                $(".due_money").val(final_total - already_pay)
            }
            if (total_pay > final_total) {
                $(".appendGiv").html(`<th class="text-center">Give Back</th>
                                          <td class="text-right" >
                                                <div class="form-group">
                                                    <input type='text' readonly name="give_back" value="` + (parseFloat(total_pay) - parseFloat(final_total)) + `"  class='form-control'>
                                                </div>
                                          </td>`);
                $(".due_money").val(0)
            } else {
                $(".appendGiv").html('')
            }


            /* if (parseFloat(total_pay) > parseFloat(final_total))
             {
                 $(".appendGiv").html(`<th class="text-center">Give Back</th>
                                           <td class="text-right" >
                                                 <div class="form-group">
                                                     <input type='text' name="give_back" value="`+(parseFloat(total_pay) - parseFloat(final_total))+`"  class='form-control'>
                                                 </div>
                                           </td>`);
                 $(".due_money").val(0)
             }else{
                 $(".appendGiv").html('');
                 $(".due_money").val((parseFloat(final_total) - parseFloat(already_pay)))
             }*/
        });

        // payment model
        $(".payment_submit").on('click', function () {
            var payment_type = $(".payment_type :selected").val();
            if (payment_type == 'cash') {
                var card_name = null;
                var card_number = null;
                var check_no = null;
            } else if (payment_type == 'card') {
                var card_name = $(".card_name").val();
                var card_number = $(".card_number").val();
                var check_no = null;
            } else if (payment_type == 'check') {
                var card_name = null;
                var card_number = null;
                var check_no = $(".check_no").val();
            }
            var pos_customer_id = $(".p_pos_customer_id").val();
            var last_payment_id = $(".last_payment_id").val();
            var payment_amount = $(".pay_input_field").val();
            var total = $(".sale_total").text();

            if ((parseFloat(payment_amount) > parseFloat(total))) {
                var due = 0;
                var back = $(".back").val();
                var status = "FP";
            } else if (parseFloat(payment_amount) == parseFloat(total)) {
                var due = 0;
                var back = 0;
                var status = 'FP'
            } else {
                var due = $(".due").val();
                var back = 0;
                var status = 'PP'
            }

            if (payment_type == null) {
                toastr.error('Please select Payment Type');
                return false;
            }


            $.post("{{ route('admin.sale.payment.store.with_ajax') }}",
                {
                    last_payment_id: last_payment_id,
                    pos_customer_id: pos_customer_id,
                    card_name: card_name,
                    card_number: card_number,
                    check_no: check_no,
                    payment_amount: payment_amount,
                    payment_type: payment_type,
                    due: due,
                    back: back,
                    status: status,
                    total: total,
                },
                function (res) {
                    if (res == 'payment_success') {
                        toastr.success('Payment Success !');
                        $(".payment_type").val('');
                        $(".p_pos_customer_id").val('');
                        $(".last_payment_id").val('');
                        $(".pay_input_field").val('');
                        $(".give_back").html('');
                        $(".sale_total").text('');
                        $(".appendCardInput").hide();
                        $(".appendCheckInput").hide();
                        $(".payment_model").modal('hide');
                    }
                });
        })

    </script>
@endpush


{{--@push('script')

    <script>
        $().ready(function () {

            var total_amount = $("#total_amount").val();

            $("#pay_amount").keyup(function () {
                var pay_amt = $(this).val();
                if(parseInt(pay_amt))
                {
                    var due_amount = parseInt(total_amount) - parseInt(pay_amt);
                    if (parseInt(total_amount) < parseInt(pay_amt))
                    {
                        $(this).val(total_amount);
                        $("#due_amount").val(0);
                        $("#due_amounthidden").val(0);
                        alert('you pay so much')
                    }else{
                        $("#due_amount").val(due_amount);
                        $("#due_amounthidden").val(due_amount);
                    }
                }else{
                    $("#due_amount").val(total_amount);
                    $("#due_amounthidden").val(total_amount);
                }
            })
        })
    </script>


@endpush--}}
