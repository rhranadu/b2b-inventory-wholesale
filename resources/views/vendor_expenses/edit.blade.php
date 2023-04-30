@extends('layouts.crud-master')
@section('title', 'Vendor Expense Edit')
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
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
            <div class="card-body">
            @include('component.message')
            <div class="normal-table-list">
                <div class="bsc-tbl">
{{--            <form action="{{$sale ?  route('admin.vendorexpense.update', $vendorexpense->id) : route('admin.vendorexpenses.update', $vendorexpense->id) }}" method="post">--}}
                    <form action="{{ route('admin.vendorexpenses.update', $vendor_expense->id) }}" method="post">
                        @csrf
                        @method('put')

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Particulars<span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                    <input class="form-control" id="particulars"
                                           value="{{ $vendor_expense->particulars }}"
                                           autocomplete="off" name="particulars" type="text">
                                    @error('particulars')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Amount<span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                    <input type='number' name="pay_amount"  value="{{ $vendor_expense->pay_amount }}"   class='form-control' min="0">
                                    @error('pay_amount')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Expense Date<span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                    <input required name="expense_date" data-date="" data-date-format="DD MMMM YYYY"
                                           type="date" class="form-control" value="{{ $vendor_expense->expense_date }}"
                                           id="expense_date">
                                    @error('expense_date')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for=""></label>
                                    <button type="submit" class="form-control btn" style="background: #00c292; color: #f0f6ff">Update</button>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>


                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Data Table area End-->




@endsection



@push('script')
    <script>


    </script>
@endpush



