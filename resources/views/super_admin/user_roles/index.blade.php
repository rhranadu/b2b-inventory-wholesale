@extends('layouts.app')
@include('component.dataTable_resource')
@section('title', 'User Role')
@push('css')
@endpush


@section('main_content')
    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-body">
            <div class="{{ Session('breadcomb_container') }}">
                <div class="normal-table-list">
                    <div class="bsc-tbl">
                        @include('component.message')
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-condensed userRoleDataTable"
                                       id="userRoleDataTable">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SI</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Type</th>
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
@endsection

@push('script')

    <script>
        $(".alert").delay(5000).slideUp(300);

        $.ajaxSetup({
            headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
        });
        var userRoleDataTable =   $('#userRoleDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('superadmin.user_role.ajax')}}',
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
                {data: 'description', name: 'description'},
                {data: 'user_type.table_name', name: 'user_type.table_name'},
                {data: 'action', name: 'action'},
            ],
            columnDefs: [{
                targets: '_all',
                defaultContent: 'N/A'
            }],

        });

        // delete vendor
        function deleteUserRole(id) {
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
                    document.getElementById('deleteUserRoleForm-' + id).submit();
                }
            })
        };

    </script>

@endpush
