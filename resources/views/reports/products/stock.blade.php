@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Products Stock Report')
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
                                <table class="table table-hover table-bordered table-condensed" id="product-stock-report-table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SI</th>
                                        <th class="text-center" >Supplier Name</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Invoice No</th>
                                        <th class="text-center">Product Stock Count</th>
                                        <th class="text-center">Invoice Amount</th>
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

            var productStockTable =   $('#product-stock-report-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('admin.report.product_stocks.ajax')}}',
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
                    {data: 'purchase_supplier.name', name: 'purchase_supplier.name'},
                    {data: 'date', name: 'date'},
                    {data: 'invoice_no', name: 'invoice_no'},
                    {data: 'productStockDetailCount', name: 'productStockDetailCount'},
                    {data: 'productStockInvoiceAmount', name: 'productStockInvoiceAmount'},
                ]
            });
            $('#search').on('click', function(e) {
                productStockTable.draw();
                e.preventDefault();
            });
        });
    </script>
@endpush
