@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Warehouse Stock')

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
                    <div class="row align-items-center">
                        <div class="form-group col-md-4">
                            <label for="#">Warehouse</label>
                            <select name="warehouse" id="warehouse" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-primary mt-7" id="search">Search</button>
                        </div>
                    </div>
                        <div class="table-responsive">
                            <table
                                class="table table-hover table-bordered table-condensed"
                                id="datatable">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Warehouse</th>
                                    <th class="text-center">Stocked Quantity</th>
                                    <th class="text-center">Available Stock</th>
                                    <th class="text-center">Sold Quantity</th>
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
    $(".alert").delay(5000).slideUp(300);
    $.ajaxSetup({
        headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
    });

    $("#warehouse").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.warehouse.list") }}",
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {search: params.term};
            },
            processResults: function (data, params) {
                return {
                    results: $.map(data, function (item,i) {
                        return {id: i, text: item}
                    }),

                };
            },
            cache: true
        },
        placeholder: 'Search for a warehouse',
    });

    var datatable =   $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{route('admin.report.warehouse_wise.stock.ajax')}}',
            type: "POST",
            data: function (d) {
                d.warehouse_id = $('#warehouse :selected').val();
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
            {data: 'warehouse', name: 'warehouse'},
            {data: 'stocked_quantity', name: 'stocked_quantity'},
            {data: 'available_stock', name: 'available_stock'},
            {data: 'sold_quantity', name: 'sold_quantity'},
        ],
        columnDefs: [{
            targets: '_all',
            defaultContent: 'N/A'
        }],
    });

    $('#search').on('click', function(e) {
        datatable.draw();
        e.preventDefault();
    });

</script>
@endpush
