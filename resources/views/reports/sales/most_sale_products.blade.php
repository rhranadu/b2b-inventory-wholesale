@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Most Sale Products Report')
@push('css')

    {{-- .table th, .table td{vertical-align:inherit;} --}}
    <style>
        /*input[type="date"]::-webkit-datetime-edit, input[type="date"]::-webkit-inner-spin-button, input[type="date"]::-webkit-clear-button {*/
        /*    color: #fff;*/
        /*    position: relative;*/
        /*}*/

        /*input[type="date"]::-webkit-datetime-edit-year-field {*/
        /*    position: absolute !important;*/
        /*    border-left: 1px solid #8c8c8c;*/
        /*    padding: 2px;*/
        /*    color: #000;*/
        /*    left: 56px;*/
        /*}*/

        /*input[type="date"]::-webkit-datetime-edit-month-field {*/
        /*    position: absolute !important;*/
        /*    border-left: 1px solid #8c8c8c;*/
        /*    padding: 2px;*/
        /*    color: #000;*/
        /*    left: 26px;*/
        /*}*/


        /*input[type="date"]::-webkit-datetime-edit-day-field {*/
        /*    position: absolute !important;*/
        /*    color: #000;*/
        /*    padding: 2px;*/
        /*    left: 4px;*/

        /*}*/
    </style>
@endpush

@section('main_content')

    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    @include('component.message')
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <div class="row align-items-center">
                                <div class="form-group col-md-3">
                                    <label for="#">Date Range</label>
                                    <input type='text' class="form-control" name="date" id="kt_daterangepicker_6" readonly placeholder="Select time" >
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="#">Brand</label>
                                    <select name="brand" id="brand" class="form-control" data-live-search="true"></select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="#">Product</label>
                                    <select name="product" id="product" class="form-control" data-live-search="true"></select>
                                </div>
                                 <div class="form-group col-md-1">
                                    <button class="btn btn-danger mt-7" id="reset_btn" type="reset">Reset</button>
                                </div>
                                <div class="form-group col-md-1">
                                    <button type="submit" class="btn btn-primary mt-7" id="search">Search</button>
                                </div>
                            </div>
                            <div class="ajax-data-div table-responsive">
                                <table class="table table-hover table-bordered table-condensed" id="sale-report-table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SI</th>
                                        <th class="text-center">Product Name</th>
                                        <th class="text-center">Category Name</th>
                                        <th class="text-center">Brand Name</th>
                                        <th class="text-center">Total Sale</th>
{{--                                        <th class="text-center">Status</th>--}}
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
            $(document).off('click', '#reset_btn').on('click', '#reset_btn', function() {
                $('input[name=date]').val("").trigger("change");
                $('select[name=product]').val("").trigger("change");
                $('select[name=brand]').val("").trigger("change");
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
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
            });

            var saleTable =   $('#sale-report-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('admin.report.most_sale_products.ajax')}}',
                    type: "POST",
                    data: function (d) {
                        // d.startDate = $('#start-date').val();
                        // d.endDate = $('#end-date').val();
                        const date_range = $('#kt_daterangepicker_6').val().split("-");
                        d.product_id = $('#product').val();
                        d.brand_id = $('#brand').val();
                        d.start_date = date_range[0];
                        d.end_date = date_range[1];
                    }
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
                    {data: 'name', name: 'name'},
                    {data: 'category', name: 'category'},
                    {data: 'band', name: 'band'},
                    {data: 'total_sale', name: 'total_sale'},
                    // {data: 'status', name: 'status'}
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: 'N/A'
                }],

            });
            $('#search').on('click', function(e) {
                saleTable.draw();
                e.preventDefault();
            });

        });
    </script>
@endpush
