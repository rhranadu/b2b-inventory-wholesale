@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Warehouses')

@push('css')
    .table th, .table td{vertical-align:inherit;}
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
                            <table
                                class="table table-hover table-bordered table-condensed warehouseDataTable"
                                id="warehouseDataTable">
                                <thead>
                                <tr>
                                    <th class="text-center">SI</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Address</th>
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
@endsection

@push('script')
    <script>
        $().ready(function () {
            $(".alert").delay(5000).slideUp(300);
            $.ajaxSetup({
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
            });
            var warehouseDataTable =   $('#warehouseDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('admin.warehouse.ajax')}}',
                    type: "POST",
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
                    {data: 'type', name: 'type'},
                    {data: 'address', name: 'address'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: 'N/A'
                }],
            });

            $('#search').on('click', function(e) {
                warehouseDataTable.draw();
                e.preventDefault();
            });

            $("table").on('click', '#ActiveUnactive', function () {
                var id = $(this).attr('data_id');
                var getStatus = $(this).attr('statusCode');
                var setStatus = (getStatus > 0) ? 0 : 1;
                $.ajax({
                    url: "{{ route('admin.warehouse.statusActiveUnactive') }}",
                    type: "get",
                    data: {setStatus: setStatus, id: id},
                    success: function (res) {
                        if (res === 'true') {
                            // $(".warehouseDataTable").load(location.href + " .warehouseDataTable");
                            var warehouseDataTable = $('#warehouseDataTable').dataTable();
                            warehouseDataTable.fnDraw(false);
                            toastr.success('Warehouse status updated success');
                        } else {
                            toastr.error('Not found !');
                        }
                    }
                })
            })
        });


        // delete warehouse
        function deleteWarehouse(id) {
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
                    toastr.success('Warehouse deleted success');
                    event.preventDefault();
                    document.getElementById('deleteWarehouseForm-' + id).submit();
                }
            })
        };


    </script>
@endpush