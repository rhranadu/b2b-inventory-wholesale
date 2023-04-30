@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Attributes Maps')
@push('css')
    .table th, .table td{vertical-align:inherit;}
@endpush

@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">

            <div class="card card-custom min-h-500px">

                <div class="card-body">
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            @include('component.message')
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-condensed productAttrMapDataTable"
                                           id="productAttrMapDataTable">
                                        <thead>
                                        <tr>
                                            <th class="text-center">SI</th>
                                            <th class="text-center">Attribute Name</th>
                                            <th class="text-center">Value</th>
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
        $(".alert").delay(5000).slideUp(300);
        $.ajaxSetup({
            headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
        });
        var productAttrMapDataTable =   $('#productAttrMapDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('admin.product_attribute_map.ajax')}}',
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
                {data: 'attribute_name.name', name: 'attribute_name.name'},
                {data: 'value', name: 'value'},
                {data: 'action', name: 'action'},
            ],
            columnDefs: [{
                targets: '_all',
                defaultContent: 'N/A'
            }],
        });

        $('#search').on('click', function(e) {
            productAttrMapDataTable.draw();
            e.preventDefault();
        });
        // delete Attribute map
        function deleteAttributeMap(id) {
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
                    toastr.success('Attribute Map deleted success');
                    event.preventDefault();
                    document.getElementById('deleteAttributeMapForm-' + id).submit();
                }
            })
        };
    </script>

@endpush
