@extends('layouts.crud-master')
@section('title', 'Supplier Payment Method Add')
@section('main_content')
@push('css')
@endpush
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">

                <div class="card-body">

                    @include('component.message')

                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            @if (isset($supplier_payment_methods) && count($supplier_payment_methods) > 0)

                                <div class="table-responsive" id="refreshbody">
                                    <table class="table table-hover table-bordered table-condensed paymentMethodTable"
                                           id="data-table-basic">
                                        <thead>
                                        <tr>
                                            <th class="text-center">SI</th>
                                            <th class="text-center">Visible Name</th>
                                            <th class="text-center">Bank Name</th>
                                            <th class="text-center">Account No.</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($supplier_payment_methods as $supplier_payment_method)
                                            <tr class="text-center">
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $supplier_payment_method->visible_name }}</td>
                                                <td>{{ $supplier_payment_method->bank_name }}</td>
                                                <td>{{ $supplier_payment_method->account_no }}</td>
                                                <td id="status">
                                            <span href="#0" id="ActiveUnactive"
                                                  statusCode="{{ $supplier_payment_method->status }}"
                                                  data_id="{{ $supplier_payment_method->id }}"
                                                  class="badge cursor-pointer {{ $supplier_payment_method->status == 1 ? 'badge-success' : 'badge-danger' }}">
                                               {{ $supplier_payment_method->status == 1 ? 'Active' : 'Deactive'  }}
                                            </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.supplier.payment_method.edit', $supplier_payment_method->id) }}"
                                                           class="btn btn-sm btn-warning waves-effect btn-icon"
                                                           data-toggle="tooltip"
                                                           data-placement="auto" title="" data-original-title="EDIT"><i
                                                                class="fas fa-pencil-alt"></i> </a>

