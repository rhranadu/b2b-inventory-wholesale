@extends('layouts.app')
@include('component.dataTable_resource')
@section('title', 'Vendors')

@push('css')
@endpush


@section('main_content')

    <div class="card card-custom min-h-500px" id="kt_card_1">

        <div class="card-body">

            @include('component.message')
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-condensed vendorDataTable" id="vendorDataTable">
                        <thead>
                        <tr>
                            <th class="text-center">SI</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Website</th>
                            <th class="text-center">Address</th>
                            <th class="text-center notexport">Logo</th>
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
@endsection

@push('script')
    <script>
        $().ready(function () {
            $(".alert").delay(5000).slideUp(300);
            $.ajaxSetup({
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
            });
            var vendorDataTable =   $('#vendorDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('superadmin.vendor.ajax')}}',
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
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'website', name: 'website'},
                    {data: 'address', name: 'address'},
                    {data: 'image', name: 'image'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: 'N/A'
                }],

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
                            var vendorDataTable = $('#vendorDataTable').dataTable();
                            vendorDataTable.fnDraw(false);
                            toastr.success(res.msg);
                        } else {
                            toastr.error(res.msg);
                        }
                    }
                })
            })
        });


        // delete Vendor
        function deleteVendor(id) {
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
                    toastr.success('Vendor deleted success');
                    event.preventDefault();
                    document.getElementById('deleteVendorForm-' + id).submit();
                }
            })
        };


    </script>
@endpush
