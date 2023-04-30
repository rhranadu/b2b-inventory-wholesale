
@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Purchases Report')
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
                                    <label for="#"></label>
                                    <button type="submit" class="btn btn-primary mt-7 " id="search">Search</button>
                                </div>
                            </div>
                            <div class="ajax-data-div table-responsive">
                                <table class="table table-hover table-bordered table-condensed" id="purchase-report-table">
                                    <thead>
                                    <tr>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--    <div class="modals-default-cl">--}}
{{--        <div class="modal fade payment_model" role="dialog">--}}
{{--            <div class="modal-dialog" role="document">--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="modal-header">--}}
{{--                        <h5 class="modal-title">Payment</h5>--}}
{{--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                            <i aria-hidden="true" class="ki ki-close"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        <div class="ajax-modal-div">--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

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

            var purchaseTable =   $('#purchase-report-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('admin.report.purchases.ajax')}}',
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
                    // {data: 'id', name: 'id'},
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

            });
            $('#search').on('click', function(e) {
                purchaseTable.draw();
                e.preventDefault();
            });
            // $(document).off('click', '#search').on('click', '#search', function (e) {

                // e.preventDefault();
                {{--$.ajax({--}}
                {{--    url: "{{ route('admin.report.purchases.invoice.ajax') }}",--}}
                {{--    method: "POST",--}}
                {{--    data: {--}}
                {{--        startDate: $("#start-date").val(),--}}
                {{--        endDate: $("#end-date").val(),--}}
                {{--    },--}}
                {{--    success: function (feedbackHtml) {--}}
                {{--        $(".ajax-data-div").html(feedbackHtml);--}}
                {{--    },--}}
                {{--});--}}
            // })
            // $(document).off('click', '#invoice-detail-btn').on('click', '#invoice-detail-btn', function (e) {
            //     e.preventDefault();
            //     var id = $(this).data('id');
            //     console.log('id: ', id);
            //     var href = $(this).attr('href');
            //     console.log('href: ', href);
            //     return false;
            //     $.ajax({
            //         url: href,
            //         method: "GET",
            //         success: function (feedbackHtml) {
            //             $(".ajax-data-div").html(feedbackHtml);
            //         },
            //     });
            // })



        });
    </script>
@endpush
