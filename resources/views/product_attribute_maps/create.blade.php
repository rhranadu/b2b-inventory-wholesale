@extends('layouts.crud-master')
@section('title', 'Attributes Map Create')
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
                            <form method="POST" action="{{ route('admin.product_attribute_map.store') }}" accept-charset="UTF-8"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="product_attribute_id">Attribute Name<span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                    <select class="form-control" id="product_attribute_id" name="product_attribute_id">
                                        <option value=""> Please Select</option>
                                        @foreach($productattributes as $productattribute)
                                            <option
                                                value="{{$productattribute->id}}" {{ old("product_attribute_id") == $productattribute->id ? "selected" : "" }}>{{ $productattribute->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_attribute_id')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group add_atribute_map_section" >
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-highlight">
                                            <thead>
                                            <tr>
                                                <th width="100">Value</th>
                                                <th width="50"></th>
                                            </tr>
                                            </thead>
                                            <tbody id="appendNewValueSection">
                                            <tr>
                                                <td class="value_section">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control value_name" id="value_name">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" id="addnewValueBtn" class="btn btn-success font-weight-bold"><i
                                                            class="fa fa-plus-circle"></i>Add Into List</button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
{{--                                <div class="form-group">--}}
{{--                                    <label for="value">Value<span style="color: red; font-size: 18px"><sup>*</sup></span></label>--}}
{{--                                    <input class="form-control" id="value" value="{{ old('value') }}" autocomplete="off"--}}
{{--                                           name="value" type="text">--}}
{{--                                    @error('value')--}}
{{--                                    <strong class="text-danger" role="alert">--}}
{{--                                        <span>{{ $message }}</span>--}}
{{--                                    </strong>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}

                                <button type="submit" style="background: #00c292; color: #f0f0f0" class="btn waves-effect">Save
                                    Data
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
        // start===> finaly add new item in our collection
        $(document).off('click', '#addnewValueBtn').on('click', '#addnewValueBtn', function (e) {
            e.preventDefault();
            var value_name = $('#value_name').val();
            if (value_name) {
                var tbl = '\n' +
                    '<tr id="removeThisItem" class="everyNewSingleValueSection">\n' +
                    '     <td>\n' +
                    '         <span for="">' + value_name + '</span>\n' +
                    '         <input type="hidden" class="uniqueValue_id" data-addedValue_id="' + value_name + '" name="value[]" value="' + value_name + '">\n' +
                    '     </td>\n' +
                    '     <td style="padding-top: 9px;">\n' +
                    '         <a href="#0" id="removeThis" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>\n' +
                    '     </td>\n' +
                    '</tr>';
                $("#appendNewValueSection").append(tbl);
                $("#value_name").val('');
            } else {
                toastr.error('Please Fill Up all field with valid value')
            }

        });


        // remove item with calculation
        $(document).on("click", "#removeThis", function () {
            $(this).parents('#removeThisItem').remove();
        });

    </script>

@endpush
