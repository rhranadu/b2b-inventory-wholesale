@extends('layouts.crud-master')
@section('title', 'Purchases Edit')
@push('css')

    <style>
        input[type="date"]::-webkit-datetime-edit, input[type="date"]::-webkit-inner-spin-button, input[type="date"]::-webkit-clear-button {
            color: #fff;
            position: relative;
        }

        input[type="date"]::-webkit-datetime-edit-year-field {
            position: absolute !important;
            border-left: 1px solid #8c8c8c;
            padding: 2px;
            color: #000;
            left: 56px;
        }

        input[type="date"]::-webkit-datetime-edit-month-field {
            position: absolute !important;
            border-left: 1px solid #8c8c8c;
            padding: 2px;
            color: #000;
            left: 26px;
        }


        input[type="date"]::-webkit-datetime-edit-day-field {
            position: absolute !important;
            color: #000;
            padding: 2px;
            left: 4px;

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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>
                                        <strong>Warning!</strong> {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form method="POST" action="{{ route('admin.purchase.update',$purchase->id) }}" accept-charset="UTF-8">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="supplier_id">Supplier</label>
                                        <select required name="supplier_id" id="supplier_id" class="form-control select">
                                            <option value="" selected>Select Supplier...</option>
                                            @foreach($suppliers as $supplier)
                                                <option {{ $purchase->supplier_id == $supplier->id ? 'selected' :'' }} value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                        <p id="autofocusOption"></p>
                                        @error('supplier_id')
                                        <strong class="text-danger" role="alert"><span>{{ $message }}</span></strong>@enderror
                                        <div class="vendor_details">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12" style="margin-top: 25px;">
                                                    <div class="invoice-cmp-ds">
                                                        <div class="invoice-frm">
                                                            <span>Supplier Details</span>
                                                        </div>
                                                        <div class="comp-tl">
                                                            <h2>{{ $purchase->purchaseSupplier->name }}</h2>
                                                            <p>{{ $purchase->purchaseSupplier->mobile }}</p>
                                                            <p>{{ $purchase->purchaseSupplier->email }}</p>
                                                            <p>{{ $purchase->purchaseSupplier->address }}</p>
                                                        </div>
                                                        <div class="cmp-ph-em">
                                                            <p>{{ $purchase->purchaseSupplier->details }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="#">Date</label>
                                            <input required name="date" type="date" value="{{ $purchase->date }}"
                                                   class="form-control" id="Date">
                                            <p id="autofocusDate"></p>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="#">Invoice No</label>
                                            <div class="input-group">
                                                <input type="text" id="invoice_no" name="invoice_no"
                                                value="{{ $purchase->invoice_no }}" required class="form-control">
                                            </div>
                                            <p id="checkInvoice"></p>
                                        </div>
                                    </div>
                                </div>
                                {{--add purchases--}}
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-highlight">
                                            <thead>
                                            <tr>
                                                <th width="100">Product Name</th>
                                                <th width="100">Attribute</th>
                                                <th width="100">Attribute Map</th>
                                                <th width="50">Quantity</th>
                                                <th width="50">Price</th>
                                                <th width="50"></th>
                                            </tr>
                                            </thead>
                                            <tbody id="appendNewItemSection">
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <select id="product_id" class="form-control select">
                                                            <option value="">Select</option>
{{--                                                            @foreach($products as $produnt_id => $product_name)--}}
{{--                                                                <option--}}
{{--                                                                    value="{{ $produnt_id }}">{{ $product_name }}</option>--}}
{{--                                                            @endforeach--}}
                                                            @foreach($products as $product)
                                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td class="attribut_section">
                                                    <div class="form-group">
                                                        <select id="attribute_id" class="form-control attribute_id select">
                                                            <option value="">Select</option>
                                                            @foreach($attributes as $attribute)
                                                                <option
                                                                    value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div id="tag-list" style="display:none"></div>
                                                </td>
                                                <td class="attribut_map_section">
                                                    <div class="form-group">
                                                        <select id="product_attribute_map_id"
                                                                class="form-control product_attribute_map_id select">
                                                            <option value="">Select</option>
                                                            @foreach($attribute_maps as $attribute_map)
                                                                <option
                                                                    value="{{ $attribute_map->id }}">{{ $attribute_map->value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td class="qty_section">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control qty" id="qty">
{{--                                                            <div class="input-group-append" style="cursor:pointer" id="add_multipul_attribute">--}}
{{--                                                                <button class="btn btn-primary btn-icon" type="button" data-toggle="tooltip" title="Add Product Attribute">--}}
{{--                                                                    <i class="fa fa-plus-circle"></i></button>--}}
{{--                                                            </div>--}}
                                                            {{--
                                                                                                                        <div class="input-group-addon"
                                                                                                                             style="background: #b5b2b2; color: #f0f0f0; cursor: pointer">
                                                                                                                            <i class="fa fa-plus-circle"></i></div> --}}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="price_section">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control price" id="price">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" id="addnewItemBtn" class="btn btn-success font-weight-bold"><i
                                                            class="fa fa-plus-circle"></i>Add Into List</button>
                                                </td>
                                            </tr>
                                            @if(!empty($purchase->purchaseDetail))
                                                @foreach($purchase->purchaseDetail as $purchaseDetail)
                                                    <tr id="removeThisItem" class="everyNewSingleItem">
                                                        <td>
                                                            <span for="">{{$purchaseDetail->product->name}}</span>
                                                            <input type="hidden" class="uniqueProduct_id" data-addedproduct_id="1" name="store_product_id[]" value="{{$purchaseDetail->product_id}}">
                                                        </td>
                                                        @php
                                                            $store_attribute_ids = [];
                                                            $store_attribute_map_ids = [];
                                                            $store_attribute_names = '';
                                                            $store_attribute_map_name = '';
                                                            foreach($purchaseDetail->attributeDetail as $attributeDetail){
                                                               $store_attribute_ids[] = $attributeDetail->attribute_id;
                                                               $store_attribute_map_ids[] = $attributeDetail->product_attribute_map_id;
                                                               $store_attribute_names .= $attributeDetail->attribute_name.'<br>';
                                                               $store_attribute_map_name .= $attributeDetail->attribute_map_name.'<br>';
                                                            }
                                                            $store_attribute_ids = implode(',',$store_attribute_ids);
                                                            $store_attribute_map_ids = implode(',',$store_attribute_map_ids);
                                                        @endphp
                                                        <td>
                                                            <span for="">{!! $store_attribute_names !!}</span>
                                                            <input type="hidden" name="store_attribute_id[]" value="{{$store_attribute_ids}}">
                                                        </td>
                                                        <td>
                                                            <span for="">{!! $store_attribute_map_name !!}</span>
                                                            <input type="hidden" data-addedattributemap_id="{{$store_attribute_map_ids}}" class="uniqueAttributemap_id" name="store_product_attribute_map_id[]" value="{{$store_attribute_map_ids}}">
                                                        </td>
                                                        <td>
                                                            <span for="">{{$purchaseDetail->quantity}}</span>
                                                            <input type="hidden" name="store_quantity[]" value="{{$purchaseDetail->quantity}}">
                                                        </td>
                                                        <td>
                                                            <span for="">{{$purchaseDetail->price}}</span>
                                                            <input type="hidden" name="store_price[]" value="{{$purchaseDetail->quantity}}">
                                                        </td>
                                                        <td style="padding-top: 9px;">
                                                            <a href="#0" id="removeThis" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row clearfix" style="margin-top:20px">
                                    <div class="pull-right col-12">
                                        <table class="table table-bordered">
                                            <tbody>
{{--                                            <tr id="tag-list" style="display:none">--}}
{{--                                                <td></td>--}}
{{--                                            </tr>--}}
                                            <tr>
                                                <td class="text-center">
                                                    <button type="submit" class="btn"
                                                            style="background: #00c292; color: #f0f0f0">Update
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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

        $(document).ready(function () {
            $(".select").select2({
                width: '100%',
            });

            // set csrf token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // get vendor id
            $("#supplier_id").change(function () {
                var supplier_id = $(this).val();
                if (supplier_id) {
                    $("#invoice_no").val(''); // to check invoice no for unice
                    $('#supplier_id').css('border', '1px solid #00c292');
                    $.post("{{ route('admin.purchase.supplier.details') }}", {supplier_id: supplier_id}, function (feedBackResult) {
                        $(".vendor_details").html(feedBackResult);
                        if (feedBackResult == 'false') {
                            toastr.error('Supplier Not Found')
                        }
                    })
                }
            });
            // get attribute map
            $("#attribute_id").change(function () {
                var attribute_id = $(this).val();
                if (attribute_id) {
                    $.post("{{ route('admin.purchase.vendor.attribute.map') }}", {attribute_id: attribute_id}, function (feedBackResult) {
                        $("#product_attribute_map_id").html(feedBackResult);
                    })
                }
            });
            // date
            $("#Date").change(function () {
                var date = $(this).val();
                if (date) {
                    $("#invoice_no").val('');// to check invoice no for unice
                    $('#Date').css('border', '1px solid #00c292');
                }
            });

            $(".autogenerateInvoice").on('click', function () {
                var supplier_id = $("#supplier_id").val();
                var date = new Date($('#Date').val());
                var year = date.getFullYear();
                $.post("{{ route('admin.purchases.autogenerate.invoice.unique') }}", {
                    year: year,
                    supplier_id: supplier_id
                }, function (res) {
                    $("#invoice_no").val(res);
                });
            })

            // check invoice_no is unique
            $("#invoice_no").keyup(function () {
                var invoice_no = $(this).val();
                var supplier_id = $("#supplier_id").val();
                var date = new Date($('#Date').val());
                var year = date.getFullYear();
                if (invoice_no) {
                    if (!supplier_id) {
                        alert('You must be need to select Vendor');
                        $(this).val('');
                        $('#supplier_id').css('border', '1px solid red').focus();
                    } else if (!date) {
                        alert('You must be need to select Date');
                        $(this).val('');
                        $('#Date').css('border', '1px solid red').focus();
                    }
                    $.post("{{ route('admin.check.invoice_no.unique') }}", {
                        invoice_no: invoice_no,
                        supplier_id: supplier_id,
                        year: year
                    }, function (res) {
                        if (res == 'true') {
                            $("#invoice_no").val('');
                            $("#checkInvoice").html('<span style="color: red">Invoice No: already Exist.Please Type another!</span>');
                        } else {
                            $("#checkInvoice").html('<span style="color: #00c292" >Invoice No: Available!</span>');
                        }
                    });
                }
            });


            // add more attribute and value
            {{--$(" table tbody #add_multipul_attribute").on('click', function () {--}}
            {{--    var unique_number = $('.attribut_section').children().length;--}}
            {{--    var attr_html = `<div class="form-group remove_this_appended attr_` + unique_number + `">--}}
            {{--                            <select  class="form-control attribute_id" data-index="` + unique_number + `">--}}
            {{--                                <option value="">Select</option>--}}
            {{--                                @foreach($attributes as $attribute)--}}
            {{--    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>--}}
            {{--                                @endforeach--}}
            {{--    </select>--}}
            {{--</div>`;--}}
            {{--    var attr_map_html = `<div class="form-group remove_this_appended remove_attr_map_` + unique_number + `">--}}
            {{--                                <select  class="form-control product_attribute_map_id attr_map_` + unique_number + `">--}}
            {{--                                    <option value="">Select</option>--}}
            {{--                                    @foreach($attribute_maps as $attribute_map)--}}
            {{--    <option value="{{ $attribute_map->id }}">{{ $attribute_map->value }}</option>--}}
            {{--                                    @endforeach--}}
            {{--    </select>--}}
            {{--</div>`;--}}
            {{--    var qty_html = `<div class="form-group remove_this_appended qty_` + unique_number + `">--}}
            {{--                            <div class="input-group">--}}
            {{--                                <input type="text" class="form-control qty">--}}
            {{--                                <div class="input-group-append" style="cursor:pointer" onclick="removeAttributeAddedItem(` + unique_number + `)"><button class="btn btn-danger btn-icon" type="button" data-toggle="tooltip" title="Remove Product Attribute">--}}
            {{--                                                        <i class="fa fa-minus-circle"></i></button></div>--}}
            {{--                            </div>--}}
            {{--                        </div>`;--}}
            {{--    $('.attribut_section').append(attr_html);--}}
            {{--    $('.attribut_map_section').append(attr_map_html);--}}
            {{--    $('.qty_section').append(qty_html);--}}
            {{--});--}}

            $("table tbody tr").on('change keyup', '.attribute_id, .append_qty, .qty', function () {
                var element = $(this);
                var attribute_id = element.val();
                var index_no = element.data('index');
                var class_name = element.attr('class');
                if (class_name === 'form-control attribute_id') {
                    if (attribute_id) {
                        $.post("{{ route('admin.purchase.vendor.attribute.map') }}", {attribute_id: attribute_id}, function (feedBackResult) {
                            $(".attribut_map_section").find('.attr_map_' + index_no).html(feedBackResult)
                        })
                    }
                } else if (class_name === 'form-control qty') {
                    if ($(this).val() === '0') {
                        $(this).val('1')
                    }
                    if (/\D/g.test($(this).val())) {
                        // Filter non-digits from input value.
                        this.value = $(this).val().replace(/\D/g, '');
                    }
                }
            });

            // start===> finaly add new item in our collection
            $("#addnewItemBtn").click(function (e) {
                e.preventDefault();
                var product_id_text = $('#product_id option:selected').text();
                var product_id = $('#product_id option:selected').val();

                // var attribute_arr_id = $('.attribut_section').find('.attribute_id').map(function () {
                //     return $(this).val();
                // }).get();
                // var attribute_arr_id_solid = attribute_arr_id.filter(function (e) {
                //     if (e !== 'Select') {
                //         return e;
                //     }
                // });
                //
                // var attribute_arr_text = [];
                // $('.attribut_section :selected').each(function (i, selected) {
                //     if ($(selected).text() !== 'Select') {
                //         attribute_arr_text[i] = $(selected).text();
                //     }
                // });
                //
                // var attribute_arr_map_id = $('.attribut_map_section').find('.product_attribute_map_id').map(function () {
                //     return $(this).val();
                // }).get();
                // var attribute_arr_map_id_solid = attribute_arr_map_id.filter(function (e) {
                //     if (e !== 'Select') {
                //         return e;
                //     }
                // });
                // var attribut_map_text = [];
                // $('.attribut_map_section :selected').each(function (i, selected) {
                //     if ($(selected).text() !== 'Select') {
                //         attribut_map_text[i] = $(selected).text();
                //     }
                // });
                /*
                    Product can have multiple Attribute and attribtue map
                 */
                var attribute_arr_id_solid = [];
                var attribute_arr_map_id_solid = [];
                var attribute_arr_text = [];
                var attribut_map_text = [];
                $('.attribute-tags').each(function (){
                    var attribute = $(this).data('attribute');
                    var attribute_map = $(this).data('attribute-map');
                    if(!isEmpty(attribute_map) && !isEmpty(attribute)){
                        attribute_arr_id_solid.push(attribute);
                        attribute_arr_map_id_solid.push(attribute_map);
                        attribute_arr_text += $(this).data('attribute-text')+'<br/>';
                        attribut_map_text += $(this).data('attribute-map-text')+'<br/>';
                    }
                });

                var qty_arr = $('.qty_section').find('.qty').map(function () {
                    return $(this).val();
                }).get();
                var qty_arr_solid = qty_arr.filter(function (e) {
                    if (e !== '0') {
                        return e;
                    }
                });

                var price_arr = $('.price_section').find('.price').map(function () {
                    return $(this).val();
                }).get();
                var price_arr_solid = price_arr.filter(function (e) {
                    if (e !== '0') {
                        return e;
                    }
                });

                if (attribute_arr_id_solid.length === attribute_arr_map_id_solid.length &&
                    attribute_arr_id_solid.length && qty_arr_solid.length > 0 &&
                    product_id_text !== 'Select') {
                    var tbl = '\n' +
                        '<tr id="removeThisItem" class="everyNewSingleItem">\n' +
                        '     <td>\n' +
                        '         <span for="">' + product_id_text + '</span>\n' +
                        '         <input type="hidden" class="uniqueProduct_id" data-addedProduct_id="' + product_id + '" name="store_product_id[]" value="' + product_id + '">\n' +
                        '     </td>\n' +

                        '     <td>\n' +
                        '          <span for="">' + attribute_arr_text + '</span>\n' +
                        '         <input type="hidden" name="store_attribute_id[]" value="' + attribute_arr_id_solid + '">\n' +
                        '     </td>\n' +

                        '     <td>\n' +
                        '          <span for="">' + attribut_map_text + '</span>\n' +
                        '         <input type="hidden" data-addedAttributemap_id="' + attribute_arr_map_id_solid + '" class="uniqueAttributemap_id" name="store_product_attribute_map_id[]" value="' + attribute_arr_map_id_solid + '">\n' +
                        '     </td>\n' +

                        '     <td>\n' +
                        '         <span for="">' + qty_arr_solid + '</span>\n' +
                        '          <input type="hidden" name="store_quantity[]" value="' + qty_arr_solid + '">\n' +
                        '     </td>\n' +

                        '     <td>\n' +
                        '         <span for="">' + price_arr_solid + '</span>\n' +
                        '          <input type="hidden" name="store_price[]" value="' + price_arr_solid + '">\n' +
                        '     </td>\n' +

                        '     <td style="padding-top: 9px;">\n' +
                        '         <a href="#0" id="removeThis" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>\n' +
                        '     </td>\n' +
                        '</tr>';
                    if (attribute_arr_id_solid.length !== 0 && attribute_arr_map_id_solid.length !== 0 && product_id.length !== 0 && qty_arr_solid.length !== 0) {
                        $("#appendNewItemSection").append(tbl);
                        $('.attribut_section').find('.remove_this_appended').remove();
                        $('.attribut_map_section').find('.remove_this_appended').remove();
                        $('.qty_section').find('.remove_this_appended').remove();
                        $('.price_section').find('.remove_this_appended').remove();
                        $('#attribute_id option:selected').removeAttr("selected");
                        $('#product_attribute_map_id option:selected').removeAttr("selected");
                        $('#product_id option:selected').removeAttr("selected");
                        $("#product_attribute_map_id option:selected").remove();
                        $("#qty").val('');
                        $("#price").val('');
                        clearTagList();
                    } else {
                        toastr.error('Please Fill Up all field with valid value. Code: Valid:1')
                    }
                } else {
                    toastr.error('Please Fill Up all field with valid value. Code: Valid:2')
                }

            });
        });

        // remove item with calculation
        $(document).on("click", "#removeThis", function () {
            $(this).parents('#removeThisItem').remove();
        });


        function removeAttributeAddedItem(index) {
            if (index) {
                $('.attribut_section').find('.attr_' + index).remove();
                $('.attribut_map_section').find('.remove_attr_map_' + index).remove();
                $('.qty_section').find('.qty_' + index).remove();
                $('.price_section').find('.price_' + index).remove();
            }
        }
        // multiple attribute map
        $('#product_attribute_map_id').on('change',function (){
            var product_attribute_map_id = $("#product_attribute_map_id :selected").val();
            var attributemap_text = $("#product_attribute_map_id :selected").html();
            var attribute_id = $("#attribute_id :selected").val();
            var attribute_text = $("#attribute_id :selected").html();
            if(!isUniqueTagInput(attribute_id)){
                toastr.warning('Already added attribute: '+ attribute_text);
                return false;
            }
            $("#tag-list").show();
            $("#tag-list ").append('<span class="badge badge-secondary mr-2 attribute-tags" ' +
                'data-attribute='+attribute_id+' data-attribute-map='+product_attribute_map_id+' data-attribute-text='+attribute_text+' data-attribute-map-text='+attributemap_text+' >'
                +attribute_text+'-'+attributemap_text+
                '&nbsp;<a class="btn-link mr-2" href="javascript:;" onclick="return $(this).parent().remove()"> <i class="fa fa-times"></i></a></span>');
        });
        function  isUniqueTagInput(attribute_id){
            var isUnique = true;
            $('.attribute-tags').each(function (){
                if($(this).data('attribute') == attribute_id){
                    isUnique = false;
                    return false;
                }
            });
            return isUnique;
        }
        function clearTagList(){
            $("#tag-list").html('');
            $("#tag-list").hide();
        }
        $('#product_id').on('change',function (){
            clearTagList();
        });
    </script>

@endpush
