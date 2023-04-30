@extends('layouts.crud-master')
@section('title', 'Supplier Bank Coount Add')
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
                                        <h2>Add Payment Method</h2>
                                        <p>add payment method</p>
                                    </div>
                                </div>
                            </div>
                            @php
                                $account_role = in_array('Account', auth()->user()->roles->pluck('name')->toArray());
                            @endphp

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
                                <div class="breadcomb-report">
                                    @if($account_role)
                                        <a href="{{ route('admin.supplier.all.index') }}" class="btn waves-effect" data-toggle="tooltip" data-placement="left" title="" data-original-title="Supplier List"><i class="fa fa-list"></i></a>
                                    @else
                                        <a href="{{ route('admin.supplier.index') }}" class="btn waves-effect" data-toggle="tooltip" data-placement="left" title="" data-original-title="Supplier List"><i class="fa fa-list"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcomb area End-->

    <div class="normal-table-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    @include('component.message')

                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form method="POST" action="{{ route('admin.supplier.pay.method.store',$supplier->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="payment_method">Payment Type</label>
                                    <select name="payment_method[]" id="payment_method" class="form-control" multiple>
                                        <option @foreach($supplier->paymentMethods as $pay_type){{ $pay_type->payment_type == 'cash' ? 'selected' : '' }}@endforeach value="cash">Cash</option>
                                        <option @foreach($supplier->paymentMethods as $pay_type){{ $pay_type->payment_type == 'card' ? 'selected' : '' }}@endforeach value="card">Card</option>
                                        <option @foreach($supplier->paymentMethods as $pay_type){{ $pay_type->payment_type == 'check' ? 'selected' : '' }}@endforeach value="check">Check</option>
                                        <option @foreach($supplier->paymentMethods as $pay_type){{ $pay_type->payment_type == 'bank' ? 'selected' : '' }}@endforeach value="bank">Bank</option>
                                    </select>
                                    @error('payment_method')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-success waves-effect">Save Data</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
