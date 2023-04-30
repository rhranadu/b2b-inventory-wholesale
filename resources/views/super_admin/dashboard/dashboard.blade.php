@extends('layouts.app')
@include('component.dataTable_resource')
@section('title', 'Super Admin Dashboard')

@push('css')

@endpush



@section('main_content')


    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Dashboard-->
            <!--begin::Row-->
            <div class="row m-0">
                <div class="col-xl-3">
                    <div class="card card-custom bg-primary gutter-b">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <span><i class="fas fa-3x text-white">৳</i></span>
                                <div class="text-inverse-primary font-weight-bolder font-size-h2 mt-3">{{isset($total_sa_commission) ? number_format($total_sa_commission,2) : 'N/A' }}</div>
                            </div>
                            <a href="#" class="text-inverse-primary font-weight-bold font-size-lg mt-4 d-block">Total Sales Commission</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card card-custom bg-primary gutter-b">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <span><i class="fas fa-file-invoice fa-3x text-white" aria-hidden="true"></i></span>
                                <div class="text-inverse-primary font-weight-bolder font-size-h2 mt-3">{{isset($total_vendor) ? $total_vendor : 'N/A' }}</div>
                            </div>
                            <a href="#" class="text-inverse-primary font-weight-bold font-size-lg mt-4 d-block">Total Vendors</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->
            <div class="mt-25" id="vendor_commission_panel" style="display: block">
                <div class=" mt-15">
                    <!--begin::Stats-->
                    <div class="card-spacer mt-n25 card">
                        <!--begin::Row-->
                        <div class="card-header border-0 py-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Vendor Commission Report</span>
{{--                                <span class="text-muted mt-3 font-weight-bold font-size-sm"><a href="{{route('admin.report.sales')}}" target="_blank" class="btn btn-info font-weight-bolder font-size-sm mr-3">View All</a></span>--}}
                            </h3>
                        </div>
                        <!--begin::Body-->
                        <div class="card-body pt-0 pb-3">
                            <div class="tab-content">
                                <!--begin::Table-->
                                <div class="table-responsive">
                                    <table class="table table-head-custom table-head-bg table-borderless table-vertical-center" id="sale-report-table">
                                        <thead>
                                        <tr class="text-left text-uppercase">
                                            <th class="text-center">SI</th>
                                            <th class="text-center">Vendor Name</th>
                                            <th class="text-center">Commission</th>
                                            <th class="text-center">Vendor Pay</th>
                                            <th class="text-center">Due</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Body-->
                        <!--end::Row-->
                    </div>
                    <!--end::Stats-->
                </div>
            </div>

            <div class="mt-25" id="vendor_list_panel" style="display: block">
                <div class=" mt-15">
                    <!--begin::Stats-->
                    <div class="card-spacer mt-n25 card">
                        <!--begin::Row-->
                        <div class="card-header border-0 py-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Vendors</span>
{{--                                <span class="text-muted mt-3 font-weight-bold font-size-sm"><a href="{{route('admin.report.sales')}}" target="_blank" class="btn btn-info font-weight-bolder font-size-sm mr-3">View All</a></span>--}}
                            </h3>
                        </div>
                        <!--begin::Body-->
                        <div class="card-body pt-0 pb-3">
                            <div class="tab-content">
                                <!--begin::Table-->
                                <div class="table-responsive">
                                    <table class="table table-head-custom table-head-bg table-borderless table-vertical-center" id="vendor_list-table">
                                        <thead>
                                        <tr class="text-left text-uppercase">
                                            <th class="text-center">SI</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Phone</th>
                                            <th class="text-center">Website</th>
                                            <th class="text-center">Address</th>
                                            <th class="text-center notexport">Logo</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Body-->
                        <!--end::Row-->
                    </div>
                    <!--end::Stats-->
                </div>
            </div>
            <!--end::Dashboard-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->


@endsection



@push('script')
    <script>
        $(document).ready(function () {
            $(".alert").delay(5000).slideUp(300);
            $.ajaxSetup({
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
            });
            var supplierDataTable =   $('#sale-report-table').DataTable({
                theme: 'bootstrap4',
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('superadmin.vendor_wise_commission.ajax')}}',
                    type: "POST",
                },
                bFilter: false,
                bLengthChange : false, //thought this line could hide the LengthMenu
                bInfo:false,
                // dom:'Blfrtip',
                // buttons: [
                //     {
                //         extend: 'excel',
                //         className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                //         text: 'Excel',
                //         exportOptions: {
                //             columns: ':not(.notexport)'
                //         }
                //     },
                //     {
                //         extend: 'pdf',
                //         className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                //         text: 'Pdf',
                //         download: 'open',
                //         exportOptions: {
                //             columns: ':not(.notexport)'
                //         }
                //     },
                //     {
                //         extend: 'print',
                //         text: 'Print',
                //         className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                //         exportOptions: {
                //             columns: ':not(.notexport)'
                //         }
                //     }
                // ],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'vendor_name', name: 'vendor_name'},
                    {data: 'superadmin_will_get', name: 'superadmin_will_get'},
                    {data: 'vendor_pay', name: 'vendor_pay'},
                    {data: 'vendor_due', name: 'vendor_due'},
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: 'N/A'
                }],
            });

            // $('#search').on('click', function(e) {
            //     supplierDataTable.draw();
            //     e.preventDefault();
            // });
            var vendorDataTable =   $('#vendor_list-table').DataTable({
                theme: 'bootstrap4',
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('superadmin.vendor.ajax')}}',
                    type: "POST",
                },
                // bFilter: false,
                bLengthChange : false, //thought this line could hide the LengthMenu
                bInfo:false,
                // dom:'Blfrtip',
                // buttons: [
                //     {
                //         extend: 'excel',
                //         className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                //         text: 'Excel',
                //         exportOptions: {
                //             columns: ':not(.notexport)'
                //         }
                //     },
                //     {
                //         extend: 'pdf',
                //         className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                //         text: 'Pdf',
                //         download: 'open',
                //         exportOptions: {
                //             columns: ':not(.notexport)'
                //         }
                //     },
                //     {
                //         extend: 'print',
                //         text: 'Print',
                //         className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                //         exportOptions: {
                //             columns: ':not(.notexport)'
                //         }
                //     }
                // ],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'website', name: 'website'},
                    {data: 'address', name: 'address'},
                    {data: 'image', name: 'image'},
                    {data: 'status', name: 'status'},
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: 'N/A'
                }],
            });

            $('#search').on('click', function(e) {
                vendorDataTable.draw();
                e.preventDefault();
            });

        });
        </script>

@endpush
