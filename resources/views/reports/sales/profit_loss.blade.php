@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Profit Loss Report')

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

                            <label for="#">Date Range</label>
                                    <input type='text' class="form-control" name="date" id="kt_daterangepicker_6" readonly placeholder="Select time" />
                        </div>
                        <div class="form-group col-md-3">
                            <label for="#">Brand</label>
                            <select name="brand" id="brand" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="#">Product</label>
                            <select name="product" id="product" class="form-control" data-live-search="true"></select>
                        </div>
                         <div class="form-group col-md-1">
                                    <button class="btn btn-danger mt-7" id="reset_btn" type="reset">Reset</button>
                                </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-primary mt-7" id="search">Search</button>
                        </div>
                    </div>
                        <div class="table-responsive">
                            <table
                                class="table table-hover table-bordered table-condensed manufacturerDataTable"
                                id="datatable">
                                <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th class="text-center">Invoice No</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Customer Name</th>
                                    <th class="text-center">Placed From</th>
                                    <th class="text-center">Warehouse Name</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Total Purchase Amount</th>
                                    <th class="text-center">Total Sold Amount</th>
                                    <th class="text-center">Total Profit</th>
                                    <th class="text-center">Sold Date</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot align="right">
                                <tr id="total"><th>Total</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
                                </tfoot>
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
            $('select[name=product]').val("").trigger("change");
            $('select[name=brand]').val("").trigger("change");
            $('input[name=date]').val("").trigger("change");
        });
    // Class definition

    var KTBootstrapDaterangepicker = function () {

        // Private functions
        var demos = function () {
            // minimum setup
            $('#kt_daterangepicker_1, #kt_daterangepicker_1_modal').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary'
            });

            // input group and left alignment setup
            $('#kt_daterangepicker_2').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary'
            }, function(start, end, label) {
                $('#kt_daterangepicker_2 .form-control').val( start.format('YYYY-MM-DD') + ' / ' + end.format('YYYY-MM-DD'));
            });

            $('#kt_daterangepicker_2_modal').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary'
            }, function(start, end, label) {
                $('#kt_daterangepicker_2 .form-control').val( start.format('YYYY-MM-DD') + ' / ' + end.format('YYYY-MM-DD'));
            });

            // left alignment setup
            $('#kt_daterangepicker_3').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary'
            }, function(start, end, label) {
                $('#kt_daterangepicker_3 .form-control').val( start.format('YYYY-MM-DD') + ' / ' + end.format('YYYY-MM-DD'));
            });

            $('#kt_daterangepicker_3_modal').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary'
            }, function(start, end, label) {
                $('#kt_daterangepicker_3 .form-control').val( start.format('YYYY-MM-DD') + ' / ' + end.format('YYYY-MM-DD'));
            });


            // date & time
            $('#kt_daterangepicker_4').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary',

                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'YYYY-MM-DD h:mm A'
                }
            }, function(start, end, label) {
                $('#kt_daterangepicker_4 .form-control').val( start.format('YYYY-MM-DD h:mm A') + ' / ' + end.format('YYYY-MM-DD h:mm A'));
            });

            // date picker
            $('#kt_daterangepicker_5').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary',

                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }, function(start, end, label) {
                $('#kt_daterangepicker_5 .form-control').val( start.format('YYYY-MM-DD') + ' / ' + end.format('YYYY-MM-DD'));
            });

            // predefined ranges
            var start = moment().subtract(29, 'days');
            var end = moment();

            $('#kt_daterangepicker_6').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary',

                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start, end, label) {
                $('#kt_daterangepicker_6 .form-control').val( start.format('YYYY-MM-DD')+ ' / ' + end.format('YYYY-MM-DD'));
            });
        }

        return {
            // public functions
            init: function() {
                demos();
            }
        };
    }();

    jQuery(document).ready(function() {
        KTBootstrapDaterangepicker.init();
    });
    $(".alert").delay(5000).slideUp(300);
    $.ajaxSetup({
        headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
    });

    $("#product").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.product.dropdown.list") }}",
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    brand_id:$('#brand').val(),
                    search: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            id: item.id,
                            text: item.name,
                        }
                    }),
                    pagination: {
                        more: (params.page * 10) < data.total
                    }
                };
            },
            cache: true
        },
        placeholder: 'Search for a product',
    });

    $("#brand").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.report.brand.Search.ajax") }}",
            dataType: 'json',
            type: 'POST',
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
        placeholder: 'Search for a Brand',
    });

    var datatable =   $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{route('admin.report.profit_loss.ajax')}}',
            type: "POST",
            data: function (d) {
                const date_range = $('#kt_daterangepicker_6').val().split("-");
                d.start_date = date_range[0];
                d.end_date = date_range[1];
                d.product_id = $('#product').val();
                d.brand_id = $('#brand').val();
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
            {data: 'product_name', name: 'product_name'},
            {data: 'customer', name: 'customer'},
            {data: 'placed_from', name: 'placed_from'},
            {data: 'warehouse_name', name: 'warehouse_name'},
            {data: 'quantity', name: 'quantity'},
            {data: 'total_cumulative_purchase_price', name: 'total_cumulative_purchase_price'},
            {data: 'total_cumulative_sold_price', name: 'total_cumulative_sold_price'},
            {data: 'total_cumulative_profit', name: 'total_cumulative_profit'},
            {data: 'sale_date', name: 'sale_date'},
        ],
        columnDefs: [{
            targets: '_all',
            defaultContent: 'N/A'
        }],


        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0.00;
            };

            // Total over all pages
            // total = api
            //     .column( 9 )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );

            // Total over this page
            total_purchase = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return (intVal(a) + intVal(b)).toFixed(2);
                }, 0 );

            // Update footer
            $( api.column( 7 ).footer() ).html(
                total_purchase+' Tk.'
            );
            // Total over this page
            total_sold = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return (intVal(a) + intVal(b)).toFixed(2);
                }, 0 );

            // Update footer
            $( api.column( 8 ).footer() ).html(
                total_sold+' Tk.'
            );
            // Total over this page
            total_profit = api
                .column( 9, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return (intVal(a) + intVal(b)).toFixed(2);
                }, 0 );

            // Update footer
            $( api.column( 9 ).footer() ).html(
                total_profit+' Tk.'
            );
        }
    });

    $('#search').on('click', function(e) {
        datatable.draw();
        e.preventDefault();
    });

</script>
@endpush
