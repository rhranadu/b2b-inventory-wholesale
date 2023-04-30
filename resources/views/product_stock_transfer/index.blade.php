@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Product Stock Transfer')
@section('main_content')


    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px">
                <div class="card-body">
                    @include('component.message')
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form
                                method="POST" action="{{ route('admin.product_stock_transfer.store') }}" accept-charset="UTF-8"
                                enctype="multipart/form-data" id="submitStockTransferForm">
                                @csrf
                                <div class="form-element-list">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ">
                                                <div class="bootstrap-select">
                                                    <label>Warehouse Name(From)<span
                                                            style="color: red; font-size: 10px;">
                                                    <sup>*</sup>
                                                    </span>
                                                    </label>
                                                    <select name="from_warehouse_id" id="from_warehouse_id" class="selectpicker form-control" data-live-search="true">
                                                        <option value="">*Select warehouse</option>
                                                        @foreach($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}" data-type="{{ $warehouse->type }}">{{ $warehouse->name }}</option>
                                                        @endforeach
                                                    </select><br/>
                                                    @error('from_warehouse_id')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-map"></i>
                                                </div>
                                                <div class=" ">
                                                    <label>Warehouse Detail (From)<span
                                                            style="color: red; font-size: 10px;">
                                                    <sup></sup></label>
                                                    <select name="from_warehouse_detail_id" id="from_warehouse_detail_id" class=" form-control" >
                                                        <option value="">Please Select Warehouse Details</option>
                                                    </select>
                                                    @error('from_warehouse_detail_id')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ">
                                                <div class="nk-int-st">
                                                    <label>Warehouse Type (From)</label>
                                                    <input type="text" name="warehouse_type_name_from" id="warehouse_type_name_from" value="{{ old('name') }}" class="form-control" placeholder="Warehouse Type Name" readonly>
                                                    @error('warehouse_type_name_from')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-map"></i>
                                                </div>
                                                <div class=" ">
                                                    <label>Warehouse Name(To)<span
                                                            style="color: red; font-size: 10px;">
                                                    <sup>*</sup>
                                                    </span>
                                                    </label>
                                                    <select name="to_warehouse_id" id="to_warehouse_id" class=" form-control" >
                                                        <option value="">*Select warehouse</option>
                                                    </select>
                                                    @error('to_warehouse_id')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror

                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-map"></i>
                                                </div>
                                                <div class=" ">
                                                    <label>Warehouse Detail (To)<span
                                                            style="color: red; font-size: 10px;">
                                                    <sup></sup></label>
                                                    <select name="to_warehouse_detail_id" id="to_warehouse_detail_id" class=" form-control" >
                                                        <option value="">Please Select Warehouse Details</option>
                                                    </select>
                                                    @error('to_warehouse_detail_id')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ">
                                                <div class="nk-int-st">
                                                    <label>Warehouse Type (To)</label>
                                                    <input type="text" name="warehouse_type_name_to" id="warehouse_type_name_to" value="{{ old('name') }}" class="form-control" placeholder="Warehouse Type Name" readonly>
                                                    @error('warehouse_type_name_to')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-map"></i>
                                                </div>
                                                <div class=" ">
                                                    <label>Products Name<span
                                                            style="color: red; font-size: 10px;">
                                                    <sup>*</sup>
                                                    </span>
                                                    </label>
                                                    <select name="stock_details_id" id="stock_details_id" class=" form-control" >
                                                        <option value="">*Select Product Name</option>
                                                    </select>
                                                    @error('stock_details_id')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-mail"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label>Quantity<span
                                                            style="color: red; font-size: 10px;">
                                                    <sup>*</sup>
                                                    </span></label>
                                                    <input
                                                        type="number" name="quantity" value="{{ old('quantity') }}" class="form-control"
                                                        placeholder="*Quantity" min="0">
                                                    @error('quantity')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label>Memo No.</label>
                                                    <input
                                                        type="text" name="memo_no" value="{{ old('memo_no') }}" class="form-control"
                                                        placeholder="*Memo No." >
                                                    @error('memo_no')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <button type="button" onclick="submitStockTransfer();" class="btn btn-success waves-effect">Submit</button>
                            </form>

                            <hr>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-condensed stockTransferDataTable"
                                           id="stockTransferDataTable">
                                        <thead>
                                        <tr>
                                            <th class="text-center">SI</th>
                                            <th class="text-center">Product Name</th>
                                            <th class="text-center">Warehouse(From)</th>
                                            <th class="text-center">Warehouse(To)</th>
                                            <th class="text-center">Quantity</th>
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
        var stockTransferDataTable =   $('#stockTransferDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('admin.product_stock_transfer.ajax')}}',
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
                {data: 'products.name', name: 'products.name'},
                {data: 'from_warehouse.name', name: 'warehouse_from.name'},
                {data: 'to_warehouse.name', name: 'warehouse_to.name'},
                {data: 'delivery_quantity', name: 'delivery_quantity'},
            ],
            columnDefs: [{
                targets: '_all',
                defaultContent: 'N/A'
            }],
        });

        $('#search').on('click', function(e) {
            stockTransferDataTable.draw();
            e.preventDefault();
        });
        $("#from_warehouse_id").change(function () {
            var id = $(this).val();
            if(id != ''){
                var request = $.ajax({
                    url: 'product_stock_transfer/warehouse_type/'+id,
                    dataType: 'json',
                    type: 'GET',
                    // success: function (response) {
                    //     console.log(response)
                    //     $("#warehouse_type_name").val(response)
                    // }
                });
                request.done(function (response) {
                    $("#warehouse_type_name_from").val(response.warehouse);
                    var to_warehouses = response.to_warehouses;
                    $("#to_warehouse_id").empty();
                    var output_two = '<option value="">Please Select Warehouse</option>';
                    $.each(to_warehouses, function (index, to_warehouse) {
                        var id = to_warehouse['id'];
                        var name = to_warehouse['name'];
                        output_two += '<option value="'+id+'" >'+name+'</option>';
                     });
                    $("#to_warehouse_id").append(output_two);

                    var products = response.products;
                    $("#stock_details_id").empty();
                    var output_three = '<option value="">Please Select Stock</option>';
                    $.each(products, function (index, product) {
                        var id = product['id'];
                        var name = product['product_name'];
                        output_three += '<option value="'+id+'" >'+name+'</option>';
                     });
                    $("#stock_details_id").append(output_three);

                    var parent_sections = response.parent_sections;
                    $("#from_warehouse_detail_id").empty();
                    var output_one = '<option value="">No Warehouse Details</option>';
                    $.each(parent_sections, function (index, parent_section) {
                        var id = parent_section['id'];
                        var name = parent_section['section_name'];
                        output_one += '<option value="'+id+'">'+name+'</option>';
                    });
                    $("#from_warehouse_detail_id").append(output_one);
                });
            }
        });
        $("#from_warehouse_detail_id").change(function () {
            var warhouseFromId = $("#from_warehouse_id").val();
            var warhouseFromDetailId = $("#from_warehouse_detail_id").val();
            if(warhouseFromId != ''){
                var request = $.ajax({
                    url: 'product_stock_transfer/warehouse_type/'+warhouseFromId + '/' + warhouseFromDetailId,
                    dataType: 'json',
                    type: 'GET',
                    // success: function (response) {
                    //     console.log(response)
                    //     $("#warehouse_type_name").val(response)
                    // }
                });
                request.done(function (response) {
                    var products = response.products;
                    $("#stock_details_id").empty();
                    var output_three = '<option value="">Please Select Stock</option>';
                    $.each(products, function (index, product) {
                        var id = product['id'];
                        var name = product['product_name'];
                        output_three += '<option value="'+id+'" >'+name+'</option>';
                     });
                    $("#stock_details_id").append(output_three);
                });
            }
        });

        $("#to_warehouse_id").change(function () {
            var warhouseToId = $("#to_warehouse_id").val();
            if(warhouseToId != ''){
                var request = $.ajax({
                    url: 'product_stock_transfer/warehouse_type/'+warhouseToId,
                    dataType: 'json',
                    type: 'GET',
                });
                request.done(function (response) {
                    console.log('response: ', response);
                    $("#warehouse_type_name_to").val(response.warehouse);
                    var parent_sections = response.parent_sections;
                    $("#to_warehouse_detail_id").empty();
                    var output_one = '<option value="">No Warehouse Details</option>';
                    $.each(parent_sections, function (index, parent_section) {
                        var id = parent_section['id'];
                        var name = parent_section['section_name'];
                        output_one += '<option value="'+id+'">'+name+'</option>';
                    });
                    $("#to_warehouse_detail_id").append(output_one);
                });

            }
        });
        function submitStockTransfer() {
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
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    $('#submitStockTransferForm').submit();
                }
            })
        }
    </script>
@endpush
