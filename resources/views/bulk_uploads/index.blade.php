@extends('layouts.crud-master')
@section('title', 'Bulk Uploads')

@push('css')
    <style>
        .example-wrapper {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        #myGrid {
            flex: 1 1 0px;
            width: 100%;
        }
    </style>
@endpush


@section('main_content')
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    @include('component.message')
                    <form id="bulk_file"
                          method="POST" accept-charset="UTF-8"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card card-custom gutter-b">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label font-weight-bolder text-dark">
                                        Choose Product Category
                                        <small></small>
                                    </h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="normal-table-list">
                                    <div class="bsc-tbl">
                                        <div class="row category-parent-element">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <select id="category-1" class="form-control category-dropdown" data-live-search="true">
                                                        <option value=""> Select Categories </option>
                                                        @foreach($parentCategories as $cat)
                                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3" style="display:none">
                                                <div class="form-group">
                                                    <select id="category-2" class="form-control category-dropdown" data-live-search="true">

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3" style="display:none">
                                                <div class="form-group">
                                                    <select id="category-3" class="form-control category-dropdown" data-live-search="true">

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3" style="display:none">
                                                <div class="form-group">
                                                    <select id="category-4" class="form-control category-dropdown" data-live-search="true">

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <ul class="list-inline selected-category-helper" data-live-search="true">
                                                    {{-- <li class="list-inline-item font-weight-boldest">Selected:</li>                     --}}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--                        <div class="form-group row">--}}
                        {{--                            <div class="col-sm-4">--}}
                        {{--                                <label for="tax_id">Tax <span--}}
                        {{--                                        style="color: red; font-size: 20px;"><sup>*</sup></span></label>--}}
                        {{--                                <select class="form-control" id="tax_id" name="tax_id" data-live-search="true">--}}
                        {{--                                    <option value="">Select Tax (%)</option>--}}
                        {{--                                    @foreach($taxes as $tax)--}}
                        {{--                                        <option--}}
                        {{--                                            value="{{ $tax->id }}" {{ old("tax_id") == $tax->id ? "selected" : "" }}>{{ $tax->percentage }}</option>--}}
                        {{--                                    @endforeach--}}
                        {{--                                </select>--}}
                        {{--                                @error('tax_id')--}}
                        {{--                                <strong class="text-danger" role="alert">--}}
                        {{--                                    <span>{{ $message }}</span>--}}
                        {{--                                </strong>--}}
                        {{--                                @enderror--}}
                        {{--                            </div>--}}
                        {{--                            <div class="col-sm-4">--}}
                        {{--                                <label for="product_brand_id">Brand <span--}}
                        {{--                                        style="color: red; font-size: 20px;"><sup>*</sup></span></label>--}}
                        {{--                                <select class="form-control" id="product_brand_id" name="product_brand_id" data-live-search="true">--}}
                        {{--                                    <option value=""> Select Brand</option>--}}
                        {{--                                    @foreach($brands as $brand)--}}
                        {{--                                        <option--}}
                        {{--                                            value="{{ $brand->id }}" {{ old("product_brand_id") == $brand->id ? "selected" : "" }}>{{ $brand->name }}</option>--}}
                        {{--                                    @endforeach--}}
                        {{--                                </select>--}}
                        {{--                                @error('product_brand_id')--}}
                        {{--                                <strong class="text-danger" role="alert">--}}
                        {{--                                    <span>{{ $message }}</span>--}}
                        {{--                                </strong>--}}
                        {{--                                @enderror--}}
                        {{--                            </div>--}}
                        {{--                            <div class="col-sm-4">--}}
                        {{--                                <label for="supplier_id">Supplier <span--}}
                        {{--                                        style="color: red; font-size: 20px;"><sup>*</sup></span></label>--}}
                        {{--                                <select class="form-control" id="supplier_id" name="supplier_id" data-live-search="true">--}}
                        {{--                                    <option value=""> Select Supplier</option>--}}
                        {{--                                    @foreach($suppliers as $supplier)--}}
                        {{--                                        <option--}}
                        {{--                                            value="{{ $supplier->id }}" {{ old("supplier_id") == $supplier->id ? "selected" : "" }}>{{ $supplier->name }}</option>--}}
                        {{--                                    @endforeach--}}
                        {{--                                </select>--}}
                        {{--                                @error('supplier_id')--}}
                        {{--                                <strong class="text-danger" role="alert">--}}
                        {{--                                    <span>{{ $message }}</span>--}}
                        {{--                                </strong>--}}
                        {{--                                @enderror--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="warehouse_id">Warehouse <span
                                        style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                <select class="form-control" id="warehouse_id" name="warehouse_id" data-live-search="true">
                                    <option value=""> Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}" {{ old("warehouse_id") == $warehouse->id ? "selected" : "" }}>{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <label for="#">Warehouse Section<span
                                        style="color: red; font-size: 20px;"><sup></sup></span></label>
                                <select name="warehouse_section" id="warehouseSection" class="form-control" data-live-search="true"></select>
                                @error('warehouse_section')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                            {{--                            <div class="col-sm-4">--}}
                            {{--                                <label for="manufacturer_id">Manufacturer <span--}}
                            {{--                                        style="color: red; font-size: 20px;"><sup>*</sup></span></label>--}}
                            {{--                                <select class="form-control" id="manufacturer_id" name="manufacturer_id" data-live-search="true">--}}
                            {{--                                    <option value=""> Select Manufacturer</option>--}}
                            {{--                                    @foreach($manufactureres as $manufacturere)--}}
                            {{--                                        <option--}}
                            {{--                                            value="{{ $manufacturere->id }}" {{ old("manufacturer_id") == $manufacturere->id ? "selected" : "" }}>{{ $manufacturere->name }}</option>--}}
                            {{--                                    @endforeach--}}
                            {{--                                </select>--}}
                            {{--                                @error('manufacturer_id')--}}
                            {{--                                <strong class="text-danger" role="alert">--}}
                            {{--                                    <span>{{ $message }}</span>--}}
                            {{--                                </strong>--}}
                            {{--                                @enderror--}}
                            {{--                            </div>--}}
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="file">Upload Xlsx File
                                    <span
                                        style="color: red; font-size: 20px;">
                                                <sup>*</sup>
                                            </span>
                                </label>
                                <input
                                    class="form-control" id="file" value="{{ old('file') }}" autocomplete="off"
                                    name="file" type="file">
                                @error('file')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <h4 style="margin-top: 40px">Sample Excel File Download <a href="{{url('SampleExcel.xlsx')}}">here</a></h4>
                            </div>
                        </div>
                        <button type="submit" id="submit" class="btn btn-success waves-effect text-center">Save Data</button>
                        <h4 class="float-right text-center text-danger">Maximum 1000 Row Allowed per File</h4>
                    </form>
                </div>
            </div>
            <div class="example-wrapper">
                <div id="myGrid" class="ag-theme-alpine">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{--    <script src="https://unpkg.com/xlsx-style@0.8.13/dist/xlsx.full.min.js">--}}
    {{--    </script>--}}
    <script src="{{asset('js/xlsx.full.min.js')}}">
    </script>
    <script src="https://unpkg.com/@ag-grid-enterprise/all-modules@25.1.0/dist/ag-grid-enterprise.min.js">
    </script>
    <script>

        $("#category-1").val('');
        const NEW_PRODUCT = {
            categoryStack: {1:'', 2:'', 3:'', 4:''},
            categoryIdStack: {1:'', 2:'', 3:'', 4:''},
            fetchNextCategory: function (el) {
                $("#search-element").show();
                $(el).parent().parent().nextAll().hide();
                let prId = $(el).val();
                let currentCategorySerial = parseInt($(el).attr('id').split('-')[1]);
                NEW_PRODUCT.setCategoryObject(NEW_PRODUCT.categoryIdStack, currentCategorySerial, $(el).find(':selected').val())
                if(!isEmpty($(el).find(':selected').val())){
                    NEW_PRODUCT.setCategoryObject(NEW_PRODUCT.categoryStack, currentCategorySerial, $(el).find(':selected').text())
                } else {
                    NEW_PRODUCT.setCategoryObject(NEW_PRODUCT.categoryStack, currentCategorySerial, '')
                }
                NEW_PRODUCT.setSelectedCategoryHelperText();
                if (isEmpty(NEW_PRODUCT.categoryIdStack[1])) {
                    $("#search-element").hide();
                }
                if (isEmpty(prId)) {
                    return true;
                }

                let nextPlaceholder =
                    `#${$(el).attr('id').split('-')[0]}-${parseInt($(el).attr('id').split('-')[1])+1}`;
                let base = "{!! url('/') !!}";
                let url = base + '/admin/product/categories/' + prId;
                let createUrlWithCategory = base + '/admin/product/create/' + prId;
                $("#text-create-new-product").attr("href", createUrlWithCategory);

                AJAX_HELPER.ajaxLoadCallback(url, function (response) {
                    if ($(nextPlaceholder).length) {
                        $(nextPlaceholder).parent().parent().show();
                        $(nextPlaceholder).val(0);
                        AJAX_HELPER.ajax_dropdown_map(nextPlaceholder, response, 'Select Category');
                    }
                });
            },
            fetchProducts: function () {
                let search = $("#input-product-search").val();
                let data = {
                    searchString: search,
                    category: NEW_PRODUCT.categoryIdStack,
                }
                let base = "{!! url('/') !!}";
                let url = base + '/admin/product/search';
                AJAX_HELPER.ajaxSubmitDataCallback(url, data, 'html', function (htmlResponse) {
                    $("#div-card-product-list").show();
                    $("#div-product-list").html(htmlResponse);
                })
                localStorage.setItem('HiddenProductSearchVal',search);
            },
            setCategoryObject: function (obj, currentCategorySerial, value) {

                $.each(obj, function (i, v) {
                    if ((i > currentCategorySerial) &&  !isEmpty(v)) {
                        obj[i] = '';
                    }
                })
                obj[currentCategorySerial] = value
            },
            setSelectedCategoryHelperText: function(){
                $(".selected-category-helper").html('');
                let selected_category_helper = '';
                if (isEmpty(NEW_PRODUCT.categoryIdStack[1])) {
                    selected_category_helper = '';
                } else {
                    selected_category_helper = `<li class="list-inline-item font-weight-boldest text-danger">Selected:</li>`;
                }
                $.each(NEW_PRODUCT.categoryStack, function (i, v) {
                    if (!isEmpty(v)) {
                        if (i == 1) {
                            selected_category_helper +=
                                `<li class="list-inline-item text-danger">${v}</li>`;
                        } else {
                            selected_category_helper +=
                                `<li class="list-inline-item text-danger">   ➡ ${v}</li>`;
                        }
                    }
                })
                $(".selected-category-helper").html(selected_category_helper);
            }
        }
        $(document).off("change", ".category-dropdown").on("change", ".category-dropdown", function () {
            NEW_PRODUCT.fetchNextCategory(this);
        })


        //AG Grid Start
        var gridOptions = {
            columnDefs: [
                {headerName: "#", valueGetter: "node.rowIndex + 1", width: 10 },
                { field: "name", minWidth: 180 },
                { field: "product_model", minWidth: 150  },
                { field: "sku", minWidth: 100 },
                { field: "product_details", minWidth: 180 },
                { field: "status" , minWidth: 100},
                { field: "min_price", minWidth: 150 },
                { field: "max_price", minWidth: 150 },
                { field: "alert_quantity", minWidth: 150  },
                { field: "tax", minWidth: 100  },
                { field: "brand", minWidth: 100  },
                { field: "manufacturer", minWidth: 150  },
                { field: "supplier", minWidth: 150  },
                { field: "purchase_quantity", minWidth: 180  },
                { field: "purchase_price", minWidth: 150  },
                { field: "attribute", minWidth: 150  }
            ],

            defaultColDef: {
                resizable: true,
                minWidth: 30,
                flex: 1,
                editable:true
            },
            max_shown_results: 100,
            width: '100%',
            rowsheight: 22,
            pagesize: 25,
            pageable: true,
            autoheight: true,
            sortable: true,
            altrows: true,
            enabletooltips: true,
            filterable: true,
            columnsresize: true,
            showfilterrow: true,
            theme: 'energyblue',
            selectionmode: 'multiplecells',

            rowData: []
        };
        // XMLHttpRequest in promise format
        function makeRequest(method, url, success, error) {
            var httpRequest = new XMLHttpRequest();
            httpRequest.open("GET", url, true);
            httpRequest.responseType = "arraybuffer";

            httpRequest.open(method, url);
            httpRequest.onload = function() {
                success(httpRequest.response);
            };
            httpRequest.onerror = function() {
                error(httpRequest.response);
            };
            httpRequest.send();
        }

        // read the raw data and convert it to a XLSX workbook
        function convertDataToWorkbook(data) {
            /* convert data to binary string */

            var data = new Uint8Array(data);
            var arr = new Array();

            for (var i = 0; i !== data.length; ++i) {
                arr[i] = String.fromCharCode(data[i]);
            }

            var bstr = arr.join("");

            return XLSX.read(bstr, { type: "binary" });
        }

        // pull out the values we're after, converting it into an array of rowData

        function populateGrid(workbook) {
            // our data is in the first sheet
            var firstSheetName = workbook.SheetNames[0];
            var worksheet = workbook.Sheets[firstSheetName];

            // we expect the following columns to be present
            var columns = {
                'A': 'name',
                'B': 'product_model',
                'C': 'sku',
                'D': 'product_details',
                'E': 'status',
                'F': 'min_price',
                'G': 'max_price',
                'H': 'alert_quantity',
                'I': 'tax',
                'J': 'brand',
                'K': 'manufacturer',
                'L': 'supplier',
                'M': 'purchase_quantity',
                'N': 'purchase_price',
                'O': 'attribute'
            };

            var rowData = [];

            // start at the 2nd row - the first row are the headers
            var rowIndex = 2;

            // iterate over the worksheet pulling out the columns we're expecting
            while (worksheet['A' + rowIndex] && rowIndex < 1002) {
                // if (rowIndex > 100){
                //     return
                // }
                var row = {};
                Object.keys(columns).forEach(function(column) {
                    row[columns[column]] = worksheet[column + rowIndex].w;
                });

                rowData.push(row);

                rowIndex++;
            }

            // finally, set the imported rowData into the grid
            gridOptions.api.setRowData(rowData);
        }

        function importExcel() {
            makeRequest('GET',
                'http://b2b-inventory.com/Rocket Advice 2697-18=2679 dt.13.9.20.xlsx',
                function(data) {
                    var workbook = convertDataToWorkbook(data);

                    populateGrid(workbook);
                },
                // error
                function(error) {
                    throw error;
                }
            );
        }

        // wait for the document to be loaded, otherwise
        // AG Grid will not find the div in the document.
        document.addEventListener("DOMContentLoaded", function() {

            // lookup the container we want the Grid to use
            var eGridDiv = document.querySelector('#myGrid');

            // create the grid passing in the div to use together with the columns & data we want to use
            new agGrid.Grid(eGridDiv, gridOptions);
        });

        $(document).ready( function () {
            $("#file").change(function (e) {
                e.preventDefault();
                if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
                    alert('The File APIs are not fully supported in this browser.');
                    return;
                }

                var input = document.getElementById('file');
                if (!input) {
                    alert("Um, couldn't find the fileinput element.");
                } else if (!input.files) {
                    alert("This browser doesn't seem to support the `files` property of file inputs.");
                } else if (!input.files[0]) {
                    alert("Please select a file before clicking 'Load'");
                } else {
                    var file = input.files[0];
                    // importExcel()
                    if (!file) return;
                    KTApp.block('body');
                    var FR = new FileReader();
                    FR.onload = function(e) {
                        var data = new Uint8Array(e.target.result);
                        var workbook = XLSX.read(data, {type: 'array'});
                        populateGrid(workbook);
                    }
                    FR.readAsArrayBuffer(file);
                    KTApp.unblock('body');
                }
            });
            $('#bulk_file #submit').on('click', function (e) {
                e.stopPropagation();
                e.preventDefault();
                // KTApp.block('body');
                KTApp.blockPage({
                    overlayColor: '#000000',
                    state: 'danger',
                    message: 'Please wait some times, backend processing takes some time. ...'
                });
                let rows = [];
                gridOptions.api.forEachNode(node => rows.push(node.data));
                var category_id = 0;
                let tax_id = 0;
                let product_brand_id = 0;
                let manufacturer_id = 0;
                let supplier_id = 0;
                let warehouse_id = 0;
                let warehouse_section = 0;
                if ($('#category-4').val() != null && $('#category-4').val() != 0){
                    category_id = $('#category-4').val();
                }else if ($('#category-3').val() != null && $('#category-3').val() != 0){
                    category_id = $('#category-3').val();
                }else if ($('#category-2').val() != null && $('#category-2').val() != 0){
                    category_id = $('#category-2').val();
                }else if ($('#category-1').val() != null && $('#category-1').val() != 0){
                    category_id = $('#category-1').val();
                }
                // if ($('#tax_id').val() != null){
                //     tax_id = $('#tax_id').val();
                // }
                // if ($('#product_brand_id').val() != null){
                //     product_brand_id = $('#product_brand_id').val();
                // }
                // if ($('#manufacturer_id').val() != null){
                //     manufacturer_id = $('#manufacturer_id').val();
                // }
                // if ($('#supplier_id').val() != null){
                //     supplier_id = $('#supplier_id').val();
                // }
                if ($('#warehouse_id').val() != null){
                    warehouse_id = $('#warehouse_id').val();
                }
                if ($('#warehouseSection').val() != null){
                    warehouse_section = $('#warehouseSection').val();
                }
                $.ajaxSetup({
                    headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
                });
                $.ajax({
                    url: "{{ route('admin.store.sheet.admin') }}",
                    type: "post",
                    data: {row_data: rows,
                        product_category_id:category_id,
                        tax_id:tax_id,
                        product_brand_id:product_brand_id,
                        manufacturer_id:manufacturer_id,
                        supplier_id:supplier_id,
                        warehouse_id:warehouse_id,
                        warehouse_section:warehouse_section},
                    success: function (res) {
                        if (res.status === 'error') {
                            toastr.error(res.message)
                        } else {
                            toastr.success(res.data);
                            // setTimeout(function(){
                            {{--    window.location.href = '{{url('admin/product')}}';--}}
                            // }, 3000)
                        }
                    }
                })
                // KTApp.unblock('body');
                KTApp.unblockPage();
            });

            $(document).off('#change', '#warehouse_id').on('change', '#warehouse_id', function () {
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
                        return {search: params.term, warehouse_id: $("#warehouse_id :selected").val()};
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

        });
    </script>


@endpush
