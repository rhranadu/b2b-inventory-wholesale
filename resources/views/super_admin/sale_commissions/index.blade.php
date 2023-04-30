@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Sale Commission')
@push('css')
    <style>
        .table th, .table td{vertical-align:inherit;}
    </style>
@endpush
@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom mb-2" id="kt_card_1">
                <div class="card-body">
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form method="POST" id="globalForm" action="{{ route('superadmin.commission.global.ajax') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <label  class="col-3 col-form-label">Global Sale Commission (%)</label>
                                    <div class="col-2">
                                        <div class="input-group">
                                            <input class="form-control" id="global" type="number" value="{{ $global->value ?: 0 }}"/>
                                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <button type="button" data-id="{{ $global->id }}" id="globalFormSubmit" class="btn btn-success">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="vendorStorePlaceholder">
                <div class="card card-custom mb-2" id="kt_card_1">
                    <div class="card-body">
                        <div class="normal-table-list">
                            <div class="bsc-tbl">
                                <form method="POST" id="vendorForm" action="{{ route('superadmin.commission.vendor.store') }}"
                                    accept-charset="UTF-8" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" id="vendorStoreId" value="0" />
                                    <div class="row align-items-center">
                                        <div class="form-group col-md-3">
                                            <label for="#">Vendor</label>
                                            <select name="vendor_id" id="storeVendor" class="form-control"
                                                data-live-search="true">
                                                @foreach ($vendors as $k=>$v)
                                                <option value={{ $k }}>{{ $v }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="#">Commission (%)</label>
                                            <div class="input-group">
                                                <input type="number" name="commission_percentage" class="form-control" id="commissionPercentage"/>
                                                <div class="input-group-append"><span class="input-group-text">%</span></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <div class="checkbox-inline pt-7">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="status" value=1 id="vendorStoreStatus" />
                                                    <span></span>
                                                    Status
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <button class="btn btn-success mt-7" id="vendorFormSubmit" type="button">Add</button>
                                            <button class="btn btn-danger mt-7" id="vendorFormReset" type="reset">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            @include('component.message')
                            <div class="row align-items-center">
                                <div class="form-group col-md-3">
                                    <label for="#">Vendor</label>
                                    <select name="vendor" id="vendor" class="form-control vendor" data-live-search="true">
                                        @foreach ($vendors as $k=>$v)
                                            <option value={{ $k }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-1">
                                    <button type="submit" class="btn btn-primary mt-7" id="search">Search</button>
                                </div>
                                <div class="form-group col-md-1">
                                    <button class="btn btn-danger mt-7" id="reset_btn" type="reset">Reset</button>
                                </div>

                            </div>
                            </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-condensed commonDatatable"
                                            id="commonDatatable">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Vendor</th>
                                            <th class="text-center">Commission (%)</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center notexport">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

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

@push('script')
    <script>

        $("#vendor, #storeVendor").select2({
            width: '100%',
            allowClear: true,
            placeholder: 'Search for a vendor',
        });

        $(".alert").delay(5000).slideUp(300);
        $.ajaxSetup({
            headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
        });
        $(document).off('click', '#globalFormSubmit').on('click', '#globalFormSubmit', function() {
            $.ajax({
                url: "{{ route('superadmin.commission.global.ajax') }}",
                type: "post",
                data: {commission: $("#global").val(), id: $(this).data('id')},
                success: function (res) {
                    if (res === 'true') {
                        toastr.success('Global sale commission updated');
                    } else {
                        toastr.error('Failed to update');
                    }
                }
            })
        })
        $(document).off('click', '#vendorFormSubmit').on('click', '#vendorFormSubmit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('superadmin.commission.vendor.store') }}",
                type: "post",
                data: {
                    commission_percentage: $("#commissionPercentage").val(),
                    vendor_id: $("#storeVendor").val(),
                    status: $("#vendorStoreStatus").is(":checked") ? 1 : 0,
                    id: $("#vendorStoreId").val()
                },
                success: function (res) {
                    if (res.success === 'true') {
                        var commonDatatable = $('#commonDatatable').dataTable();
                        commonDatatable.fnDraw(false);
                        toastr.success('Vendor sale commission stored');
                    } else {
                        toastr.error(res.msg);
                    }
                }
            })
        })
        function renderVendorStore(id) {
            let url = "{{ route('superadmin.commission.vendor.render') }}";
            AJAX_HELPER.ajaxSelectedMethodSubmitDataCallback('POST', url, {id: id}, 'html', function (response) {
                $("#vendorStorePlaceholder").html(response);
                $("#storeVendor").select2({
                    width: '100%',
                    allowClear: true,
                    placeholder: 'Search for a vendor',
                });
            })
        }
        var commonDatatable =   $('#commonDatatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('superadmin.commission.ajax')}}',
                type: "POST",
                data: function (d) {
                    d.vendor_id = $('#vendor :selected').val();
                },
            },
            dom:'Blfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, -1 ],
                [ '10', '25', '50', '100', 'All' ]
            ],
            buttons: [
                {
                    extend: 'excel',
                    className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                    text: 'Excel',
                    exportOptions: {
                        columns: ':not(.notexport)'
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                    text: 'Pdf',
                    download: 'open',
                    exportOptions: {
                        columns: ':not(.notexport)'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                    exportOptions: {
                        columns: ':not(.notexport)'
                    }
                }
            ],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'vendor', name: 'vendor'},
                {data: 'commission_percentage', name: 'commission'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'},
            ],
            columnDefs: [{
                targets: '_all',
                defaultContent: 'N/A'
            }],
        });
        $('#search').on('click', function(e) {
            commonDatatable.draw();
            e.preventDefault();
        });
        $(document).off('click', '#reset_btn').on('click', '#reset_btn', function() {
            $("select[name=vendor]").val("").trigger("change");
        });




        // delete
        function destroyData(id) {
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
                    toastr.success('Commission deleted');
                    event.preventDefault();
                    document.getElementById('destroyDataForm-' + id).submit();
                }
            })
        }


    </script>
@endpush
