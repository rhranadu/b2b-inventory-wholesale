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
                            <form method="POST" action="{{ route('superadmin.parent_product.store') }}" accept-charset="UTF-8"
                                  enctype="multipart/form-data" id="product_image_upload">
                                @csrf
                                <input type="hidden" name="parenttype">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Product Name <span
                                                    style="color: red; font-size: 20px;">*</span></label>
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
                                                    style="color: red; font-size: 20px;">*</span></label>
                                            <input class="form-control" id="slug" value="{{ old('slug')}}" autocomplete="off"
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
                                            <label for="qr_code">Qr Code<span
                                                    style="color: red; font-size: 20px;"></span></label>
                                            <input class="form-control" id="qr_code" value="{{ old('qr_code') }}"
                                                   autocomplete="off" name="qr_code" type="text">
                                            @error('qr_code')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="sku">Sku<span
                                                    style="color: red; font-size: 20px;"></span></label>
                                            <input class="form-control" id="sku" value="{{ old('sku') }}"
                                                   autocomplete="off" name="sku" type="text">
                                            @error('sku')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="product_category_id">Product Category <span style="color: red; font-size: 20px;">*</span></label>
                                                <select class="form-control" id="product_category_id" name="product_category_id">
                                                    <option value=""> Select Category</option>
                                                    @foreach($categories as $category)
                                                        <option
                                                            value="{{ $category->id }}" {{ old("product_category_id") == $category->id ? "selected" : "" }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            @error('product_category_id')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="product_brand_id">Product Brand <span
                                                    style="color: red; font-size: 20px;">*</span></label>
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
                                            <label for="manufacturer_id">Product Manufacturer <span
                                                    style="color: red; font-size: 20px;">*</span></label>
                                            <select class="form-control" id="manufacturer_id" name="manufacturer_id">
                                                <option value=""> Select Manufacturer</option>
                                                @foreach($manufacturers as $manufacturer)
                                                    <option
                                                        value="{{ $manufacturer->id }}" {{ old("manufacturer_id") == $manufacturer->id ? "selected" : "" }}>{{ $manufacturer->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('manufacturer_id')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="product_details">Product Details</label>
                                            <textarea class="d-none" name="product_details" id="product_details" >{{ old('product_details') }}</textarea>
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
                                                style="color: red; font-size: 20px;"></span></label>
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
                if(max_price < min_price){
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

        });





    </script>
@endpush
