@extends('layouts.crud-master')
@section('title', 'Purchase Edit')

@section('main_content')
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div
            class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Edit Purchase Order</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-muted font-weight-bold mr-4">Edit purchase Order</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <!--begin::Actions-->
                <a
                    data-toggle="tooltip"
                    title="Add Purchase Order"
                    href="{{route('admin.purchase.create')}}"
                    class="btn btn-light-success btn-sm btn-clean font-weight-bold font-size-base mr-1">
                    <i class="fa fa-plus"></i>Add Purchase Order
                </a>
                <a
                    data-toggle="tooltip"
                    title="Purchase Order List"
                    href="{{route('admin.purchase.index')}}"
                    class="btn btn-light-primary btn-sm btn-clean font-weight-bold font-size-base mr-1">
                    <i class="fa fa-list"></i>Purchase Order List
                </a>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar-->
        </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px">
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
                            <form method="POST" action="{{ route('admin.purchase.update', $purchase->id) }}"
                                  accept-charset="UTF-8">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="supplier_id">Supplier</label>
                                        <select required name="supplier_id" id="supplier_id" class="form-control">
                                            <option value="" selected>select Supplier...</option>
                                            @foreach($suppliers as $supplier)
                                                <option
                                                    {{ $purchase->supplier_id == $supplier->id ? 'selected' :'' }} value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                        <p id="autofocusOption"></p>
                                        @error('supplier_id')<strong class="text-danger" role="alert"><span>{{ $message }}</span></strong>@enderror
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

                                        <div class="form-group col-md-3">
                                            <label for="#">Date</label>
                                            <input required name="date" type="date" value="{{ $purchase->date }}"
                                                   class="form-control" id="Date">
                                            <p id="autofocusDate"></p>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="#">Invoice No</label>
                                            <input type="text" id="invoice_no" name="invoice_no"
                                                   value="{{ $purchase->invoice_no }}" required class="form-control">
                                            <p id="checkInvoice"></p>
                                        </div>

                                    </div>
                                </div>
                                {{--add new item--}}
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" id="tab_logic">
                                            <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Attribute</th>
                                                <th>Attribute Map</th>
                                                <th>Quantity</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody id="makeInputData">
                                            <tr>
                                                <td>
                                                    <select id="new_product_id" class="form-control">
                                                        <option value="">Select</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="attribute_id" class="form-control">
                                                        <option value="">Select</option>
                                                        @foreach($attributes as $attribute)
                                                            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="product_attribute_map_id" class="form-control">
                                                        <option value="">Select</option>
                                                        @foreach($attribute_maps as $attribute_map)
                                                            <option
                                                                value="{{ $attribute_map->id }}">{{ $attribute_map->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control singleQty" id="new_qty">
                                                </td>
                                                <td>
                                                    <a href="#0" id="addnewItembtn" style="background: #00c292; color: #f0f0f0"
                                                       class="btn addnewItem"><i class="fa fa-plus-circle"></i></a>
                                                </td>
                                            </tr>
                                            {{--all store item--}}
                                            @foreach($purchase->purchaseDetail as $item)
                                                @php
                                                    $attributeMaps = \App\ProductAttributeMap::where(['product_attribute_id' => $item->attribute_id, 'vendor_id' =>auth()->user()->vendor_id])->get()
                                                @endphp
                                                <tr class="parentTr">
                                                    <td>
                                                        <input type="hidden" class="addedProduct_id"
                                                               data-addedProduct_id="{{ $item->product_id }}">
                                                        <select name="product_id[]" id="product_id"
                                                                class="form-control product_id">
                                                            <option selected>Select</option>
                                                            @foreach($products as $product)
                                                                <option
                                                                    {{ $product->id == $item->product_id ? 'selected' : ''  }} value="{{ $product->id }}">{{ $product->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="attribute_id[]" id="attribute_id"
                                                                class="form-control attribute_id">
                                                            <option selected>Select</option>
                                                            @foreach($attributes as $attribute)
                                                                <option
                                                                    {{ $attribute->id == $item->attribute_id ? 'selected' : ''  }} value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" class="addedAttributemap_id"
                                                               data-addedAttributemap_id="{{ $item->product_attribute_map_id  }}">
                                                        <select name="product_attribute_map_id[]" id="product_attribute_map_id"
                                                                class="form-control product_attribute_map_id">
                                                            <option selected>Select</option>
                                                            @foreach($attributeMaps as $attributeMap)
                                                                <option
                                                                    {{ $attributeMap->id == $item->product_attribute_map_id ? 'selected' : ''  }} value="{{ $attributeMap->id }}">{{ $attributeMap->value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="{{$item->quantity }}" name="quantity[]"
                                                               class="form-control newQty">
                                                    </td>
                                                    <td>
                                                        <a href="#0" data_id="{{ $item->id }}"
                                                           class="btn btn-sm btn-danger removeExistItem"><i
                                                                class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                {{--end add new item--}}

                                <div class="row clearfix" style="margin-top:20px">
                                    <div class="pull-right col-md-4">
                                        <table class="table table-bordered table-hover" id="tab_logic_total">
                                            <tbody>
                                            <tr>
                                                <td class="text-center">
                                                    <button type="submit" class="btn submit_button"
                                                            style="background: #00c292; color: #f0f0f0"> Update
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

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // get supplier id
            $("#supplier_id").change(function () {
                var supplier_id = $(this).val();
                if (supplier_id) {
                    $('#supplier_id').css('border', '1px solid #00c292');
                    $.get("{{ route('admin.purchase.supplier.details') }}", {supplier_id: supplier_id}, function (feedBackResult) {
                        $(".vendor_details").html(feedBackResult);
                    })
                }
            });

            // start===> get attribute map for every new item when we add
            $("#attribute_id").change(function () {
                var attribute_id = $(this).val();
                if (attribute_id) {
                    $.post("{{ route('admin.purchase.vendor.attribute.map') }}", {attribute_id: attribute_id}, function (feedBackResult) {
                        $("#product_attribute_map_id").html(feedBackResult);
                    })
                }
            });
            // end===> get attribute map for every new item when we add


            /* start ==>> every time we need to add unique item */
            $("table").on('change keyup', '#new_product_id, #product_attribute_map_id, #new_qty', function () {
                var new_product_id = $('#new_product_id option:selected').val();
                var product_attribute_map_id = $('#product_attribute_map_id option:selected').val();

                if ($(this).attr('id') === 'new_qty') {
                    if ($(this).val() === '0') {
                        $(this).val('1')
                    }
                    if (/\D/g.test($(this).val())) {
                        // Filter non-digits from input value.
                        this.value = $(this).val().replace(/\D/g, '');
                    }
                }

                // start==> searche area
                $('tbody .parentTr').each(function () {
                    var added_product = $(this).find('.addedProduct_id').attr('data-addedProduct_id');
                    var added_attributeMap_id = $(this).find('.addedAttributemap_id').attr('data-addedAttributemap_id');
                    if ((new_product_id === added_product) && (product_attribute_map_id === added_attributeMap_id)) {
                        alert('alredy exist to your collection !');
                        $("#makeInputData :input").prop("disabled", true);
                        $("#addnewItembtn").attr('disabled', true);
                        $("#product_attribute_map_id").css('border', '1px solid red');
                        $("#product_attribute_map_id").prop("disabled", false);
                        $("#new_product_id").prop("disabled", false);
                        $(".submit_button").prop("disabled", true);
                        return false;
                    } else {
                        $("#addnewItembtn").attr('disabled', false);
                        $("#product_attribute_map_id").css('border', '1px solid green');
                        $("#makeInputData :input").prop("disabled", false);
                        $(".submit_button").prop("disabled", false);
                    }
                });
                // end==> searche area
            });
            /* end ==>> every time we need to add unique item */


            //start===> editable value does not want any null or 0
            $("table tbody .parentTr").on('change keyup', '.product_id, .product_attribute_map_id, .attribute_id, .newQty', function () {
                if ($(this).val() == 'Select') {
                    $(this).css('border', '1px solid red');
                    $(".submit_button").attr('disabled', true)
                    return false;
                } else if ($(this).val() === '') {
                    $(this).css('border', '1px solid red');
                    $(".submit_button").attr('disabled', true)
                    return false;
                } else if ($(this).val() === '0') {
                    $(this).css('border', '1px solid red');
                    $(this).val('1');
                    return false;
                } else {
                    $(".submit_button").attr('disabled', false)
                }
                if ($(this)[0].name === 'quantity[]') {
                    if ($(this).val() === '0') {
                        $(this).val('1')
                    }
                    if (/\D/g.test($(this).val())) {
                        // Filter non-digits from input value.
                        this.value = $(this).val().replace(/\D/g, '');
                    }
                }
            });
            //end===> editable value does not want any null or 0


            //start===> if stored item change attribute so we need to append that collection
            $(".attribute_id").change(function () {
                var added_attribute_id = $(this).val();
                var parent = $(this).parents('.parentTr');
                var product_attribute_map_id = parent.find('.product_attribute_map_id');
                if (added_attribute_id) {
                    $.get("{{ route('admin.purchase.vendor.attribute.map') }}", {attribute_id: added_attribute_id}, function (feedBackResult) {
                        product_attribute_map_id.html(feedBackResult);
                    })
                }
            });
            //end===> if stored item change attribute so we need to append that collection


            // date
            $("#Date").change(function () {
                var date = $(this).val();
                if (date) {
                    $('#Date').css('border', '1px solid #00c292');
                }
            });

            //start===> we need to uniqu invoice no per year
            $("#invoice_no").keyup(function () {
                var invoice_no = $(this).val();
                var supplier_id = $("#supplier_id").val();
                var date = new Date($('#Date').val());
                var year = date.getFullYear();
                if (invoice_no) {
                    if (!supplier_id) {
                        alert('You must be need to select Supplier');
                        $(this).val('');
                        $('#supplier_id').css('border', '1px solid red').focus();
                    } else if (!date) {
                        alert('You must be need to select Date');
                        $(this).val('');
                        $('#Date').css('border', '1px solid red').focus();
                    }
                    $.get("{{ route('admin.check.invoice_no.unique') }}", {
                        invoice_no: invoice_no,
                        supplier_id: supplier_id,
                        year: year
                    }, function (res) {
                        if (res == 'true') {
                            $("#checkInvoice").html('<span style="color: red">Invoice No already Exist.Please Type another!</span>');
                            $(this).val('');
                        } else {
                            $("#checkInvoice").html('<span style="color: #00c292" >Invoice No Available!</span>');
                        }
                    });
                }
            });
            //end===> we need to uniqu invoice no per year

            // if you need to add new item
            $("#addnewItembtn").click(function (e) {
                e.preventDefault();
                getAllDataInput()
            });

            function getAllDataInput() {
                var product_id_text = $('#new_product_id option:selected').text();
                var product_id = $('#new_product_id option:selected').val();

                var attribute_id_text = $('#attribute_id option:selected').text();
                var attribute_id = $('#attribute_id option:selected').val();

                var new_product_attribute_map_id_text = $('#product_attribute_map_id option:selected').text();
                var product_attribute_map_id = $('#product_attribute_map_id option:selected').val();

                var qty = $("#new_qty").val();

                var html = '\n' +
                    '<tr class="removeThisItem parentTr">\n' +
                    '     <td>\n' +
                    '         <span for="">' + product_id_text + '</span>\n' +
                    '         <input type="hidden" class="addedProduct_id" data-addedProduct_id="' + product_id + '" name="product_id[]" value="' + product_id + '">\n' +
                    '     </td>\n' +

                    '     <td>\n' +
                    '          <span for="">' + attribute_id_text + '</span>\n' +
                    '         <input type="hidden" name="attribute_id[]" value="' + attribute_id + '">\n' +
                    '     </td>\n' +

                    '     <td>\n' +
                    '          <span for="">' + new_product_attribute_map_id_text + '</span>\n' +
                    '         <input type="hidden" class="addedAttributemap_id" data-addedAttributemap_id="' + product_attribute_map_id + '" name="product_attribute_map_id[]" value="' + product_attribute_map_id + '">\n' +
                    '     </td>\n' +

                    '     <td>\n' +
                    '         <span for="">' + qty + '</span>\n' +
                    '          <input type="hidden" name="quantity[]" value="' + qty + '">\n' +
                    '     </td>\n' +

                    '     <td style="padding-top: 9px;">\n' +
                    '         <a href="#0" id="removeThis" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>\n' +
                    '     </td>\n' +
                    '</tr>';


                if (!product_id || !attribute_id || !product_attribute_map_id || !qty) {
                    toastr.error('Please Fillable must be all');
                } else {
                    $("#makeInputData").append(html);
                    emptyAll();
                }
            }

            function emptyAll() {
                $('#attribute_id option:selected').removeAttr("selected");
                $('#product_attribute_map_id option:selected').removeAttr("selected");
                $('#new_product_id option:selected').removeAttr("selected");
                $("#new_qty").val('');
            }


            // remove  exist item
            $(".removeExistItem").on('click', function () {
                var id = $(this).attr("data_id");
                var parent = $(this).parents('.parentTr');
                $.ajax({
                    url: "{{ route('admin.purchase.details.delete') }}",
                    data: {id: id},
                    type: "post",
                    success: function (res) {
                        if (res == 'true') {
                            parent.html('');
                            toastr.success('This Item Deleted Success');
                        } else {
                            toastr.success('You press the wrong item');
                        }
                    }
                });
            })

        });

        // start==> remove item with calculation
        $(document).on("click", "#removeThis", function () {
            $(this).parents('.removeThisItem').remove();
        });
        // end==> remove item with calculation
    </script>
@endpush
