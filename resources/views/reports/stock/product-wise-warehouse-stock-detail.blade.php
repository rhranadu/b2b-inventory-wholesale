@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Product Wise Warehouse Stock')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
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
                            <label for="#">Brand</label>
                            <select name="brand" id="brand" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="#">Products</label>
                            <select name="products" id="productId" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-primary mt-7" id="search">Search</button>
                        </div>
                    </div>
                    <div class="row d-none" id="productName">

                    </div>

                        <div class="table-responsive">
                            <table
                                class="table table-hover table-bordered table-condensed"
                                id="datatable">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
{{--                                    <th class="text-center">Product</th>--}}
                                    <th class="text-center">Warehouse</th>
                                    <th class="text-center">Warehouse Section</th>
                                    <th class="text-center">Stocked Quantity</th>
                                    <th class="text-center">Available Stock</th>
                                    <th class="text-center">Action</th>
{{--                                    <th class="text-center">Sold Quantity</th>--}}
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
    <div id="ModalBarcode" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"
         aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog  modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-link" data-dismiss="modal" onclick="$('#jstree_demo_div').jstree('destroy');">&times;</button>
                    <h4 class="modal-title">Warehouse Section Tree</h4>
                </div>
                <div class="modal-body">
                    <div id="jstree_demo_div"></div>
                </div>
                <div class="modal-footer">
{{--                    <button type="submit" id="save_barcode_data" onclick="" class="btn btn-primary">Save</button>--}}
                    <button type="button" class="btn btn-default close-link" data-dismiss="modal" onclick="$('#jstree_demo_div').jstree('destroy');">Close</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<script>
    $(".alert").delay(5000).slideUp(300);
    $.ajaxSetup({
        headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
    });
    function warehouseSectionJstree (id) {
        $(function () {
            $('#jstree_demo_div').jstree();
        });
        $('#jstree_demo_div').on("changed.jstree", function (e, data) {
            console.log(data.selected);
        });

        {{--$.ajax({--}}
        {{--    url: "{{ route("admin.warehouse-section-tree.ajax") }}",--}}
        {{--    method: 'POST',--}}
        {{--    data: {'id': id},--}}
        {{--    success: function (result) {--}}
                //tdata = result;
        $('#jstree_demo_div').jstree('destroy');
                $('#jstree_demo_div').jstree({
                    'core': {
                        //'data': JSON.parse(result)
                        'data': {
                            url: "{{ route("admin.warehouse-section-tree.ajax") }}",
                            method: 'POST',
                            data: {'id': id},
                            "dataType": "json" // needed only if you do not supply JSON headers
                        }
                    },
                    "plugins": ["wholerow"]
                });
            // }
        // });

        $('#ModalBarcode').modal('show');
    };
    function warehouseJstree (id) {
        $(function () {
            $('#jstree_demo_div').jstree();
        });
        $('#jstree_demo_div').on("changed.jstree", function (e, data) {
            console.log(data.selected);
        });

        {{--$.ajax({--}}
        {{--    url: "{{ route("admin.warehouse-section-tree.ajax") }}",--}}
        {{--    method: 'POST',--}}
        {{--    data: {'id': id},--}}
        {{--    success: function (result) {--}}
                //tdata = result;
        $('#jstree_demo_div').jstree('destroy');
                $('#jstree_demo_div').jstree({
                    'core': {
                        //'data': JSON.parse(result)
                        'data': {
                            url: "{{ route("admin.warehouse-tree.ajax") }}",
                            method: 'POST',
                            data: {'id': id},
                            "dataType": "json" // needed only if you do not supply JSON headers
                        }
                    },
                    "plugins": ["wholerow"]
                });
            // }
        // });

        $('#ModalBarcode').modal('show');
    }

    $("#brand").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.report.brand.Search.ajax") }}",
            dataType: 'json',
            type: 'POST',
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
        placeholder: 'Search for a Brand',
    });

    $("#productId").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.product.dropdown.list") }}",
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    brand_id:$('#brand').val(),
                    search: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            id: item.id,
                            text: item.name,
                        }
                    }),
                    pagination: {
                        more: (params.page * 10) < data.total
                    }
                };
            },
            cache: true
        },
        placeholder: 'Search for a product',
    });

    $("#productId").off('change').on('change', function () {
        var productName = `<h6> Product: `+$('#productId :selected').text()+`</h6>`;
        $("#productName").html("");
        $("#productName").html(productName);
        $("#productName").addClass('d-none').removeClass('d-none');
        $("#productName").css("margin-bottom","15px");
    })
    var datatable =   $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{route('admin.report.product-wise.warehouse.stock.detail.ajax')}}',
            type: "POST",
            data: function (d) {
                d.product_id = $('#productId :selected').val();
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
            //{data: 'product_name', name: 'product_name'},
            {data: 'warehouse_name', name: 'warehouse_name'},
            {data: 'warehouse_section_name', name: 'warehouse_section_name'},
            {data: 'stocked_quantity', name: 'stocked_quantity'},
            {data: 'available_stock', name: 'available_stock'},
            {data: 'action', name: 'action'},
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
