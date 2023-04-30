
@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Sales Report')
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
                                <div class="form-group col-md-3">
                                    <label for="#">Start Date</label>
                                    <input required name="date" data-date="" data-date-format="DD MMMM YYYY"
                                             type="date" class="form-control"
                                            id="start-date">
{{--                                    <p id="autofocusDate"></p>--}}
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="#">End Date</label>
                                    <input required name="date" data-date="" data-date-format="DD MMMM YYYY"
                                     type="date" class="form-control"
                                    id="end-date">
{{--                                    <p id="autofocusDate"></p>--}}
                                </div>
                                   <div class="form-group col-md-1">
                            <button class="btn btn-danger mt-7" id="reset_btn" type="reset">Reset</button>
                        </div>
                                <div class="form-group col-md-3">
                                    <button type="submit" class="btn btn-primary mt-7" id="search">Search</button>
                                </div>
                            </div>
                            <div class="ajax-data-div table-responsive">
                                <table class="table table-hover table-bordered table-condensed" id="sale-report-table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SL</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Sold From</th>
                                        <th class="text-center">Warehouse</th>
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
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
            });

            var saleTable =   $('#sale-report-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('admin.report.sales.ajax')}}',
                    type: "POST",
                    data: function (d) {
                        d.vendor_id = $('#vendor_id').val();
                        d.startDate = $('#start-date').val();
                        d.endDate = $('#end-date').val();
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
                    {data: 'created_date', name: 'created_date'},
                    {data: 'type_of_sale', name: 'sold_from'},
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

            });
            $('#search').on('click', function(e) {
                saleTable.draw();
                e.preventDefault();
            });
            {{--$(document).off('click', '#search').on('click', '#search', function (e) {--}}
            {{--    e.preventDefault();--}}
            {{--    $.ajax({--}}
            {{--        url: "{{ route('admin.report.sales.ajax') }}",--}}
            {{--        method: "POST",--}}
            {{--        data: {--}}
            {{--            startDate: $("#start-date").val(),--}}
            {{--            endDate: $("#end-date").val(),--}}
            {{--        },--}}
            {{--        success: function (feedbackHtml) {--}}
            {{--            $(".ajax-data-div").html(feedbackHtml);--}}
            {{--        },--}}
            {{--    });--}}
            {{--})--}}
        });
    </script>
@endpush
