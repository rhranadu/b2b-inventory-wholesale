@extends('layouts.crud-master')
@section('title', 'Product Create')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond/css/filepond.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond/css/filepond-plugin-image-preview.css') }}">
    <style>
        .filepond--item {
            width: calc(20% - .2em);
        }
        /*.filepond--root {*/
        /*    max-height: 10em;*/
        /*}*/
    </style>
@endpush

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
                            <form method="POST" action="{{ route('admin.product.store') }}" accept-charset="UTF-8"
                                  enctype="multipart/form-data" id="product_image_upload">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Product Name <span
                                                    style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                            <input class="form-control" id="name" value="{{ old('name') }}" autocomplete="off"
                                                   name="name" type="text">
                                            @error('name')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="slug">Slug<span
                                                    style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                            <input class="form-control" id="slug" value="{{ old('slug') }}" autocomplete="off"
                                                   name="slug" type="text">
                                            @error('slug')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="product_model">Product Model </label>
                                            <input class="form-control" id="product_model" value="{{ old('product_model') }}"
                                                   autocomplete="off" name="product_model" type="text">
                                            @error('product_model')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="sku">Sku </label>
                                            <input class="form-control" id="sku" value="{{ old('sku') }}"
                                                   autocomplete="off" name="sku" type="text">
                                            @error('sku')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="min_price">Selling Price(Min.) <span
                                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                                    <input class="form-control" id="min_price" value="{{ old('min_price') }}"
                                                           autocomplete="off" name="min_price" type="text">
                                                    @error('min_price')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="max_price">Selling Price(Max.) <span
                                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                                    <input class="form-control" id="max_price" value="{{ old('max_price') }}"
                                                           autocomplete="off" name="max_price" type="text">
                                                    @error('max_price')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tax_id">Tax </label>
                                            <select class="form-control" id="tax_id" name="tax_id">
                                                <option value="">Select Tax (%)</option>
                                                @foreach($taxes as $tax)
                                                    <option
                                                        value="{{ $tax->id }}" {{ old("tax_id") == $tax->id ? "selected" : "" }}>{{ $tax->percentage }}</option>
                                                @endforeach
                                            </select>
                                            @error('tax_id')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="product_category_id">Category <span style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                            @if($preCategory)
                                                <input class="form-control" value="{{ $preCategory }}" autocomplete="off"
                                                       name="product_category_id" type="hidden">
                                                <select class="form-control" disabled="disabled" id="product_category_id" name="">
                                                    <option value=""> Select Category</option>
                                                    @foreach($categories as $category)
                                                        <option
                                                            value="{{ $category->id }}" {{ $preCategory == $category->id ? "selected" : "" }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select class="form-control" id="product_category_id" name="product_category_id">
                                                    <option value=""> Select Category</option>
                                                    @foreach($categories as $category)
                                                        <option
                                                            value="{{ $category->id }}" {{ old("product_category_id") == $category->id ? "selected" : "" }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                            @error('product_category_id')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="product_brand_id">Brand <span
                                                    style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                            <select class="form-control" id="product_brand_id" name="product_brand_id">
                                                <option value=""> Select Brand</option>
                                                @foreach($brands as $brand)
                                                    <option
                                                        value="{{ $brand->id }}" {{ old("product_brand_id") == $brand->id ? "selected" : "" }}>{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('product_brand_id')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="manufacturer_id">Manufacturer <span
                                                    style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                            <select class="form-control" id="manufacturer_id" name="manufacturer_id">
                                                <option value=""> Select Manufacturer</option>
                                                @foreach($manufactureres as $manufacturere)
                                                    <option
                                                        value="{{ $manufacturere->id }}" {{ old("manufacturer_id") == $manufacturere->id ? "selected" : "" }}>{{ $manufacturere->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('manufacturer_id')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="alert_quantity">Reorder Quantity<span
                                                    style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                            <input class="form-control" id="alert_quantity" value="{{ old('alert_quantity') }}"
                                                   autocomplete="off" name="alert_quantity" type="text">
                                            @error('alert_quantity')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                        {{--<div class="form-group">
                                            <label for="product_attribute_id">Attribute <span style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                            <select class="form-control" id="product_attribute_id" name="product_attribute_id">
                                                <option value=""> Select Attribute</option>
                                                @foreach($attributes as $attribute)
                                                    <option value="{{ $attribute->id }}" {{ old("product_attribute_id") == $attribute->id ? "selected" : "" }}>{{ $attribute->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('product_attribute_id')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>--}}
                                        {{--<div class="form-group">
                                            <label for="productmttributemap_id">Attribute Map <span style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                            <select class="form-control" id="productmttributemap_id" name="productmttributemap_id">
                                                <option value=""> Select Attribute Map</option>
                                                @foreach($attribute_maps as $attribute_map)
                                                    <option value="{{ $attribute_map->id }}" {{ old("productmttributemap_id") == $attribute_map->id ? "selected" : "" }}>{{ $attribute_map->value }}</option>
                                                @endforeach
                                            </select>
                                            @error('productmttributemap_id')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>--}}





                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="product_details">Product Details</label>
                                            <textarea class="d-none" name="product_details" id="product_details" >{{ old('product_details') }}</textarea>

                                            {{--                                            <textarea class="form-control date" id="product_details"--}}
                                            {{--                                                      name="product_details">{{ old('product_details') }}</textarea>--}}
                                            @error('product_details')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-4 align-self-center">
                                        <div class="form-group">
                                            <div class="checkbox-inline">
                                                <label class="checkbox checkbox-outline checkbox-success">
                                                    <input
                                                        value="1" type="checkbox" id="addedItemCheckbox" name="status"
                                                        class="i-checks">
                                                    <span></span>
                                                    Status
                                                </label>
                                            </div>
                                            @error('status')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input
                                                value="" type="checkbox" id="product_specifications_chkbox" name=""
                                                class="i-checks product_specifications">
                                            <span></span>
                                            Product Specifications
                                        </label>
                                    </div>
                                </div>
                                <div class="row add_product_specifications_section" style="display: none">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-highlight">
                                            <thead>
                                            <tr>
                                                <th width="100">Label</th>
                                                <th width="50">Value</th>
                                                <th width="50"></th>
                                            </tr>
                                            </thead>
                                            <tbody id="appendNewProductSpecificationsSection">
                                            <tr>
                                                <td class="label_section">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control label_name" id="label_name">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="value_section">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control label_value" id="label_value" >
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" id="addNewProductSpecificationBtn" class="btn btn-success font-weight-bold"><i
                                                            class="fa fa-plus-circle"></i>Add Into List</button>
                                                </td>

                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="image">Image(1200*1200, First Image Will be the Original Image) <span
                                                    style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                            <input type="file"
                                                   class="filepond"
                                                   name="image[]"
                                                   multiple
                                                   data-allow-reorder="true"
                                                   data-max-file-size="2MB"
                                                   data-max-files="10"
                                                   id="filepondImage">
                                            {{--                                        @error('image')--}}
                                            {{--                                        <strong class="text-danger" role="alert">--}}
                                            {{--                                            <span>{{ $message }}</span>--}}
                                            {{--                                        </strong>--}}
                                            {{--                                        @enderror--}}
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="productSubmit" style="background: #00c292; color: #f0f0f0" class="btn waves-effect">Save
                                    Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Container-->
    </div>
    <!--begin::Entry-->


@endsection

@push('script')
    <!-- include FilePond library -->
    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-image-preview.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-file-validate-type.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-image-validate-size.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-image-exif-orientation.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-file-validate-size.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-file-encode.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond.jquery.js') }}"></script>

    <script src="//cdn.ckeditor.com/4.15.1/full/ckeditor.js"></script>


    <script>
        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
        };
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.config.htmlEncodeOutput = false;
        CKEDITOR.config.basicEntities = false;
        CKEDITOR.replace('product_details',options);
        $(document).off('click', '#product_specifications_chkbox').on('click', '#product_specifications_chkbox', function () {

            var  ischecked = $("#product_specifications_chkbox").is(":checked");
            if (ischecked) {
                $('.add_product_specifications_section').show();
            }
            if (!ischecked) {
                $('.add_product_specifications_section').hide();
                $('.everyNewSingleProductSpecificationSection').remove();
            }
        });
        // start===> finaly add new item in our collection
        $(document).off('click', '#addNewProductSpecificationBtn').on('click', '#addNewProductSpecificationBtn', function (e) {
            e.preventDefault();
            var label_name = $('#label_name').val();
            var label_value = $('#label_value').val();
            if (label_name && label_value) {
                var tbl = '\n' +
                    '<tr id="removeThisItem" class="everyNewSingleProductSpecificationSection">\n' +
                    '     <td>\n' +
                    '         <span for="" >' + label_name + '</span>\n' +
                    '         <input type="hidden" class="uniqueProductSpecification_id" data-addedProductSpecification_id="' + label_name + '" name="store_label_name[]" value="' + label_name + '">\n' +
                    '     </td>\n' +
                    '     <td>\n' +
                    '         <span for="">' + label_value + '</span>\n' +
                    '          <input type="hidden" name="store_label_value[]" value="' + label_value + '">\n' +
                    '     </td>\n' +
                    '     <td style="padding-top: 9px;">\n' +
                    '         <a href="#0" id="removeThis" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>\n' +
                    '     </td>\n' +
                    '</tr>';
                $("#appendNewProductSpecificationsSection").append(tbl);
                $("#label_name").val('');
                $("#label_value").val('');

            } else {
                toastr.error('Please Fill Up all field with valid value')
            }

        });


        // remove item with calculation
        $(document).on("click", "#removeThis", function () {
            $(this).parents('#removeThisItem').remove();
        });


        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            document.getElementById("name").value = localStorage.getItem("HiddenProductSearchVal");

            FilePond.registerPlugin(
                FilePondPluginImagePreview,
                FilePondPluginImageExifOrientation,
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType,
                FilePondPluginImageValidateSize,
                FilePondPluginFileEncode
            );


            const inputElement = document.querySelector('input[type="file"]');
            const pond = FilePond.create( inputElement , {
                allowMultiple: true,
                allowFileEncode: true,
                acceptedFileTypes: ['image/jpeg','image/jpg','image/png','image/bmp'],
                allowFileSizeValidation: true,
                allowImageValidateSize: true,
                maxFileSize: '2MB',
                maxFiles: 10,
                // imageValidateSizeMaxWidth	: 1200,
                // imageValidateSizeMaxHeight	: 1200,
            });
            pond.on('warning', (error, file) => {
                if(error.body === "Max files"){
                    Swal.fire({
                        text: "Sorry, You have uploaded more than 10 files",
                        icon: "error",
                        confirmButtonText: "Ok, got it!",
                    })
                }
            });

            $( "#productSubmit" ).click(function( event ) {
                var min_price = $("#min_price").val();
                var max_price = $("#max_price").val();
                if(parseFloat(max_price)  < parseFloat(min_price)){
                    toastr.error('Minimum price can not be greater than Maximum price!');
                    return;
                }else{
                    localStorage.removeItem("HiddenProductSearchVal");
                    $('#product_image_upload').submit();
                }
            });


            // End of multiple image

            // Jquery Slug from product name
            $("#name").keyup(function(){
                var nameText = $("#name").val();
                var trimmed = $.trim(nameText);
                var slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
                replace(/-+/g, '-').
                replace(/^-|-$/g, '');
                nameText = slug.toLowerCase();
                $("#slug").val(nameText);
            });

            if (!isEmpty( $("#name").val() )) {
                var nameText = $("#name").val();
                var trimmed = $.trim(nameText);
                var slug = trimmed.replace(/[^a-z0-9-]/gi, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
                nameText = slug.toLowerCase();
                $("#slug").val(nameText);
            }


            // old value catch
            @if(old('vendor_id') > 0)
            $("#vendor_id").val("{!! old('vendor_id') !!}");

            $("#vendor_id").trigger('change'); // autometic run
            updateDynamicField($("#vendor_id"));
            @endif

            $("#vendor_id").change(function () {
                var vendor_id = $(this).val();
                if (vendor_id) {
                    updateDynamicField(this);
                }
            });


            function updateDynamicField(element) {
                if ($(element).val() !== '') {
                    var vendor_id = $(element).val();
                    $.get("{{ route('admin.get.vendorwise.product.component') }}", {vendor_id: vendor_id}, function (res) {
                        $("#tax_id").html(res.tax);
                        $("#product_category_id").html(res.category);
                        $("#product_brand_id").html(res.brand);
                        $("#manufacturer_id").html(res.manufacturer);
                        $("#product_attribute_id").html(res.pattribute);
                        $("#productmttributemap_id").html(res.pattribute_maps);
                    });
                }
            }

            // old value catch
            @if(old('product_attribute_id') > 0)
            $("#product_attribute_id").val("{!! old('product_attribute_id') !!}");
            $("#product_attribute_id").trigger('change'); // autometic run
            updateDynamicAttributeField($("#product_attribute_id"));
            @endif

            $("#product_attribute_id").change(function () {
                var product_attribute_id = $(this).val();
                if (product_attribute_id) {
                    updateDynamicAttributeField(this);
                }
            });

            function updateDynamicAttributeField(element) {
                if ($(element).val() !== '') {
                    var product_attribute_id = $(element).val();
                    $.get("{{ route('admin.get.vendorwise.product.component') }}", {product_attribute_id: product_attribute_id}, function (res) {
                        $("#productmttributemap_id").html(res.pattribute_maps);

                        // old value catch
                        @if(old('productmttributemap_id') > 0)
                        $("#productmttributemap_id").val("{!! old('productmttributemap_id') !!}");
                        $("#productmttributemap_id").trigger('change'); // autometic run
                        @endif
                    });
                }
            }
        });





    </script>
@endpush
