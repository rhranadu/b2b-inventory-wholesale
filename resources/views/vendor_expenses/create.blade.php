@extends('layouts.crud-master')
@section('title', 'Vendor Expense Create')
@push('css')

    <style>
        .card_pay{
            display: none;
        }
        .check_no_pay{
            display: none;
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
                                        <h2> Create Vendor Expense</h2>
                                        <p> Create Vendor Expense</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
                                <div class="breadcomb-report">
                                    @if(($account_role && $sale) || auth()->user()->user_type_id == 2)
                                        <a href="{{ route('admin.vendorexpenses.index') }}" class="btn waves-effect" data-toggle="tooltip" data-placement="left" title="" data-original-title="Vendor Expenses List"><i class="fa fa-list"></i></a>
                                    @elseif(!$sale && $account_role)
                                        <a href="{{ route('admin.vendorexpenses.index') }}" class="btn waves-effect" data-toggle="tooltip" data-placement="left" title="" data-original-title="Vendor Expenses List"><i class="fa fa-list"></i></a>
                                    @elseif($sale && !$account_role)
                                        <a href="{{ route('admin.vendorexpense.all.index') }}" class="btn waves-effect" data-toggle="tooltip" data-placement="left" title="" data-original-title="Vendor Expenses List"><i class="fa fa-list"></i></a>
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

    <br>
    <br>
    <!-- Data Table area Start-->
    <div class="data-table-area">
        <div class="container">
            @include('component.message')
            <form action="{{  $sale ? route('admin.vendorexpense.store') : route('admin.vendorexpenses.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Total Amount</label>
                            <input type='number' name="total_amount"   class='form-control'>
                            @error('total_amount')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Pay Amount</label>
                            <input type='number' name="pay_amount"   class='form-control'>
                            @error('pay_amount')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Reason</label>
                            <textarea name="reason" class="form-control"></textarea>
                            @error('reason')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Details</label>
                            <textarea name="details" class="form-control"></textarea>
                            @error('details')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2" style="margin-top: 5px;">
                        <div class="form-group">
                            <label for=""></label>
                            <button type="submit" class="form-control btn" style="background: #00c292; color: #f0f6ff">Vendor Expense Create</button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
    <!-- Data Table area End-->




@endsection



@push('script')
    <script>

    </script>
@endpush