{{--                                                        <a href="{{ route('admin.supplier.payment_method.destroy', $supplier_payment_method->id) }}"--}}
{{--                                                           class="btn btn-sm btn-danger waves-effect btn-icon"--}}
{{--                                                           onclick="deletePaymentMethod({{ $supplier_payment_method->id }})"--}}
{{--                                                           data-toggle="tooltip" data-placement="auto" title="" data-original-title="DELETE">--}}
{{--                                                            <i class="fa fa-trash"></i> </a>--}}
{{--                                                        <button type="submit" id="payment_method_delete"--}}
{{--                                                                class="btn btn-sm btn-danger waves-effect btn-icon"--}}
{{--                                                                data-toggle="tooltip" data-placement="auto" title=""--}}
{{--                                                                data-id="{{ $supplier_payment_method->id }}"--}}
{{--                                                                data-original-title="DELETE"><i class="fa fa-trash"></i>--}}
{{--                                                        </button>--}}
                                                        <a
                                                            href="#0" class="btn btn-sm btn-danger btn-icon"
                                                            title="Delete" data-toggle="tooltip" data-placement="auto"
                                                            onclick="deletePaymentMethod({{ $supplier_payment_method->id }})"
                                                            data-original-title="DELETE">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                    <form
                                                        style="display: none" method="POST"
                                                        id="deletePaymentMethodForm-{{ $supplier_payment_method->id }}"
                                                        action="{{ route('admin.supplier.payment_method.destroy', $supplier_payment_method->id) }}">
                                                        @csrf
{{--                                                        @method('DELETE')--}}
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {!! $supplier_payment_methods->links() !!}

                            @else

                                <div class="alert alert-custom alert-outline-2x alert-outline-warning fade show mb-5"
                                     role="alert" style="position: relative">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text h4 mb-0">No payment method record found</div>
                                </div>
                            @endif
                            <hr>


                            <form method="POST" action="{{ route('admin.supplier.payment_method.store',$supplier->id) }}"
                                  accept-charset="UTF-8" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="account_for" value="{{ $supplier->type }}">
                                <input type="hidden" name="account_owner_id" value="{{ $supplier->id }}">
                                <div class="form-element-list">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-map"></i>
                                                </div>
                                                <div class="bootstrap-select ic-cmp-int">
                                                    <label for="payment_type">Payment Type
                                                        <span style="color: red; font-size: 10px;"><sup>*</sup></span>
                                                    </label>
                                                    <select name="payment_type" id="payment_type" class="selectpicker form-control"
                                                            data-live-search="true">
                                                        <option {{ old('payment_type') == "" ? "selected" : "" }} value="">Select Payment Type</option>
                                                        <option {{ old('payment_type') == "cash" ? "selected" : "" }} value="cash" >{{ 'Cash' }}</option>
                                                        <option {{ old('payment_type') == "bank_transfer" ? "selected" : "" }} value="bank_transfer" >{{ 'Bank Transfer' }}</option>
                                                        <option {{ old('payment_type') == "cheque" ? "selected" : "" }} value="cheque" >{{ 'Cheque' }}</option>
                                                        <option {{ old('payment_type') == "card" ? "selected" : "" }} value="card" >{{ 'Card' }}</option>
                                                        <option {{ old('payment_type') == "online_banking" ? "selected" : "" }} value="online_banking" >{{ 'Online Banking' }}</option>
                                                        <option {{ old('payment_type') == "mobile_banking" ? "selected" : "" }} value="mobile_banking" >{{ 'Mobile Banking' }}</option>
                                                    </select><br/>
                                                    @error('payment_type')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="bank_name">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st" >
                                                    <label for="bank_name">Bank Name<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control"
                                                           value="{{ isset($supplier->paymentMethods->bank_name) ? $supplier->paymentMethods->bank_name : old('bank_name') }}"
                                                           autocomplete="off" name="bank_name" type="text">
                                                    @error('bank_name')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="branch_name">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st" >
                                                    <label for="branch_name">Branch Name<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control"
                                                           value="{{ isset($supplier->paymentMethods->branch_name) ? $supplier->paymentMethods->branch_name : old('branch_name') }}"
                                                           autocomplete="off" name="branch_name" type="text">
                                                    @error('branch_name')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="bank_account_name">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st" >
                                                    <label for="bank_account_name">Bank Account Name<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control"
                                                           value="{{ isset($supplier->paymentMethods->bank_account_name) ? $supplier->paymentMethods->bank_account_name : old('bank_account_name') }}"
                                                           autocomplete="off" name="bank_account_name" type="text">
                                                    @error('bank_account_name')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="card_name">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st" >
                                                    <label for="card_name">Card Name<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control"
                                                           value="{{ isset($supplier->paymentMethods->card_name) ? $supplier->paymentMethods->card_name : old('card_name') }}"
                                                           autocomplete="off" name="card_name" type="text">
                                                    @error('card_name')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="account_no">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-mail"></i>
                                                </div>
                                                <div class="nk-int-st" >
                                                    <label for="account_no">Account Number<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control"
                                                           value="{{ isset($supplier->paymentMethods->account_no) ? $supplier->paymentMethods->account_no : old('account_no') }}"
                                                           autocomplete="off" name="account_no" type="text">
                                                    @error('account_no')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="card_number">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-mail"></i>
                                                </div>
                                                <div class="nk-int-st" >
                                                    <label for="card_number">Card Number<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control"
                                                           value="{{ isset($supplier->paymentMethods->card_number) ? $supplier->paymentMethods->card_number : old('card_number') }}"
                                                           autocomplete="off" name="card_number" type="text">
                                                    @error('card_number')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="swift_code">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-map"></i>
                                                </div>
                                                <div class=" ic-cmp-int" >
                                                    <label for="swift_code">Swift Code<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control"
                                                           value="{{ isset($supplier->paymentMethods->swift_code) ? $supplier->paymentMethods->swift_code : old('swift_code') }}"
                                                           autocomplete="off" name="swift_code" type="text">
                                                    @error('swift_code')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="mobile_service_name">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st" >
                                                    <label for="mobile_service_name">Mobile Service Name<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control"
                                                           value="{{ isset($supplier->paymentMethods->mobile_service_name) ? $supplier->paymentMethods->mobile_service_name : old('mobile_service_name') }}"
                                                           autocomplete="off" name="mobile_service_name" type="text">
                                                    @error('mobile_service_name')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="visible_name">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-mail"></i>
                                                </div>
                                                <div class="nk-int-st"  >
                                                    <label for="visible_name">Visible Name<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control"
                                                           value="{{ isset($supplier->paymentMethods->visible_name) ? $supplier->paymentMethods->visible_name : old('visible_name') }}"
                                                           autocomplete="off" name="visible_name" type="text">
                                                    @error('visible_name')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group" id="addedItemCheckbox">
                                                <div class="checkbox-inline">
                                                    <label class="checkbox checkbox-outline checkbox-success">
                                                        <input value="1" type="checkbox" name="status"
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
        $(document).ready(function () {

            $(".alert").delay(5000).slideUp(300);
            if (($('#payment_type option:selected').text() == "Bank Transfer") || ($('#payment_type option:selected').text() == "Cheque") || ($('#payment_type option:selected').text() == "Online Banking") ){
                $('#bank_name').show();
                $('#branch_name').show();
                $('#bank_account_name').show();
                $('#account_no').show();
                $('#swift_code').show();
                $('#addedItemCheckbox').show();
                $('#mobile_service_name').hide();
                $('#visible_name').show();
            }else if (($('#payment_type option:selected').text() == "Cash")){
                $('#bank_name').hide();
                $('#branch_name').hide();
                $('#bank_account_name').hide();
                $('#account_no').hide();
                $('#swift_code').hide();
                $('#addedItemCheckbox').show();
                $('#mobile_service_name').hide();
                $('#visible_name').show();
            }else if (($('#payment_type option:selected').text() == "Mobile Banking")){
                $('#bank_name').hide();
                $('#branch_name').hide();
                $('#bank_account_name').hide();
                $('#account_no').show();
                $('#swift_code').hide();
                $('#addedItemCheckbox').hide();
                $('#mobile_service_name').show();
                $('#visible_name').show();
            }else if (($('#payment_type option:selected').text() == "Card")){
                $('#bank_name').hide();
                $('#branch_name').hide();
                $('#bank_account_name').hide();
                $('#account_no').hide();
                $('#swift_code').hide();
                $('#addedItemCheckbox').show();
                $('#mobile_service_name').hide();
                $('#visible_name').show();
                $('#card_name').show();
                $('#card_number').show();
            }else {
                $('#bank_name').hide();
                $('#branch_name').hide();
                $('#bank_account_name').hide();
                $('#account_no').hide();
                $('#swift_code').hide();
                $('#mobile_service_name').hide();
                $('#visible_name').hide();
                $('#addedItemCheckbox').hide();
                $('#card_name').hide();
                $('#card_number').hide();
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("table").on('click', '#ActiveUnactive', function () {
                var id = $(this).attr('data_id');
                var getStatus = $(this).attr('statusCode');
                var setStatus = (getStatus > 0) ? 0 : 1;
                $.ajax({
                    url: "{{ route('admin.supplier.payment_method.statusActiveUnactive') }}",
                    type: "get",
                    data: {setStatus: setStatus, id: id},
                    success: function (res) {
                        if (res === 'true') {
                            $(".paymentMethodTable").load(location.href + " .paymentMethodTable");
                            toastr.success('Payment Method status updated success');
                        } else {
                            toastr.success('Not found !');
                        }
                    }
                })
            })
        });


        {{--$('body').on('click', '#payment_method_delete', function () {--}}
        {{--    var val_id = $(this).data('id');--}}
        {{--    if(val_id != ''){--}}
        {{--        var request = $.ajax({--}}
        {{--            type: 'post',--}}
        {{--            url:  '{{url('admin/payment_method/destroy')}}',--}}
        {{--            data: {--}}
        {{--                'val_id': val_id,--}}
        {{--            },--}}
        {{--            dataType: "json"--}}
        {{--        });--}}
        {{--        request.done(function (response) {--}}
        {{--            if (response.success == true){--}}
        {{--                $("#refreshbody").load(location.href + " #refreshbody>*", "");--}}
        {{--                toastr.success('Payment Method deleted success');--}}
        {{--                location.reload();--}}
        {{--            }--}}
        {{--        });--}}
        {{--    }--}}
        {{--});--}}

        function deletePaymentMethod(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButtonColor: '#00c292',
                    cancelButton: 'btn btn-danger mt-0'
                },
                buttonsStyling: true
            });
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this! ⚠️",
                type: 'warning',
                cancelButtonColor: "#AF0000",
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    // toastr.success('Warehouse deleted success');
                    event.preventDefault();
                    document.getElementById('deletePaymentMethodForm-' + id).submit();
                }
            })
        }
         // mobile_service_name visible_name
        $("#payment_type").change(function () {
            var payment_type = $('#payment_type option:selected').text();
            if ((payment_type == "Bank Transfer") || (payment_type == "Cheque") || (payment_type == "Online Banking") ){
                $('#bank_name').show();
                $('#branch_name').show();
                $('#bank_account_name').show();
                $('#account_no').show();
                $('#swift_code').show();
                $('#addedItemCheckbox').show();
                $('#mobile_service_name').hide();
                $('#visible_name').show();
            }else if ((payment_type == "Cash")){
                $('#bank_name').hide();
                $('#branch_name').hide();
                $('#bank_account_name').hide();
                $('#account_no').hide();
                $('#swift_code').hide();
                $('#addedItemCheckbox').show();
                $('#mobile_service_name').hide();
                $('#visible_name').show();
            }else if (($('#payment_type option:selected').text() == "Card")) {
                $('#bank_name').hide();
                $('#branch_name').hide();
                $('#bank_account_name').hide();
                $('#account_no').hide();
                $('#swift_code').hide();
                $('#addedItemCheckbox').show();
                $('#mobile_service_name').hide();
                $('#card_name').show();
                $('#card_number').show();
                $('#visible_name').show();
            }else {
                $('#bank_name').hide();
                $('#branch_name').hide();
                $('#bank_account_name').hide();
                $('#account_no').show();
                $('#swift_code').hide();
                $('#addedItemCheckbox').show();
                $('#mobile_service_name').show();
                $('#visible_name').show();

            }

        });
    </script>
@endpush
