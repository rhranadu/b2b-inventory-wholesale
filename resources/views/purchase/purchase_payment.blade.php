@extends('layouts.crud-master')
@push('css')
@endpush

@section('main_content')
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div
            class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Purchase Order Payment</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-muted font-weight-bold mr-4">Purchase Order Payment</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <!--begin::Actions-->
                <a
                    data-toggle="tooltip"
                    title="Add Purchase Order"
                    href="{{route('admin.purchase.create')}}"
                    class="btn btn-light-success btn-sm btn-clean font-weight-bold font-size-base mr-1">
                    <i class="fa fa-plus"></i>Add Purchase Order
                </a>
                <a
                    data-toggle="tooltip"
                    title="Purchase Order List"
                    href="{{route('admin.purchase.index')}}"
                    class="btn btn-light-primary btn-sm btn-clean font-weight-bold font-size-base mr-1">
                    <i class="fa fa-list"></i>Purchase Order List
                </a>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar-->
        </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    @include('component.message')
                    <div id="ui-view" data-select2-id="ui-view">
                        <div>
                            <div class="card">
                                <div class="card-header">Invoice
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>#{{ $purchase->invoice_no }}</strong>
                                            <div>Date: {{ $purchase->date }}</div>
                                            <div>
                                                {{-- <a class="btn btn-sm btn-secondary float-right mr-1 d-print-none" href="#" onclick="javascript:window.print();" data-abc="true">
                                                     <i class="fa fa-print"></i> Print</a>
                                                 <a class="btn btn-sm btn-info float-right mr-1 d-print-none" href="#" data-abc="true">
                                                     <i class="fa fa-save"></i> Save</a>--}}
                                            </div>
                                        </div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4 float-right text-center">
                                            @if(isset($status))
                                                @if($status == 1)
                                                    <span class="btn btn-sm btn-success">Paid</span>
                                                @else
                                                    <span class="btn btn-sm btn-warning">Not Full Paid</span>
                                                @endif
                                            @else
                                                <span class="btn btn-sm btn-danger">Full Due</span>
                                            @endif
                                        </div>
                                    </div>
            
            
                                </div>
                                <div class="card-body">
                                    <div class="row" style="padding: 20px;">
                                        <div class="col-sm-4">
                                            <h6 class="mb-3">From:</h6>
                                            <div>
                                                <h5>{{ $purchase->purchaseVendor->name }},</h5>
                                            </div>
                                            <div><b>Email:</b> {{ $purchase->purchaseVendor->email }}</div>
                                            <div><b>Address:</b> {{ $purchase->purchaseVendor->address }}</div>
                                        </div>
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4">
                                            <h6 class="mb-3">To:</h6>
                                            <div><h5>{{ $purchase->purchaseSupplier->name }},</h5></div>
                                            <div><b>Email:</b> {{ $purchase->purchaseSupplier->email }}</div>
                                            <div><b>Phone:</b> {{ $purchase->purchaseSupplier->mobile }}</div>
                                            <div><b>Address:</b> {{ $purchase->purchaseSupplier->details }}</div>
                                        </div>
                                    </div>
            
            
                                    @if($purchase_details->count() > 0)
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h2 style="padding: 10px">Payment Transction</h2>
                                                <div class="table-responsive-sm">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>SI</th>
                                                            <th>Supplier Type</th>
                                                            <th>Payment Type</th>
                                                            <th>Payment By</th>
                                                            <th>Card Name</th>
                                                            <th>Card Number</th>
                                                            <th>Total Amount</th>
                                                            <th>Pay Amount</th>
                                                            <th>Due Amount</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php
                                                            $total_pay_amount = 0;
                                                        @endphp
                                                        @foreach($purchase_details as $detail)
                                                            <tr>
                                                                <td class="left">{{ $loop->index + 1 }}</td>
                                                                <td class="left">{{ $detail->vendor_type }}</td>
                                                                <td class="left">{{ $detail->payment_type }}</td>
                                                                <td class="left">{{ $detail->payment_by }}</td>
                                                                <td class="left">{{ $detail->card_name ?? 'N/A' }}</td>
                                                                <td class="left">{{ $detail->card_number ?? 'N/A' }}</td>
                                                                <td class="left">{{ $detail->total_amount }}</td>
                                                                <td class="left">{{ $detail->pay_amount }}</td>
                                                                <td class="left">{{ $detail->due_amount }}</td>
                                                            </tr>
                                                            @php
                                                                $total_pay_amount += $detail->pay_amount;
                                                            @endphp
                                                        @endforeach
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <th>Total Pay Amount</th>
                                                            <th class="left">{{ $total_pay_amount }}</th>
                                                        </tr>
            
            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
            
            
                                    <h2 style="padding: 10px">Purchase Products</h2>
                                    <div class="table-responsive-sm">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>SI</th>
                                                <th width="200px">Product Name</th>
                                                <th>Product Attribute</th>
                                                <th>Attribute Map</th>
                                                <th>Warehouse</th>
                                                <th class="center">Quantity</th>
                                                <th class="center">Unit Price</th>
                                                <th class="center">Min Price</th>
                                                <th class="center">Max Price</th>
                                                <th class="center">Bar Code</th>
                                                <th class="center">Total Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $sub_total = 0;
                                            @endphp
                                            @foreach($purchase->purchaseDetail as $item)
                                                <tr>
                                                    <td class="left">{{ $loop->index + 1 }}</td>
                                                    <td class="left">{{ $item->product->name }}</td>
                                                    <td class="center">{{ $item->productAttr->name }}</td>
                                                    <td class="center">{{ $item->productAttrMap->value }}</td>
                                                    <td class="center">{{ $item->productWarehouse->name }}</td>
                                                    <td class="center">{{ $item->quantity }}</td>
                                                    <td class="center">{{ $item->purchase_price }}</td>
                                                    <td class="center">{{ $item->min_price }}</td>
                                                    <td class="center">{{ $item->max_price }}</td>
                                                    <td class="center">
                                                        @foreach($item->barCode as $bar_code)
                                                            <span class="badge">{{ $bar_code->bar_code }}</span>
                                                        @endforeach
                                                    </td>
                                                    <td class="center">{{ $item->total }}</td>
                                                </tr>
                                                @php
                                                    $sub_total += $item->total;
                                                @endphp
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
            
                                    <div class="row no-gutters">
                                        <div class="col-12 col-sm-6 col-md-8">
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <form action="{{ route('admin.purchase.payment.store', $purchase->id) }}"
                                                  method="post">
                                                @csrf
                                                <input type="hidden" name="supplier_id"
                                                       value="{{  $purchase->purchaseSupplier->id }}">
                                                <input type="hidden" name="purchase_id" value="{{  $purchase->id }}">
                                                <input type="hidden" class="form-control" name="total_amount"
                                                       value="{{ $total }}">
                                                <table class="table table-clear">
                                                    <tbody>
                                                    <tr>
                                                        <td class="left">
                                                            <strong>Subtotal</strong>
                                                        </td>
                                                        <td class="right">
                                                            <input type="text" disabled readonly class="form-control"
                                                                   value="{{ $sub_total }}">
                                                            @error('total_amount')<p
                                                                class="text-danger">{{ $message }}</p>@enderror
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="left">
                                                            <strong>Tax (%) </strong>
                                                        </td>
                                                        <td class="right">
                                                            <input type="text" disabled readonly class="form-control"
                                                                   value="{{ $purchase->tax }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="left">
                                                            <strong>Tax Amount (+) </strong>
                                                        </td>
                                                        <td class="right">
                                                            <input type="text" disabled readonly class="form-control"
                                                                   value="{{ $purchase->tax_amount }}">
                                                        </td>
                                                    </tr>
            
                                                    <tr>
                                                        <td class="left">
                                                            <strong>Shipping Charge (+)</strong>
                                                        </td>
                                                        <td class="right">
                                                            <input type="text" disabled readonly class="form-control"
                                                                   value="{{ $purchase->shipping_charge }}">
                                                        </td>
                                                    </tr>
            
                                                    <tr>
                                                        <td class="left">
                                                            <strong>Discount (-)</strong>
                                                        </td>
                                                        <td class="right">
                                                            <input type="text" disabled readonly class="form-control"
                                                                   value="{{ $purchase->discount }}">
                                                        </td>
                                                    </tr>
            
                                                    <tr>
                                                        <td class="left">
                                                            <strong>Total Amount </strong>
                                                        </td>
                                                        <td class="right">
                                                            <input type="text" disabled readonly class="form-control"
                                                                   name="total_amount" id="total_amount" value="{{ $total }}">
                                                        </td>
                                                    </tr>
            
            
                                                    <tr>
                                                        <td class="left">
                                                            <strong>Payment By</strong>
                                                        </td>
                                                        <td class="right">
                                                            <select name="payment_by" id="payment_by" class="form-control">
                                                                <option value="Hand">Hand Cash</option>
                                                                <option value="Bank">Bank</option>
                                                                <option value="Card">Card</option>
                                                            </select>
            
                                                            <div class="cardDiv">
            
                                                            </div>
                                                            @error('payment_by')<p
                                                                class="text-danger">{{ $message }}</p>@enderror
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="left">
                                                            <strong>Till Now Total Pay</strong>
                                                        </td>
                                                        <td class="right">
                                                            <input type="text" readonly disabled class="form-control"
                                                                   value="{{ $total_pay_amount ?? 0 }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="left">
                                                            <strong>Pay Amount</strong>
                                                        </td>
                                                        <td class="right">
                                                            <input type="text" id="pay_amount" class="form-control"
                                                                   value="{{ old('pay_amount') }}" name="pay_amount">
                                                            @error('pay_amount')<p
                                                                class="text-danger">{{ $message }}</p>@enderror
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="left">
                                                            <strong>Due Amount</strong>
                                                        </td>
                                                        <td class="right">
                                                            <input type="hidden" class="form-control" id="due_amounthidden"
                                                                   value="{{ isset($total_pay_amount) ? ($total-$total_pay_amount) : $total }}"
                                                                   name="due_amount">
                                                            <input type="text" disabled readonly id="due_amount"
                                                                   class="form-control"
                                                                   value="{{ isset($total_pay_amount) ? ($total-$total_pay_amount) : $total }}">
                                                            @error('due_amount')<p
                                                                class="text-danger">{{ $message }}</p>@enderror
                                                        </td>
                                                    </tr>
            
                                                    <tr>
                                                        <td class="left">
                                                            <strong></strong>
                                                        </td>
                                                        <td class="right">
                                                            <button
                                                                {{ ($status == 1) ? 'disabled' : '' }} class="btn btn-success float-right"
                                                                style="margin-bottom: 18px">
                                                                <i class="fa fa-usd"></i>Pay
                                                            </button>
                                                        </td>
                                                    </tr>
            
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
        <!--end::Container-->
    </div>
    <!--end::Entry-->
    

    <!-- Search Engine End-->


