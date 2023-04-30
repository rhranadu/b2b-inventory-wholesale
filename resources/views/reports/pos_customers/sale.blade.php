@extends('layouts.crud-master')

@section('title', 'POS Customers Sale Reports')

@push('css')
    <style>
        .dataTables_filter, .dataTables_length {
            padding: 20px 15px;
            padding-top: 0px;
            padding-bottom: 42px;!important;
        }
    </style>
@endpush


@section('main_content')

    <!-- Breadcomb area Start-->
    <div class="breadcomb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcomb-list">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="breadcomb-wp">
                                    <div class="breadcomb-icon">
                                        <i class="notika-icon notika-windows"></i>
                                    </div>
                                    <div class="breadcomb-ctn">
                                        <h2>POS Customer sale Reports</h2>
                                        <p>POS customer sale reports</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
                                <div class="breadcomb-report">
                                    <a href="{{ route('admin.report.poscustomers') }}" class="btn"> <i class="fa fa-list"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcomb area End-->


    <!-- Data Table area Start-->
    <div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="data-table-list">

                        <div class="table-responsive">
                            <table id="data-table-basic" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Si</th>
                                    <th>Items</th>
                                    <th>Sub_total</th>
                                    <th>Tax</th>
                                    <th>Shipping_charge</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                    <th>Pay Amount</th>
                                    <th>Due Amount</th>
                                    <th>Give Back</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($poscustomer->posCustomerSales as $pos_customer_sale)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $pos_customer_sale->items}}</td>
                                        <td>{{ number_format($pos_customer_sale->sub_total, 2) }}</td>
                                        <td>{{ $pos_customer_sale->tax}}</td>
                                        <td>{{ $pos_customer_sale->shipping_charge}}</td>
                                        <td>{{ $pos_customer_sale->discount}}</td>
                                        <td>{{ number_format($pos_customer_sale->final_total, 2) }}</td>
                                        <td>{{ $pos_customer_sale->payment->sum('pay_amount')  }}</td>
                                        <td>{{ $pos_customer_sale->payment->last()->due_amount ?? 'N/A'  }}</td>
                                        <td>{{ $pos_customer_sale->payment->last()->give_back ?? 'N/A' }}</td>
                                        <td>
                                            @if(isset($pos_customer_sale->payment->last()->status) and $pos_customer_sale->payment->last()->status == 'PP')
                                                <span style="background-color: orange" class="badge">Partial Paid</span>
                                            @elseif(isset($pos_customer_sale->payment->last()->status) and $pos_customer_sale->payment->last()->status == 'FP')
                                                <span style="background-color: green" class="badge">Full Paid</span>
                                            @else
                                                <span style="background-color: red" class="badge">Full Due</span>
                                            @endif
                                        </td>
                                        <td width="100">
                                            <a href="{{ route('admin.sale.payment', $pos_customer_sale->id) }}" class="btn btn-sm btn-info"> Payment Info </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Table area End-->

@endsection


@push('script')

@endpush
