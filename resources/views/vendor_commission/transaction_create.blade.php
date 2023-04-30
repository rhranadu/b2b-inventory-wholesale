@extends('layouts.crud-master')
@section('title', 'Commission Payment')

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
                                method="POST" action="{{ route('admin.vendor_commission_transaction.store') }}" accept-charset="UTF-8"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-element-list">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <input type="hidden" value="{{auth()->user()->vendor_id}}" name="vendor_id">
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

    </script>
@endpush
