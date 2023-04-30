    <div class="card card-custom min-h-500px">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Create Product
                    <small>Create Product</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.product.index') }}" class="btn btn-sm btn-light-primary"
                   data-card-tool="remove" data-toggle="tooltip" data-placement="top"
                   title="Product List">
                    <i class="fa fa-plus"></i> Product List
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('component.message')
            <div class="normal-table-list">
                <div class="bsc-tbl">
                    <form method="POST" action="{{ route('admin.product.store') }}" accept-charset="UTF-8"
                          enctype="multipart/form-data">
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
                                    <label for="product_model">Product Model <span style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="product_model" value="{{ old('product_model') }}"
                                           autocomplete="off" name="product_model" type="text">
                                    @error('product_model')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="model_no">Model No <span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="model_no" value="{{ old('qr_code') }}"
                                           autocomplete="off" name="model_no" type="text">
                                    @error('model_no')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="qr_code">QR Code <span style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="qr_code" value="{{ old('qr_code') }}"
                                           autocomplete="off" name="qr_code" type="text">
                                    @error('qr_code')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="alert_quantity">Minimum Price <span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="min_price" value="{{ old('min_price') }}"
                                           autocomplete="off" name="min_price" type="text">
                                    @error('min_price')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="alert_quantity">Maximum Price <span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="max_price" value="{{ old('max_price') }}"
                                           autocomplete="off" name="max_price" type="text">
                                    @error('max_price')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="alert_quantity">Alert Quantity <span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="alert_quantity" value="{{ old('alert_quantity') }}"
                                           autocomplete="off" name="alert_quantity" type="text">
                                    @error('alert_quantity')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label for="image">Image <span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input type="file" class="form-control date" id="image" name="image">
                                    @error('image')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="tax_id">Tax <span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
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

                                <div class="form-group">
                                    <label for="product_details">Product Details</label>
                                    <textarea class="form-control date" id="product_details"
                                              name="product_details">{{ old('product_details') }}</textarea>
                                    @error('product_details')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="product_specification">Product Specification</label>
                                    <textarea class="form-control date" id="product_specification"
                                              name="product_specification">{{ old('product_specification') }}</textarea>
                                    @error('product_specification')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <input value="1" type="checkbox" id="addedItemCheckbox" name="status"
                                           class="i-checks">
                                    @error('status')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                            </div>

                        </div>
                        <button type="submit" style="background: #00c292; color: #f0f0f0" class="btn waves-effect">Save
                            Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

{{--@push('script')--}}
    <script src="{{ asset('backend/js/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('backend/js/icheck/icheck-active.js') }}"></script>
    <script>
        $().ready(function () {

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

