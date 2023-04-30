@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Payment Methods')

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
                            <table
                                class="table table-hover table-bordered table-condensed shippingMethodDataTable"
                                id="shippingMethodDataTable">
                                <thead>
                                <tr>
                                    <th class="text-center">SI</th>
                                    <th class="text-center">Payment type</th>
                                    <th class="text-center">Descriptions</th>
                                    <th class="text-center">logo</th>
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
            var shippingMethodDataTable =   $('#shippingMethodDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('admin.payment_methods.ajax')}}',
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
                    {data: 'payment_type', name: 'payment_type'},
                    {data: 'descriptions', name: 'descriptions'},
                    {data: 'logo', name: 'logo'},
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
                    url: "{{ route('admin.payment_methods.statusActiveUnactive') }}",
                    type: "get",
                    data: {setStatus: setStatus, id: id},
                    success: function (res) {
                        if (res === 'true') {
                            // $(".shippingMethodDeatilsTable").load(location.href + " .shippingMethodDeatilsTable");
                            var shippingMethodDataTable = $('#shippingMethodDataTable').dataTable();
                            shippingMethodDataTable.fnDraw(false);
                            toastr.success('Payment Method status updated success');
                        } else {
                            toastr.error('Not found !');
                        }
                    }
                })
            })
        });


        // delete warehouse
        function deleteShippingMethod(id) {
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
                    document.getElementById('deleteShippingMethodForm-' + id).submit();
                }
            })
        };


    </script>




@endpush
