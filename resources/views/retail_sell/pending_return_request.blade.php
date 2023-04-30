@extends('layouts.ajax')

<!--begin::Entry-->

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
                        <th class="text-center">Request Type</th>
                        <th class="text-center">Reason</th>
                        <th class="text-center">Comment</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product_return as $item)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->returned_stocked_product_barcode }}</td>
                        <td>
                            {{ $item->request_type }}
                        </td>
                        <td>{{ $item->reason }}</td>
                        <td>{{ $item->comment }}</td>
                        <td>
                            {{ $item->status }}
                        </td>
                        <td>
                            <a href="#"
                            class="btn btn-sm btn-icon btn-success return-detail"
                            data-toggle="tooltip" data-placement="auto" title="View"
                            data-original-title="View" data-key="{{ $item->id }}"
                            data-rtn-barcode="{{ $item->returned_stocked_product_barcode }}">
                            <i class="fa fa-eye"></i></a>

                            <a href="#"
                            class="btn btn-sm btn-icon btn-danger delete-return-request"
                            data-key="{{ $item->id }}" data-toggle="tooltip"
                            data-placement="auto" title="DELETE" data-original-title="DELETE">
                            <i class="fa fa-trash"></i></a>
{{--                            <a href="#"--}}
{{--                            class="btn btn-sm btn-icon btn-warning edit-return-request"--}}
{{--                            data-key="{{ $item->id }}" data-toggle="tooltip"--}}
{{--                            data-placement="auto" title="EDIT" data-original-title="EDIT">--}}
{{--                            <i class="fa fa-pencil"></i></a>--}}

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>

    var productReturnDataTable = $('#product_return_datatable').DataTable({
        serverSide: false,
        dom: 'Blfrtip',
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
    });
    $('#product_return_datatable').on('draw.dt', function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
