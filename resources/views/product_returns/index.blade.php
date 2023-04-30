@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Product Return')
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
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-condensed" id="product_return_datatable">
                                <thead>
                                <tr>
                                    <th class="text-center">Sl</th>
                                    <th class="text-center">Requested At</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Returned Product Barcode</th>
                                    <th class="text-center">Exchanged Product Barcode</th>
                                    <th class="text-center">Request Type</th>
                                    <th class="text-center">Approved Request Type</th>
                                    <th class="text-center">Reason</th>
                                    <th class="text-center">Comment</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
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
    <form action="{{ route('admin.return.request.detail') }}" method="POST" target="_blank" id="return_detail_view_helper_form">
        @csrf
        <input type="hidden" name="return_product_barcode" id="return_product_barcode"/>
        <input type="hidden" name="return_product_id" id="return_product_id"/>
        <input type="submit" class="d-none" value="Submit">
    </form>
@endsection

@push('script')
    <script>
        $(".alert").delay(5000).slideUp(300);
        $.ajaxSetup({
            headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
        });
        var productReturnDataTable =   $('#product_return_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('admin.return.product')}}',
                type: "GET",
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
                {data: 'created_at', name: 'created_at'},
                {data: 'product.name', name: 'product.name'},
                {data: 'returned_stocked_product_barcode', name: 'returned_stocked_product_barcode'},
                {data: 'exchanged_stocked_product_barcode', name: 'exchanged_stocked_product_barcode'},
                {data: 'request_type', name: 'request_type'},
                {data: 'approved_request_type', name: 'approved_request_type'},
                {data: 'reason', name: 'reason'},
                {data: 'comment', name: 'comment'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'},
            ],
            columnDefs: [{
                targets: '_all',
                defaultContent: 'N/A'
            }],

        });

        $(document).off('click', '.delete-return-request').on('click', '.delete-return-request', function () {
            var url = "{{url('admin/product_returns')}}/"+$(this).data('key');
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
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    $.ajax({
                        url: url,
                        type: 'delete',
                        dataType: 'json',
                        beforeSend: function(){
                            $.blockUI();
                        },
                        complete: function(){
                            $.unblockUI();
                        },
                        success: function(data) {
                            var productReturnDatatable = $('#product_return_datatable').dataTable();
                            productReturnDatatable.fnDraw(false);
                            toastr.success(data.msg);
                        },
                        error: function(data) {
                            toastr.error('Failed to delete');
                        }
                    });
                }
            })
        })
        $(document).off('click', '.take-action, .return-detail').on('click', '.take-action, .return-detail', function () {
            $("#return_product_barcode").val($(this).data('rtnBarcode'))
            $("#return_product_id").val($(this).data('key'))
            $("#return_detail_view_helper_form").submit();
        })
        $('#product_return_datatable').on('draw.dt', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

    </script>
@endpush
