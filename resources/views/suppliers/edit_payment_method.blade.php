@extends('layouts.crud-master')
@section('title', 'Supplier Edit')
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
                                method="POST" action="{{ route('admin.supplier.payment_method.update',$supplier_payment_method->id) }}"
                                accept-charset="UTF-8"
                                enctype="multipart/form-data">
                                @csrf
{{--                                @method('PUT')--}}
                                <input type="hidden" name="vendor_id" value="{{$supplier_payment_method->vendor_id}}">
                                <input type="hidden" name="supplier_id" value="{{$supplier_payment_method->supplier_id}}">
                                <input type="hidden" name="supplier_payment_method" value="{{$supplier_payment_method->id}}">
                                <div class="form-group">
                                    <label for="payment_type">Payment Type</label>
                                    <input
                                        class="form-control" id="payment_type" value="{{ $supplier_payment_method->payment_type }}" autocomplete="off"
                                        name="payment_type" type="text" readonly>
                                    @error('payment_type')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="visible_name">Visible Name</label>
                                    <input
                                        class="form-control" id="visible_name" value="{{ $supplier_payment_method->visible_name }}" autocomplete="off"
                                        name="visible_name" type="visible_name">
                                    @error('visible_name')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="form-group bank_name">
                                    <label for="bank_name">Bank Name</label>
                                    <input
                                        class="form-control" id="bank_name" value="{{ $supplier_payment_method->bank_name }}"
                                        autocomplete="off"
                                        name="bank_name" type="text">
                                    @error('bank_name')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="form-group card_name">
                                    <label for="card_name">Card Name</label>
                                    <input
                                        class="form-control" id="card_name" value="{{ $supplier_payment_method->card_name }}"
                                        autocomplete="off"
                                        name="card_name" type="text">
                                    @error('card_name')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group branch_name">
                                    <label for="branch_name">Branch Name</label>
                                    <input
                                        class="form-control" id="branch_name" value="{{ $supplier_payment_method->branch_name }}"
                                        autocomplete="off"
                                        name="branch_name" type="text">
                                    @error('branch_name')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group bank_account_name">
                                    <label for="bank_account_name">Bank Account Name</label>
                                    <input
                                        class="form-control" id="bank_account_name" value="{{ $supplier_payment_method->bank_account_name }}"
                                        autocomplete="off"
                                        name="bank_account_name" type="text">
                                    @error('bank_account_name')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group swift_code">
                                    <label for="swift_code">Swift Code</label>
                                    <input
                                        class="form-control" id="swift_code" value="{{ $supplier_payment_method->swift_code }}"
                                        autocomplete="off"
                                        name="swift_code" type="text">
                                    @error('swift_code')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group account_no">
                                    <label for="account_no">Account No.</label>
                                    <input
                                        class="form-control" id="account_no" value="{{ $supplier_payment_method->account_no }}"
                                        autocomplete="off"
                                        name="account_no" type="text">
                                    @error('account_no')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group card_number">
                                    <label for="card_number">Card No.</label>
                                    <input
                                        class="form-control" id="card_number" value="{{ $supplier_payment_method->card_number }}"
                                        autocomplete="off"
                                        name="card_number" type="text">
                                    @error('card_number')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group mobile_service_name">
                                    <label for="mobile_service_name">Mobile Service Name</label>
                                    <input
                                        class="form-control" id="mobile_service_name" value="{{ $supplier_payment_method->mobile_service_name }}"
                                        autocomplete="off"
                                        name="mobile_service_name" type="text">
                                    @error('mobile_service_name')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>


                                <div class="form-group">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input
                                                value="1" {{ $supplier_payment_method->status == 1 ? 'checked' : '' }} type="checkbox"
                                                id="addedItemCheckbox" name="status"
                                                class="i-checks">
                                            <span></span>
                                            Status
                                        </label>
                                    </div>

                                    @error('status')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <button
                                    type="submit" style="background: #00c292; color: #f0f0f0" class="btn waves-effect">
                                    Update Data
                                </button>
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
        $(document).ready( function () {
             if ($('#payment_type').val() == 'bank_transfer'){
                $('.mobile_service_name').hide()
            }else if ($('#payment_type').val() == 'mobile_banking'){
                $('.bank_name').hide()
                $('.branch_name').hide()
                $('.bank_account_name').hide()
                $('.swift_code').hide()
            }else if ($('#payment_type').val() == 'online_banking'){
                $('.mobile_service_name').hide()
            }else if ($('#payment_type').val() == 'card'){
                $('.bank_name').hide()
                $('.branch_name').hide()
                $('.bank_account_name').hide()
                $('.swift_code').hide()
                $('.account_no').hide()
                $('.mobile_service_name').hide()
                $('.card_name').show()
                $('.card_number').show()
            }else if ($('#payment_type').val() == 'cheque'){
                $('.bank_name').show()
                $('.branch_name').show()
                $('.bank_account_name').show()
                $('.swift_code').show()
                $('.account_no').show()
                $('.mobile_service_name').hide()
                $('.card_name').hide()
                $('.card_number').hide()
            }else {
                $('.bank_name').hide()
                $('.branch_name').hide()
                $('.bank_account_name').hide()
                $('.swift_code').hide()
                $('.account_no').hide()
                $('.mobile_service_name').hide()
                $('.card_name').hide()
                $('.card_number').hide()
            }
        });
    </script>
@endpush