@endsection

@push('script')

    <script>
        $().ready(function () {
            // if select card or bank then it will run
            $("#payment_by").change(function () {
                var value = $(this).val();
                var txt = "<input type='text' placeholder='Card Name' name='card_name' class='form-control'>"
                    + "<input type='text' placeholder='Card Number' name='card_number' class='form-control'>";
                if (value == 'Card') {
                    $(".cardDiv").html(txt)
                } else if (value == 'Bank') {
                    $(".cardDiv").html('');
                    var supplier_id = "{{ $purchase->purchaseSupplier->id }}";
                    // get vendor bank account true or false
                    $.get("{{ route('admin.get.supplier.bank.account') }}", {supplier_id: supplier_id}, function (feedBackResult) {
                        if (feedBackResult === 'false') {
                            alert('No Bank account in this supplier.. Go To Add Bank Account');
                            var url = '{{ route("admin.supplier.bank", ":id") }}';
                            url = url.replace(':id', supplier_id);
                            window.location.href = url;
                        }
                    });
                } else {
                    $(".cardDiv").html('')
                }
            });


            var total_amount = parseFloat($("#total_amount").val(), 2);
            var payAmount = parseFloat("{{ $total_pay_amount ?? 0 }}");
            var willDue = (total_amount - payAmount);
            $("#pay_amount").keyup(function () {
                var pay_amt = parseFloat($(this).val());
                if (parseFloat(pay_amt)) {
                    var totalPaid = (pay_amt + payAmount);
                    var due_amount = total_amount - totalPaid;
                    if (total_amount < totalPaid) {
                        $(this).val(willDue);
                        $("#due_amount").val(0);
                        $("#due_amounthidden").val(0);
                        alert('you pay so much')
                    } else {
                        $("#due_amount").val(due_amount);
                        $("#due_amounthidden").val(due_amount);
                    }
                } else {
                    $("#due_amount").val(willDue);
                    $("#due_amounthidden").val(willDue);
                }
            })
        })
    </script>


@endpush
