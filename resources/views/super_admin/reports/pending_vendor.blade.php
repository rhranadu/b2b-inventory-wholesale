
@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Pending Vendors Report')
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
{{--                            <div class="row align-items-center">--}}
{{--                                <div class="form-group col-md-3">--}}
{{--                                    <label for="#">Vendor Name</label>--}}
{{--                                    <select name="vendor_id" id="vendor_id" class="selectpicker form-control" data-live-search="true">--}}
{{--                                        <option value="">*Select Vendor</option>--}}
{{--                                        @foreach($vendors as $vendor)--}}
{{--                                            <option value="{{ $vendor->id }}" >{{ $vendor->name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                                <div class="form-group col-md-3">--}}
{{--                                    <label for="#">Start Date</label>--}}
{{--                                    <input required name="date" data-date="" data-date-format="DD MMMM YYYY"--}}
{{--                                             type="date" class="form-control"--}}
{{--                                            id="start-date">--}}
{{--                                </div>--}}
{{--                                <div class="form-group col-md-3">--}}
{{--                                    <label for="#">End Date</label>--}}
{{--                                    <input required name="date" data-date="" data-date-format="DD MMMM YYYY"--}}
{{--                                     type="date" class="form-control"--}}
{{--                                    id="end-date">--}}
{{--                                </div>--}}
{{--                                <div class="form-group col-md-3">--}}
{{--                                    <button type="submit" class="btn btn-primary mt-7" id="search">Search</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="ajax-data-div">
                                <table class="table table-hover table-bordered table-condensed" id="pending-vendor-report-table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SI</th>
                                        <th class="text-center" >Name</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Phone</th>
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
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
            });

            var pendingVendorTable =   $('#pending-vendor-report-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('superadmin.report.pending_vendors.ajax')}}',
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
                buttons: ['excel', 'pdf','print'],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'status', name: 'status'},
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: 'N/A'
                }],
                // rowCallback: function (row, data) {
                //     if (data.status == 0) {
                //         $('td:eq(4)', row).html( '<span class="badge badge-danger">Deactive</span>' );
                //     }
                // }
            });
            $('#search').on('click', function(e) {
                pendingVendorTable.draw();
                e.preventDefault();
            });

            $("table").on('click', '#ActiveUnactive', function () {
                var id = $(this).attr('data_id');
                var getStatus = $(this).attr('statusCode');
                var setStatus = (getStatus > 0) ? 0 : 1;
                $.ajax({
                    url: "{{ route('superadmin.vendor.statusActiveUnactive') }}",
                    type: "get",
                    data: {setStatus: setStatus, id: id},
                    success: function (res) {
                        if (res.success === true) {
                            // $(".VendorTable").load(location.href + " .VendorTable");
                            var pendingVendorTable = $('#pending-vendor-report-table').dataTable();
                            pendingVendorTable.fnDraw(false);
                            toastr.success(res.msg);
                        } else {
                            toastr.error(res.msg);
                        }
                    }
                })
            })
        });
    </script>
@endpush
