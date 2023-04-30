@extends('layouts.crud-master')
@section('title', 'Attributes Map Edit')
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
                            <form method="POST"
                                  action="{{ route('admin.product_attribute_map.update', $productAttributeMap->id) }}"
                                  accept-charset="UTF-8" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="product_attribute_id">Attribute Name<span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                    <select class="form-control" id="product_attribute_id" name="product_attribute_id">
                                        <option value=""> Please Select</option>
                                        @foreach($productattributes as $productattribute)
                                            <option
                                                value="{{$productattribute->id}}" {{ $productAttributeMap->product_attribute_id == $productattribute->id ? "selected" : "" }}>{{ $productattribute->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_attribute_id')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="value">Value<span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                    <input class="form-control" id="value" value="{{ $productAttributeMap->value }}"
                                           autocomplete="off" name="value" type="text">
                                    @error('value')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <button type="submit" style="background: #00c292; color: #f0f0f0" class="btn waves-effect">
                                    Update Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->

@endsection

@push('script')

    <script>
        $().ready(function () {
            $("#vendorIdChange").change(function () {
                var vendor_id = $(this).val();
                $.get("{{ route('admin.get.product_attribute') }}", {vendor_id: vendor_id}, function (res) {
                    $("#product_attribute_id").html(res);
                });
            });
        });
    </script>

@endpush
