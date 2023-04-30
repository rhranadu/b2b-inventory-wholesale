@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Suppliers')

@push('css')

@endpush

@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px">
                <div class="card-body">

                    <!-- Data Table area Start-->
                    @include('component.message')
                        <div class="data-table-list">

                            <div class="table-responsive">
                                <table id="supplierDataTable" class="table table-striped supplierDataTable">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SI</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Phone</th>
                                        <th class="text-center">Address</th>
                                        <th class="text-center">Details</th>
                                        <th class="text-center notexport">Image</th>
                                        <th class="text-center">Accounts Payable</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center notexport">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <!-- Data Table area End-->
                </div>
            </div>
        </div>
    </div>

@endsection



@push('script')
    <link href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <script>
        $(document).ready(function () {
            $(".alert").delay(5000).slideUp(300);
            $.ajaxSetup({
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
            });
            var supplierDataTable =   $('#supplierDataTable').DataTable({
                theme: 'bootstrap4',
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('admin.supplier.ajax')}}',
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
                    {data: 'email', name: 'email'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'address', name: 'address'},
                    {data: 'details', name: 'details'},
                    {data: 'image', name: 'image'},
                    {data: 'supplier_account.balance', name: 'supplier_account.balance'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: 'N/A'
                }],
            });

            $('#search').on('click', function(e) {
                supplierDataTable.draw();
                e.preventDefault();
            });

            $("table").on('click', '#ActiveUnactive', function () {
                var id = $(this).attr('data_id');
                var getStatus = $(this).attr('statusCode');
                var setStatus = (getStatus > 0) ? 0 : 1;
                $.ajax({
                    url: "{{ route('admin.supplier.statusActiveUnactive') }}",
                    type: "get",
                    data: {setStatus: setStatus, id: id},
                    success: function (res) {
                        if (res === 'true') {
                            // $(".supplierDataTable").load(location.href + " .supplierDataTable");
                            var supplierDataTable = $('#supplierDataTable').dataTable();
                            supplierDataTable.fnDraw(false);
                            toastr.success('Supplier status updated success');
                        } else {
                            toastr.success('Not found !');
                        }
                    }
                })
            })
        });

        // delete vendor
        function deleteSupplier(id) {
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
                    toastr.success('Supplier deleted success');
                    event.preventDefault();
                    document.getElementById('supplierDeleteForm-' + id).submit();
                }
            })
        };
    </script>

@endpush
