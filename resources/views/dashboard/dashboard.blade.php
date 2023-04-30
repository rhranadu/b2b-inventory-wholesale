@extends('layouts.app')
@include('component.dataTable_resource')
@section('title', 'Dashboard')

@push('css')

@endpush
@section('main_content')
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="row m-0">
                <div class="col-xl-3">
                    <div class="card card-custom bg-primary gutter-b">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <span><i class="fas fa-file-invoice fa-3x text-white" aria-hidden="true"></i></span>
                                <div class="text-inverse-primary font-weight-bolder font-size-h2 mt-3">{{isset($data['today_sale_invoice']) ? number_format($data['today_sale_invoice']) : 'N/A' }}</div>
                            </div>
                            <a href="#" class="text-inverse-primary font-weight-bold font-size-lg mt-4 d-block">Today Invoice</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card card-custom bg-primary gutter-b">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <span><i class="fas fa-3x text-white">৳</i></span>
                                <div class="text-inverse-primary font-weight-bolder font-size-h2 mt-3">{{isset($data['today_purchase_invoice']) ? number_format($data['today_purchase_invoice']) : 'N/A' }}</div>
                            </div>
                            <a href="#" class="text-inverse-primary font-weight-bold font-size-lg mt-4 d-block">Today Purchase</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card card-custom bg-primary gutter-b">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <span><i class="fas fa-tasks  fa-3x text-white"></i></span>
                                <div class="text-inverse-primary font-weight-bolder font-size-h2 mt-3">{{isset($data['total_products']) ? number_format($data['total_products']) : 'N/A' }}</div>
                            </div>
                            <a href="#" class="text-inverse-primary font-weight-bold font-size-lg mt-4 d-block">Products List</a>
                        </div>
                    </div>
                </div>
{{--                <div class="col-xl-3">--}}
{{--                    <div class="card card-custom bg-primary gutter-b">--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="d-flex align-items-center justify-content-between">--}}
{{--                                <span><i class="fas fa-tasks  fa-3x text-white"></i></span>--}}
{{--                                <div class="text-inverse-primary font-weight-bolder font-size-h2 mt-3">{{isset($data['total_products_items']) ? number_format($data['total_products_items']) : 'N/A' }}</div>--}}
{{--                            </div>--}}
{{--                            <a href="#" class="text-inverse-primary font-weight-bold font-size-lg mt-4 d-block">Stocks in Hand</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="col-xl-3">
                    <div class="card card-custom bg-primary gutter-b">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <span><i class="fas fa-tasks  fa-3x text-white"></i></span>
                                <div class="text-inverse-primary font-weight-bolder font-size-h2 mt-3">{{isset($data['alert_products']) ? number_format($data['alert_products']) : 'N/A' }}</div>
                            </div>
                            <a href="#" class="text-inverse-primary font-weight-bold font-size-lg mt-4 d-block">Reorder Quantity Products</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card card-custom bg-primary gutter-b">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <span><i class="fas fa-tasks  fa-3x text-white"></i></span>
                                <div class="text-inverse-primary font-weight-bolder font-size-h2 mt-3">{{isset($data['payable_commission']) ? number_format($data['payable_commission']) : 'N/A' }}</div>
                            </div>
                            <a href="#" class="text-inverse-primary font-weight-bold font-size-lg mt-4 d-block">Commission Payable</a>
                        </div>
                    </div>
                </div>
{{--                <div class="col-xl-3">--}}
{{--                    <div class="card card-custom bg-primary gutter-b">--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="d-flex align-items-center justify-content-between">--}}
{{--                                <span><i class="fas  fa-3x text-white">৳</i></span>--}}
{{--                                <div class="text-inverse-primary font-weight-bolder font-size-h2 mt-3">{{isset($data['total_sale']) ? number_format($data['total_sale']) : 'N/A' }}</div>--}}
{{--                            </div>--}}
{{--                            <a href="#" class="text-inverse-primary font-weight-bold font-size-lg mt-4 d-block">Total Sales</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                {{-- <div class="col-xl-3">
                    <div class="card card-custom bg-primary gutter-b">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <span><i class="fas  fa-3x text-white">৳</i></span>
                                <div class="text-inverse-primary font-weight-bolder font-size-h2 mt-3">{{isset($data['today_sale']) ? number_format($data['today_sale']) : 'N/A' }}</div>
                            </div>
                            <a href="#" class="text-inverse-primary font-weight-bold font-size-lg mt-4 d-block">Today Sale</a>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="mt-25" id="sales_report_panel" style="display: none">
                <div class=" mt-15">
                    <!--begin::Stats-->
                    <div class="card-spacer mt-n25 card">
                        <!--begin::Row-->
                        <div class="card-header border-0 py-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Sales Report</span>
                                <span class="text-muted mt-3 font-weight-bold font-size-sm"><a href="{{route('admin.report.sales')}}" target="_blank" class="btn btn-info font-weight-bolder font-size-sm mr-3">View All</a></span>
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
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Sale Warehouse</th>
                                            <th class="text-center">POS Customer</th>
                                            <th class="text-center">Invoice No</th>
                                            <th class="text-center">Sub Total</th>
                                            <th class="text-center">Tax</th>
                                            <th class="text-center">Shipping Charge</th>
                                            <th class="text-center">Discount</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Pay Amount</th>
                                            <th class="text-center">Due Amount</th>
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


            <div class="mt-35" id="purchase_report_panel" style="display: none">
                <div class=" mt-15">
                    <!--begin::Stats-->
                    <div class="card-spacer mt-n25 card">
                        <!--begin::Row-->
                        <div class="card-header border-0 py-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Purchase Report</span>

                                <span class="text-muted mt-3 font-weight-bold font-size-sm"><a href="{{route('admin.report.purchases')}}" class="btn btn-info font-weight-bolder font-size-sm mr-3" target="_blank">View All</a></span>
                            </h3>
                        </div>
                        <!--begin::Body-->
                        <div class="card-body pt-0 pb-3">
                            <div class="tab-content">
                                <!--begin::Table-->
                                <div class="table-responsive">
                                    <table class="table table-head-custom table-head-bg table-borderless table-vertical-center" id="purchase-report-table">
                                        <thead>
                                        <tr class="text-left text-uppercase">
                                            <th class="text-center">SI</th>
                                            <th class="text-center" >Date</th>
                                            <th class="text-center">Invoice No</th>
                                            <th class="text-center" >Supplier Name</th>
                                            <th class="text-center" >Total Item</th>
                                            <th class="text-center" >Additional Expense</th>
                                            <th class="text-center" >Status</th>
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
            <div class="mt-35" id="alert_quantity_report_panel" style="display: none">
                <div class=" mt-15">
                    <!--begin::Stats-->
                    <div class="card-spacer mt-n25 card">
                        <!--begin::Row-->
                        <div class="card-header border-0 py-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Reorder Quantity Report</span>

                                <span class="text-muted mt-3 font-weight-bold font-size-sm"><a href="{{route('admin.report.product_alert_quantity')}}" class="btn btn-info font-weight-bolder font-size-sm mr-3" target="_blank">View All</a></span>
                            </h3>
                        </div>
                        <!--begin::Body-->
                        <div class="card-body pt-0 pb-3">
                            <div class="tab-content">
                                <!--begin::Table-->
                                <div class="text-center">
                                    <p class="badge badge-primary">*** Available Quantity = Stock Quantity - Freeze Stock Quantity for orders. </p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-head-custom table-head-bg table-borderless table-vertical-center" id="alert-quantity-report-table">
                                        <thead>
                                        <tr class="text-left text-uppercase">
                                            <th class="text-center">SI</th>
                                            <th class="text-center" >Product Name</th>
                                            <th class="text-center">Stock Quantity</th>
                                            <th class="text-center">Available Quantity</th>
                                            <th class="text-center">Alert Quantity</th>
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
            <!--end:: of dynamic reports generate-->
            <!--end::Dashboard-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->


@endsection



@push('script')
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
        });

    });
    $(window).on('load', function () {
        sale_report();
    });

    function sale_report() {
        $('#sales_report_panel').show();
        var saleTable =   $('#sale-report-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('admin.report.sales.ajax')}}',
                type: "POST",
                data: function (d) {
                    d.dashboard = 'yes';
                }
            },
            // pageLength:  10,
            // "bPaginate": false,
            bFilter: false,
            bLengthChange : false, //thought this line could hide the LengthMenu
            bInfo:false,
            // dom:'Blfrtip',
            // buttons: ['excel', 'pdf','print'],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                // {data: 'id', name: 'id'},
                {data: 'created_date', name: 'created_date'},
                {data: 'saleWarehouseName', name: 'saleWarehouseName'},
                {data: 'pos_customer.name', name: 'pos_customer.name'},
                {data: 'invoice_no', name: 'invoice_no'},
                {data: 'sub_total', name: 'sub_total'},
                {data: 'tax', name: 'tax'},
                {data: 'shipping_charge', name: 'shipping_charge'},
                {data: 'discount', name: 'discount'},
                {data: 'final_total', name: 'final_total'},
                {data: 'pay_amount', name: 'pay_amount'},
                {data: 'due_amount', name: 'due_amount'},
                {data: 'status', name: 'status'}
            ],
            columnDefs: [{
                targets: '_all',
                defaultContent: 'N/A'
            }],
            drawCallback: function(settings){
                if($(this).find('tbody tr').length <= 10){
                    $('#sale-report-table_paginate').hide();
                }
            },
        });
        purchase_report();
    }
    function purchase_report() {
        $('#purchase_report_panel').show();
        var purchaseTable =   $('#purchase-report-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('admin.report.purchases.ajax')}}',
                type: "POST",
                data: function (d) {
                    d.dashboard = 'yes';
                }
            },
            // "bPaginate": false,
            bFilter: false,
            bLengthChange : false, //thought this line could hide the LengthMenu
            bInfo:false,
            // dom:'Blfrtip',
            // buttons: ['excel', 'pdf','print'],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'date', name: 'date'},
                {data: 'invoice_no', name: 'invoice_no'},
                {data: 'purchase_supplier.name', name: 'purchase_supplier.name'},
                {data: 'purchaseDetailCount', name: 'purchaseDetailCount'},
                {data: 'purchaseAdditionalExpense', name: 'purchaseAdditionalExpense'},
                {data: 'status', name: 'status'},
            ],
            columnDefs: [{
                targets: '_all',
                defaultContent: 'N/A'
            }],
            drawCallback: function(settings){
                if($(this).find('tbody tr').length <= 10){
                    $('#purchase-report-table_paginate').hide();
                }
            },
        });
        alert_quantity_report();
    }
    function alert_quantity_report() {
        $('#alert_quantity_report_panel').show();
        var alertQuantityTable =   $('#alert-quantity-report-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('admin.report.product_alert_quantity.ajax')}}',
                type: "POST",
                data: function (d) {
                    d.dashboard = 'yes';
                }
            },
            // "bPaginate": false,
            bFilter: false,
            bLengthChange : false, //thought this line could hide the LengthMenu
            bInfo:false,
            // dom:'Blfrtip',
            // buttons: ['excel', 'pdf','print'],
            drawCallback: function(settings){
                if($(this).find('tbody tr').length <= 10){
                    $('#alert-quantity-report-table_paginate').hide();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                // {data: 'purchases.invoice_no', name: 'purchases.invoice_no'},
                {data: 'product.name', name: 'product.name'},
                {data: 'quantity', name: 'quantity'},
                {data: 'product_receive_total', name: 'product_receive_total'},
                {data: 'product.alert_quantity', name: 'product.alert_quantity'},
                // {data: 'product_due_total', name: 'product_due_total'},
            ],
            columnDefs: [{
                targets: '_all',
                defaultContent: 'N/A'
            }],
            // order: [[0, 'desc']],
            // rowsGroup: [0,1],
            // rowGroup: {
            //     responsive: true,
            //     dataSrc: 'purchases.invoice_no'
            // }
        });
    }
</script>
@endpush
