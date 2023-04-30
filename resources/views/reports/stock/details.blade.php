@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Product Stock Detail Report')

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
                        <div class="form-group col-md-2">
                            <label for="#">Start Date</label>
                            <input required name="date" data-date="" data-date-format="DD MMMM YYYY"
                                     type="date" class="form-control"
                                    id="startDate">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="#">End Date</label>
                            <input required name="date" data-date="" data-date-format="DD MMMM YYYY"
                             type="date" class="form-control"
                            id="endDate">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="#">Warehouse</label>
                            <select name="warehouse" id="warehouse" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="#">Warehouse Section</label>
                            <select name="warehouse_section" id="warehouseSection" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="#">Purchase Invoice</label>
                            <select name="invoice" id="invoice" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="#">Supplier</label>
                            <select name="supplier" id="supplier" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="#">Brand</label>
                            <select name="brand" id="brand" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="#">Product</label>
                            <select name="product" id="product" class="form-control" data-live-search="true"></select>
                        </div>
                           <div class="form-group col-md-1">
                            <button class="btn btn-danger mt-7" id="reset_btn" type="reset">Reset</button>
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
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Attribute</th>
                                    <th class="text-center">Warehouse</th>
                                    <th class="text-center">Warehouse Section</th>
                                    <th class="text-center">Stocked Quantity</th>
                                    <th class="text-center">Available Stock</th>
                                    <th class="text-center">Purchase Invoice</th>
                                    <th class="text-center">Purchase Price</th>
                                    <th class="text-center">Purchased At</th>
                                    <th class="text-center">Stock Per Price</th>
                                    <th class="text-center">Stocked At</th>
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
        $(document).off('click', '#reset_btn').on('click', '#reset_btn', function() {
            $("select[name=warehouse]").val("").trigger("change");
            $("select[name=warehouse_section]").val("").trigger("change");
            $("select[name=supplier]").val("").trigger("change");
            $("select[name=brand]").val("").trigger("change");
            $("select[name=product]").val("").trigger("change");
            $("select[name=invoice]").val("").trigger("change");
            $('input[name=date]').val("").trigger("change");
        });
    $(".alert").delay(5000).slideUp(300);
    $.ajaxSetup({
        headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
    });
    $("#product").select2({
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

        $("#invoice").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.purchase.invoices") }}",
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
        placeholder: 'Search for a invoice',
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
    $(document).off('#change', '#warehouse').on('change', '#warehouse', function () {
        $("#warehouseSection").empty().trigger('change')
    })
    $("#warehouseSection").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.products.warehouse_type.check") }}",
            dataType: 'json',
            type: 'post',
            data: function (params) {
                return {search: params.term, warehouse_id: $("#warehouse :selected").val()};
            },
            processResults: function (data, params) {
                return {
                    results: $.map(data.parent_sections, function (item,i) {
                        return {id: item.id, text: item.section_name}
                    }),

                };
            },
            cache: true
        },
        placeholder: 'Search for a warehouse section',
    });
    $("#supplier").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.supplier.list") }}",
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {search: params.term};
            },
            processResults: function (data, params) {
                return {
                    results: $.map(data, function (item, i) {
                        return {id: i, text: item}
                    }),

                };
            },
            cache: true
        },
        placeholder: 'Search for a supplier',
    });

    var datatable =   $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{route('admin.report.stock.detail.ajax')}}',
            type: "POST",
            data: function (d) {
                d.start_date = $('#startDate').val();
                d.end_date = $('#endDate').val();
                d.product_id = $('#product :selected').val();
                d.warehouse_id = $('#warehouse :selected').val();
                d.warehouse_detail_id = $('#warehouseSection :selected').val();
                d.invoice_no = $('#invoice :selected').val();
                d.supplier_id = $('#supplier :selected').val();
                d.brand_id = $('#brand').val();

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
            {data: 'product_name', name: 'product_name'},
            {data: 'attribute', name: 'attribute'},
            {data: 'warehouse', name: 'warehouse'},
            {data: 'warehouse_section', name: 'warehouse_section'},
            {data: 'stocked_quantity', name: 'stocked_quantity'},
            {data: 'available_stock', name: 'available_stock'},
            {data: 'purchase_invoice', name: 'purchase_invoice'},
            {data: 'purchase_price', name: 'purchase_price'},
            {data: 'purchased_at', name: 'purchased_at'},
            {data: 'stock_per_price', name: 'stock_per_price'},
            {data: 'stocked_at', name: 'stocked_at'},
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
