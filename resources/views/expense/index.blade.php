@extends('layouts.crud-master')
@section('title', 'Supplier Payment Method Add')
@section('main_content')
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div
            class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ isset($supplier->name) ? $supplier->name : old('supplier_name') }} | Data Table</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-muted font-weight-bold mr-4">Create a Supplier for your site</span>
                <a href="{{route('admin.supplier.create')}}" class="btn btn-light-warning font-weight-bolder btn-sm">Add
                    Supplier
                </a>
                <!--end::Actions-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <!--begin::Actions-->
                <a
                    href="{{route('admin.supplier.index')}}"
                    class="btn btn-clean btn-light-primary btn-sm font-weight-bold font-size-base mr-1">
                    <i class="fa fa-list"></i>
                    Supplier List
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
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Create Payment Method <i
                                class="mr-2"></i><small>Create Payment Method</small></h3>
                    </div>
                    <!--begin::Toolbar-->
                    <div class="d-flex align-items-center">
                        <!--begin::Actions-->
{{--                        <a--}}
{{--                            data-toggle="tooltip"--}}
{{--                            title="Payment Method List"--}}
{{--                            href="{{route('admin.supplier.payment_method.view',$supplier->id)}}"--}}
{{--                            class="btn btn-light-success btn-sm btn-clean font-weight-bold font-size-base mr-1">--}}
{{--                            <i class="fa fa-plus"></i>Payment Method List--}}
{{--                        </a>--}}
                        <a
                            data-toggle="tooltip"
                            title="Supplier List"
                            href="{{route('admin.supplier.index')}}"
                            class="btn btn-light-primary btn-sm btn-clean font-weight-bold font-size-base mr-1">
                            <i class="fa fa-list"></i>Supplier List
                        </a>
                        <!--end::Actions-->
                    </div>
                    <!--end::Toolbar-->
                </div>
                <div class="card-body">

                    @include('component.message')

                    <div class="normal-table-list">
                        <div class="bsc-tbl">

                            <hr>


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
                                            <th class="text-center">Created By</th>
                                            <th class="text-center">Updated By</th>
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
                                                <td>{{ $supplier_payment_method->createdBy->name }}</td>
                                                <td>{{ $supplier_payment_method->updatedBy->name }}</td>
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
                                                    <select name="payment_type" id="payment_type" class="selectpicker form-control" data-live-search="true">
                                                        <option value="">Select Payment Type</option>
                                                        <option value="cash" >{{ 'Cash' }}</option>
                                                        <option value="bank_transfer" >{{ 'Bank Transfer' }}</option>
                                                        <option value="cheque" >{{ 'Cheque' }}</option>
                                                        <option value="online_banking" >{{ 'Online Banking' }}</option>
                                                        <option value="mobile_banking" >{{ 'Mobile Banking' }}</option>
                                                    </select><br/>
                                                    @error('payment_type')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label for="bank_name">Bank Name<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control" id="bank_name"
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
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label for="branch_name">Branch Name<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control" id="branch_name"
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
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label for="bank_account_name">Bank Account Name<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control" id="bank_account_name"
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

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-mail"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label for="account_no">Account Number<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control" id="account_no"
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
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-map"></i>
                                                </div>
                                                <div class=" ic-cmp-int">
                                                    <label for="swift_code">Swift Code<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control" id="swift_code"
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
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label for="mobile_service_name">Mobile Service Name<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control" id="mobile_service_name"
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

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-mail"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label for="visible_name">Visible Name<span style="color: red; font-size: 10px;"><sup>*</sup></span></label>
                                                    <input class="form-control" id="visible_name"
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
                                            <div class="form-group">
                                                <div class="checkbox-inline">
                                                    <label class="checkbox checkbox-outline checkbox-success">
                                                        <input value="1" type="checkbox" id="addedItemCheckbox" name="status"
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
        $().ready(function () {

            $(".alert").delay(5000).slideUp(300);


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
        };
    </script>
@endpush
