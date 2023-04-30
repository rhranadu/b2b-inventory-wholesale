
@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Purchase Details By Supplier')
@push('css')

    {{-- .table th, .table td{vertical-align:inherit;} --}}
    <style>
        input[type="date"]::-webkit-datetime-edit, input[type="date"]::-webkit-inner-spin-button, input[type="date"]::-webkit-clear-button {
            color: #fff;
            position: relative;
        }

        input[type="date"]::-webkit-datetime-edit-year-field {
            position: absolute !important;
            border-left: 1px solid #8c8c8c;
            padding: 2px;
            color: #000;
            left: 56px;
        }

        input[type="date"]::-webkit-datetime-edit-month-field {
            position: absolute !important;
            border-left: 1px solid #8c8c8c;
            padding: 2px;
            color: #000;
            left: 26px;
        }


        input[type="date"]::-webkit-datetime-edit-day-field {
            position: absolute !important;
            color: #000;
            padding: 2px;
            left: 4px;

        }
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
{{--                                <div class="form-group col-md-3">--}}
{{--                                    <label for="#">Vendor Name</label>--}}
{{--                                    <select name="vendor_id" id="vendor_id" class="selectpicker form-control" data-live-search="true">--}}
{{--                                        <option value="">*Select Vendor</option>--}}
{{--                                        @foreach($vendors as $vendor)--}}
{{--                                            <option value="{{ $vendor->id }}" >{{ $vendor->name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}

                                <div class="form-group col-md-4">
                                    <label for="#">Date Range</label>
                                    <input type='text' class="form-control" name="date" id="kt_daterangepicker_6" readonly placeholder="Select time" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="#">Supplier</label>
                                    <select name="supplier" id="supplier" class="form-control" data-live-search="true"></select>
                                </div>

                            <div class="form-group col-md-1">
                                <button class="btn btn-danger mt-7" id="reset_btn" type="reset">Reset</button>
                            </div>
                            <div class="form-group col-md-3">
                                <button type="submit" class="btn btn-primary mt-7" id="search">Search</button>
                            </div>
                            </div>
                            <div class="ajax-data-div table-responsive">
                                <table class="table table-hover table-bordered table-condensed" id="purchase-details-report-table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SI</th>
                                        <th class="text-center">Invoice No.</th>
                                        <th class="text-center">Supplier</th>
                                        <th class="text-center" >Product Name</th>
                                        <th class="text-center" >Attributes</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Receive</th>
                                        <th class="text-center">Due</th>
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
        });

        // Class definition

        var KTBootstrapDaterangepicker = function () {

            // Private functions
            var demos = function () {

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

            $("#supplier").select2({
                width: '100%',
                allowClear: true,
                ajax: {
                    url: "{{ route("admin.supplier.list") }}",
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
                placeholder: 'Search for a supplier',
            });

            var purchaseDetailsTable =   $('#purchase-details-report-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('admin.report.purchases.supplier.details.ajax')}}',
                    type: "POST",
                    data: function (d) {
                        d.supplier_id = $('#supplier').val();
                        const date_range = $('#kt_daterangepicker_6').val().split("-");
                        d.startDate = date_range[0];
                        d.endDate = date_range[1];
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
                    {data: 'invoice_no', name: 'invoice_no'},
                    {data: 'supplier_name', name: 'supplier_name'},
                    {data: 'product_name', name: 'product_name'},
                    {data: 'product_attributes', name: 'product_attributes'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'product_receive_total', name: 'product_receive_total'},
                    {data: 'product_due_total', name: 'product_due_total'},
                ],
                // order: [[0, 'desc']],
                // rowsGroup: [0,1],
                rowGroup: {
                    responsive: true,
                    dataSrc: 'supplier_name'
                },
                columnDefs: [
                    {defaultContent: 'N/A', targets: '_all',},
                    {className: 'dt-center', targets: '_all'}
                ],
            });


            $('#search').on('click', function(e) {
                purchaseDetailsTable.draw();
                e.preventDefault();
            });


        });
    </script>
@endpush
