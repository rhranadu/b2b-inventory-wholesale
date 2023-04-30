@php
//dd($_SESSION);
 //dd($sales);
@endphp
@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Sale Order Invoice')
@push('css')
    <style>
        .modal-full {
            min-width: 60%;
            margin-left: 80;
        }

        .modal-full .modal-content {
            min-height: 80vh;
        }
        #card_invoice {
            margin: 20px;
            background-color: white;
        }
        @media print {
            #printPageButton {
                display: none;
            }
        }
    </style>
@endpush
@section('main_content')
<div class="card" id="card_invoice">
    <div class="card-body">
        <div class="mt-2" id="invoice">
            <div class="col-12">
                <div class="invoice-title row">
                    <div class="col-4">
                        <h2>Invoice</h2>
                        <button id="printPageButton" onclick="window.print()" class="btn btn-info btn-sm"><i class="fa fa-print"></i> Print</button>
                    </div>
                    <div class="col-4 text-center">
                        <img src="{!! asset($image) !!}" style="height: 60px;height: 60px;" alt="Logo">

                    </div>
                    <div class="col-4">
                        <h3 class="float-right">Order # {{ $sales['invoice_no'] }}</h3>
                    </div>

                </div>
                <hr>
                @if(!empty($user_address))
                    <div class="row">
                        <div class="col-6">
                            <address>
                                <strong>Billed To:</strong><br>
                                @if(!empty($user_address))
                                    {{ $user_address->first_name .' '.$user_address->last_name }}<br>
                                    {{ $user_address->email }}<br>
                                    {!! $user_address->address_field_1 !!}
                                @endif
                            </address>
                        </div>
                        <div class="col-6 text-right">
                            <address>
                                <strong>Shipped To:</strong><br>
                                @if(!empty($user_address))
                                    {{ $user_address->first_name .' '.$user_address->last_name }}<br>
                                    {{ $user_address->phone }}<br>
                                    {!! $user_address->address_field_1 !!}
                                @endif
                            </address>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-6">
                        <address>
                            <strong>Payment Method:</strong><br>
                            Cash On Delivery
                        </address>
                    </div>
                    <div class="col-6 text-right">
                        <address>
                            <strong>Order Date:</strong><br>
                            {{ date('d m Y',strtotime($sales['created_at']))  }}<br><br>
                        </address>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Order summary</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                <tr>
                                    <td><strong>Item</strong></td>
                                    <td class="text-center"><strong>Price</strong></td>
                                    <td class="text-center"><strong>Quantity</strong></td>
                                    <td class="text-right"><strong>Totals</strong></td>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $subTotal = 0;
                                @endphp
                                @if(!empty($saleDetails))
                                    @foreach($saleDetails as $saleDetail)
                                        @php
                                            $subTotal = $subTotal + $saleDetail['total'];
                                        @endphp
                                        <tr>
                                            <td>{{ $saleDetail->product->name }}</td>
                                            <td class="text-center">{{ $saleDetail['per_price'] }}</td>
                                            <td class="text-center">{{ $saleDetail['quantity'] }}</td>
                                            <td class="text-right">{{ $saleDetail['total'] }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="thick-line"></td>
                                        <td class="thick-line"></td>
                                        <td class="thick-line text-center"><strong>Subtotal</strong></td>
                                        <td class="thick-line text-right">{{ $subTotal }}</td>
                                    </tr>
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center"><strong>Shipping</strong></td>
                                        <td class="no-line text-right">{{ $sales['shipping_charge'] ??0 }}</td>
                                    </tr>
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center"><strong>Total</strong></td>
                                        <td class="no-line text-right">{{ $subTotal + $sales['shipping_charge'] }}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
    <style>
        #invoice .invoice-title h2, .invoice-title h3 {
            display: inline-block;
        }

        #invoice .table > tbody > tr > .no-line {
            border-top: none;
        }

        #invoice .table > thead > tr > .no-line {
            border-bottom: none;
        }

        #invoice .table > tbody > tr > .thick-line {
            border-top: 2px solid;
        }
    </style>
@endpush
