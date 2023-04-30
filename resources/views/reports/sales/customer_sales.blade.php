@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Customer Sale Report')

@push('css')
@endpush

@section('main_content')
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    @include('component.message')
                    <div class="row align-items-center">
                        <div class="form-group col-md-2">
                            <label for="#">Start Date</label>
                            <input required name="date" data-date="" data-date-format="DD MMMM YYYY"
                                     type="date" class="form-control"
                                    id="startDate">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="#">End Date</label>
                            <input required name="date" data-date="" data-date-format="DD MMMM YYYY"
                             type="date" class="form-control"
                            id="endDate">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="#">Customer</label>
                            <select name="customer" id="customer" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="#">Invoice</label>
                            <select name="invoice" id="invoice" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="#">Sale Status</label>
                            <select name="status" id="status" class="form-control" data-live-search="true"></select>
                        </div>
                       <div class="form-group col-md-1">
                            <button class="btn btn-danger mt-7" id="reset_btn" type="reset">Reset</button>
                        </div>
                        <div class="form-group col-md-1">
                            <button type="submit" class="btn btn-primary mt-7" id="search">Search</button>
                        </div>
                    </div>
                        <div class="table-responsive">
                            <table
                                class="table table-hover table-bordered table-condensed"
                                id="datatable">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Invoice</th>
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Placed From</th>
                                    <th class="text-center">Items</th>
                                    <th class="text-center">Sale Status</th>
                                    <th class="text-center">Sale Amount</th>
                                    <th class="text-center">Sale Date</th>
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
@endsection

@push('script')
<script>
        $(document).off('click', '#reset_btn').on('click', '#reset_btn', function() {
            $("select[name=customer]").val("").trigger("change");
            $("select[name=invoice]").val("").trigger("change");
            $("select[name=status]").val("").trigger("change");
            $('input[name=date]').val("").trigger("change");
        });
    $(".alert").delay(5000).slideUp(300);
    $.ajaxSetup({
        headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
    });
    $("#invoice").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.report.sale.invoices") }}",
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {search: params.term};
            },
            processResults: function (data, params) {
                return {
                    results: $.map(data, function (item,i) {
                        return {id: i, text: item}
                    }),

                };
            },
            cache: true
        },
        placeholder: 'Search for a invoice',
    });
    $("#customer").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.report.pos_mp_customer.list") }}",
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {search: params.term};
            },
            processResults: function (data, params) {
                return {
                    results: $.map(data, function (item,i) {
                        return {id: i, text: item}
                    }),

                };
            },
            cache: true
        },
        placeholder: 'Search for a customer',
    });
    $("#status").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.report.sale_status.list") }}",
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {search: params.term};
            },
            processResults: function (data, params) {
                return {
                    results: $.map(data, function (item, i) {
                        return {id: i, text: item}
                    }),

                };
            },
            cache: true
        },
        placeholder: 'Search for a Sale Status',
    });

    var datatable =   $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{route('admin.report.customer_sales.ajax')}}',
            type: "POST",
            data: function (d) {
                d.start_date = $('#startDate').val();
                d.end_date = $('#endDate').val();
                d.customer_id = $('#customer :selected').val();
                d.invoice_no = $('#invoice :selected').val();
                d.status = $('#status :selected').val();
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
            {data: 'invoice_no', name: 'invoice_no'},
            {data: 'customer', name: 'customer'},
            {data: 'placed_from', name: 'placed_from'},
            {data: 'items', name: 'items'},
            {data: 'status', name: 'status'},
            {data: 'final_price', name: 'final_price'},
            {data: 'sale_date', name: 'sale_date'},
        ],
        columnDefs: [{
            targets: '_all',
            defaultContent: 'N/A'
        }],
    });

    $('#search').on('click', function(e) {
        datatable.draw();
        e.preventDefault();
    });

</script>
@endpush
