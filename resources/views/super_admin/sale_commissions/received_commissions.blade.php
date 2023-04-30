@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Sale Commission')
@push('css')
    <style>
        .table th, .table td{vertical-align:inherit;}
    </style>
@endpush
@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">

            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            @include('component.message')
                            <div class="row align-items-center">
                                <div class="form-group col-md-3">
                                    <label for="#">Vendor</label>
                                    <select name="vendor" id="vendor" class="form-control vendor" data-live-search="true">
                                        @foreach ($vendors as $k=>$v)
                                            <option value={{ $k }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-1">
                                    <button type="submit" class="btn btn-primary mt-7" id="search">Search</button>
                                </div>
                                <div class="form-group col-md-1">
                                    <button class="btn btn-danger mt-7" id="reset_btn" type="reset">Reset</button>
                                </div>

                            </div>
                            </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-condensed commonDatatable"
                                            id="commonDatatable">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Vendor</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Payment Date</th>
                                            <th class="text-center">Note</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center notexport">Action</th>
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

        $("#vendor").select2({
            width: '100%',
            allowClear: true,
            placeholder: 'Search for a vendor',
        });

        $(".alert").delay(5000).slideUp(300);
        $.ajaxSetup({
            headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
        });

        var commonDatatable =   $('#commonDatatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('superadmin.commission.received')}}',
                type: "POST",
                data: function (d) {
                    d.vendor_id = $('#vendor :selected').val();
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
                {data: 'vendor', name: 'vendor'},
                {data: 'amount', name: 'amount'},
                {data: 'payment_date', name: 'payment_date'},
                {data: 'note', name: 'note'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'},
            ],
            columnDefs: [{
                targets: '_all',
                defaultContent: 'N/A'
            }],
        });
        $('#search').on('click', function(e) {
            commonDatatable.draw();
            e.preventDefault();
        });
        $(document).off('click', '#reset_btn').on('click', '#reset_btn', function() {
            $("select[name=vendor]").val("").trigger("change");
        });




        // delete
        function receivePayment(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButtonColor: '#00c292',
                    cancelButton: 'btn btn-danger mt-0'
                },
                buttonsStyling: true
            });
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this! ⚠️",
                type: 'warning',
                cancelButtonColor: "#AF0000",
                showCancelButton: true,
                confirmButtonText: 'Yes, go for it!'
            }).then((result) => {
                if (result.value) {
                    var url = "{{URL::to('/')}}";
                    $.ajax({
                        url: url + "/superadmin/sale-commission/receive_payment/" + id,
                        type: "get",
                        success: function (res) {
                            if (res === 'true') {
                                var commonDatatable = $('#commonDatatable').dataTable();
                                commonDatatable.fnDraw(false);
                                toastr.success('Payment received');
                            } else {
                                toastr.error('Not found !');
                            }
                        }
                    })
                }
            })
        }


    </script>
@endpush
