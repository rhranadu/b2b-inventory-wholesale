@extends('layouts.sales')
@section('title', 'Sale Create')

@section('main_content')
    <!--begin::Entry-->
    <div class="row h-100 no-gutters">
        <div class="col-lg-9 h-100">
            <div class="card card-custom h-100">
                <div class="card-header">
                    <div class="d-flex mb-0 justify-content-between align-items-center w-100">
                        <button class="btn btn-text-primary btn-hover-light-primary font-weight-bold"><i
                                class="fa fa-plus"></i> Add New
                            Item
                        </button>
                        <div class="input-group w-lg-450px">
                            <input type="text" class="form-control product_search_field"
                                   placeholder="Search . . ."
                                   name="search">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary product_search_btn"><i
                                        class="fa fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="scroll flex-fill overflow-hidden" data-scroll="true">
                        <div class="reloadbyAjax row">
                            @foreach($products as $product)
                                <div class="col-lg-3 cursor-pointer every_single_product{{$product->id}}"
                                     onclick="everySingleProduct(this, {{ $product->id }})">
                                    <div class="card shadow-sm mb-3">

                                        <div class="image_square_product pop_img"
                                             style="background-image: url({{asset('assets/media/products/1.png')}});"
                                             data-img="{{asset('assets/media/products/1.png')}}">{{--{{ asset($product->product->image_path) }}--}}
                                        </div>

                                        <div class="product_desc py-3">
                                            <h4 class="mb-0 font-weight-bold">{{isset($product->product->name) ? $product->product->name : 'N/A'  }}</h4>
                                            <p class="mb-0">{{isset($product->productAttribute->name) ? $product->productAttribute->name : 'N/A'  }}
                                                - {{isset($product->productAttributeMap->value) ? $product->productAttributeMap->value : 'N/A'  }}</p>
                                        </div>
                                        <div class="needValue" style="display: none">
                                            <span class="stock_detail_id">{{ $product->id }}</span>
                                            <span class="product_name">{{ isset($product->product->name) ? $product->product->name : 'N/A' }}</span>
                                            <span class="product_id">{{ $product->product_id }}</span>
                                            <span class="attribute_name">{{ isset($product->productAttribute->name) ? $product->productAttribute->name : 'N/A' }}</span>
                                            <span class="attribute_id">{{ $product->product_attribute_id }}</span>
                                            <span class="attribute_map_name">{{ isset($product->productAttributeMap->value) ? $product->productAttributeMap->value : 'N/A' }}</span>
                                            <span class="attribute_map_id">{{ $product->product_attribute_map_id }}</span>
                                            <span class="warehouse_id">{{ $product->warehouse_id }}</span>
                                            <span class="available_quantity">{{ $product->quantity }}</span>
                                            @if (!empty($product->product->pos_discount_price))
                                                <span class="per_price">{{ $product->product->pos_discount_price }}</span>
                                            @else
                                                <span class="per_price">{{ $product->product->max_price }}</span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                            @foreach($products as $product)
                                <div class="col-lg-3 cursor-pointer every_single_product{{$product->id}}"
                                     onclick="everySingleProduct(this, {{ $product->id }})">
                                    <div class="card shadow-sm mb-3">

                                        <div class="image_square_product pop_img"
                                             style="background-image: url({{asset('assets/media/products/1.png')}});"
                                             data-img="{{asset('assets/media/products/1.png')}}">{{--{{ asset($product->product->image_path) }}--}}
                                        </div>

                                        <div class="product_desc py-3">
                                            <h4 class="mb-0 font-weight-bold">{{isset($product->product->name) ? $product->product->name : 'N/A' }}</h4>
                                            <p class="mb-0">{{ isset($product->productAttribute->name) ? $product->productAttribute->name : 'N/A' }}
                                                - {{ isset($product->productAttributeMap->value) ? $product->productAttributeMap->value : 'N/A' }}</p>
                                        </div>
                                        <div class="needValue" style="display: none">
                                            <span class="stock_detail_id">{{ $product->id }}</span>
                                            <span class="product_name">{{ isset($product->product->name) ? $product->product->name : 'N/A' }}</span>
                                            <span class="product_id">{{ $product->product_id }}</span>
                                            <span
                                                class="attribute_name">{{ isset($product->productAttribute->name) ? $product->productAttribute->name : 'N/A' }}</span>
                                            <span class="attribute_id">{{ $product->product_attribute_id }}</span>
                                            <span
                                                class="attribute_map_name">{{ isset($product->productAttributeMap->value) ? $product->productAttributeMap->value : 'N/A' }}</span>
                                            <span
                                                class="attribute_map_id">{{ $product->product_attribute_map_id }}</span>
                                            <span class="warehouse_id">{{ $product->warehouse_id }}</span>
                                            <span class="available_quantity">{{ $product->quantity }}</span>
                                            @if (!empty($product->product->pos_discount_price))
                                                <span class="per_price">{{ $product->product->pos_discount_price }}</span>
                                            @else
                                                <span class="per_price">{{ $product->product->max_price }}</span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                                @foreach($products as $product)
                                    <div class="col-lg-3 cursor-pointer every_single_product{{$product->id}}"
                                         onclick="everySingleProduct(this, {{ $product->id }})">
                                        <div class="card shadow-sm mb-3">

                                            <div class="image_square_product pop_img"
                                                 style="background-image: url({{asset('assets/media/products/1.png')}});"
                                                 data-img="{{asset('assets/media/products/1.png')}}">{{--{{ asset($product->product->image_path) }}--}}
                                            </div>

                                            <div class="product_desc py-3">
                                                <h4 class="mb-0 font-weight-bold">{{ isset($product->product->name) ? $product->product->name : 'N/A' }}</h4>
                                                <p class="mb-0">{{ isset($product->productAttribute->name) ? $product->productAttribute->name : 'N/A' }}
                                                    - {{ isset($product->productAttributeMap->value) ? $product->productAttributeMap->value : 'N/A' }}</p>
                                            </div>
                                            <div class="needValue" style="display: none">
                                                <span class="stock_detail_id">{{ $product->id }}</span>
                                                <span class="product_name">{{ isset($product->product->name) ? $product->product->name : 'N/A' }}</span>
                                                <span class="product_id">{{ $product->product_id }}</span>
                                                <span
                                                    class="attribute_name">{{ isset($product->productAttribute->name) ? $product->productAttribute->name : 'N/A' }}</span>
                                                <span class="attribute_id">{{ $product->product_attribute_id }}</span>
                                                <span
                                                    class="attribute_map_name">{{ isset($product->productAttributeMap->value) ? $product->productAttributeMap->value : 'N/A' }}</span>
                                                <span
                                                    class="attribute_map_id">{{ isset($product->product_attribute_map_id) ? $product->product_attribute_map_id : 'N/A' }}</span>
                                                <span class="warehouse_id">{{ $product->warehouse_id }}</span>
                                                <span class="available_quantity">{{ $product->quantity }}</span>
                                                @if (!empty($product->product->pos_discount_price))
                                                    <span class="per_price">{{ $product->product->pos_discount_price }}</span>
                                                @else
                                                    <span class="per_price">{{ $product->product->max_price }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="card-toolbar justify-content-end d-flex">
                        <button class="btn btn-outline-danger mr-2">Cancel Order</button>
                        <button class="btn btn-outline-success">Hold Order</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 h-100">
            <div class="card h-100">
                <div class="card-header">
                    <div class="card-title">
                        <ul class="nav nav-tabs nav-light-success nav-pills bill_list">
                            <li class="nav-item active">
                                <a class="text-nowrap nav-link active" href=".new_1"
                                   data-toggle="tab">New-1
                                </a>
                                <i class="ki ki-close icon-nm close_btn"></i>
                            </li>
                        </ul>
                    </div>
                    <div class="card-toolbar">
                        <a href="#" class="add-new_item btn btn-icon btn-sm btn-light-primary"
                           data-toggle="tab"><i
                                class="fa fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active new_1">
                            <div class="pos_customer_reload_with_ajax mb-3">
                                <div class="input-group">
                                    <div class="flex-fill">
                                        <select class="form-control pos_customer_id select2" id="choice_client"
                                                name="param">
                                            <option value="">- Choose Client -</option>
                                            {{--@foreach($poscustomers as $poscustomer)
                                                <option
                                                    value="{{ $poscustomer->id }}">{{ $poscustomer->name }}</option>
                                            @endforeach--}}
                                        </select>
                                    </div>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success pos_customer_create">
                                            <i class="fa fa-user-plus"></i> Client
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Product</th>
                                            <th scope="col">Attribute</th>
                                            <th scope="col" width="100">Quantity</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Total</th>
                                            <th scope="col"></th>
                                        </tr>
                                        </thead>
                                        <tbody class="appendTr">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <table class="table table-bordered table-hover claculation_section">
                        <tbody class="new_1">
                        <tr>
                            <th class="text-center">Sub Total</th>
                            <td class="text-right html_subtotal">
                                <span style="font-size: larger; font-weight: bold">0</span>
                                <input type="number" style="display: none" class="sub_total">
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center">Tax (+)</th>
                            <td class="text-center">
                                <div class="input-group mb-2 mb-sm-0">
                                    <input type="number" min="0" value="0"
                                           class="form-control tax"
                                           name="tax">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="button">%</button>
                                    </div>

                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center">Shipping Charge (+)</th>
                            <td class="text-center">
                                <div class="input-group mb-2 mb-sm-0">
                                    <input type="text" min="0" value="0"
                                           class="form-control shipping_charge">
                                    {{-- <div class="input-group-append"><button class="btn btn-secondary" type="button">Money</button></div> --}}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center">Discount (-)</th>
                            <td class="text-center">
                                <div class="input-group mb-2 mb-sm-0">
                                    <input type="text" min="0" value="0"
                                           class="form-control discount">
                                    {{-- <div class="input-group-append"><button class="btn btn-secondary" type="button">Money</button></div> --}}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center">Total</th>
                            <td class="text-right final_total">
                                <span style="font-size: larger; font-weight: bold">0</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center"></th>
                            <td class="text-center">
                                <button type="submit" class="btn btn-success sale_btn">Sale
                                </button>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
    @include('sales.payment_model')
    @include('sales.new_pos_customer_model')

@endsection

@push('script')


    <script>

        $('#choice_client').select2({
            width: '100%',
            placeholder: "- Choice a Client -"
        });

        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.pos_customer_create:visible', function () {
                $(".pos_customer_model").modal('show')
            });


            $(".nav-tabs").on("click", "a", function (e) {
                e.preventDefault();
                $(this).tab('show');
            }).on("click", "span", function () {
                var anchor = $(this).siblings('a');
                $(anchor.attr('href')).remove();
                $(this).parent().remove();
                $(".nav-tabs li").children('a').first().click();
            });

            $('.add-new_item').click(function (e) {
                e.preventDefault();
                var id = $(".nav-tabs").children().length; //think about it ;)
                var html = `<div class="pos_customer_reload_with_ajax" style="margin-bottom: 30px; margin-top: 30px">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="form-example-int form-example-st">
                                            <select class=" form-control pos_customer_id float-left">
                                                <option value="">- Choose Client -</option>
                                                {{--@foreach($poscustomers as $poscustomer)
                <option value="{{ $poscustomer->id }}">{{ $poscustomer->name }}</option>
                                                @endforeach--}}
                </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <div class="form-example-int">
            <button class="btn pos_customer_create" style="background: #00c292; color: #fff"><i class="fa fa-user-plus"></i></button>
        </div>
    </div>
</div>
</div>
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<table class="table">
<thead class="thead-light">
<tr>
<th scope="col">Product</th>
<th scope="col">Attribute</th>
<th scope="col" width="100">Quantity</th>
<th scope="col">Price</th>
<th scope="col">Total</th>
<th scope="col"></th>
</tr>
</thead>
<tbody class="appendTr">

</tbody>
</table>
</div>
</div>
<div class="row" style="margin-top: 100px">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<table class="table table-bordered table-hover claculation_section" >
<tbody class="new_` + id + `">
            <tr>
                <th class="text-center">Sub Total</th>
                <td class="text-right html_subtotal">
                    <span style="font-size: larger; font-weight: bold">0</span>
                    <input type="hidden" class="sub_total">
                </td>
            </tr>
            <tr>
                <th class="text-center">Tax (+)</th>
                <td class="text-center">
                    <div class="input-group mb-2 mb-sm-0">
                        <input type="number" min="0"  value="0" class="form-control tax" name="tax">
                    </div>
                </td>
            </tr>
            <tr>
                <th class="text-center">Shipping Charge (+)</th>
                <td class="text-center">
                    <div class="input-group mb-2 mb-sm-0">
                        <input type="text" min="0"  value="0" class="form-control shipping_charge">
                    </div>
                </td>
            </tr>
            <tr>
                <th class="text-center">Discount (-)</th>
                <td class="text-center">
                    <div class="input-group mb-2 mb-sm-0">
                        <input type="text" min="0" value="0" class="form-control discount">
                    </div>
                </td>
            </tr>
            <tr>
                <th class="text-center">Total</th>
                <td class="text-right final_total" >
                    <span style="font-size: larger; font-weight: bold">0</span>
                </td>
            </tr>
            <tr>
                <th class="text-center"></th>
                <td class="text-center">
                    <button type="submit" class="btn btn-success sale_btn">Sale</button>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
</div>`;
                $('.bill_list').append('<li class="nav-item"><a class="text-nowrap nav-link" href=".new_' + id + '">New-' + id + ' </a><i class="ki ki-close icon-nm close_btn"></i></li>');
                $('.tab-content').append('<div class="tab-pane new_' + id + '">' + html + '</div>');
            });

        });

        //start==> add product to cart
        function everySingleProduct(element, id) {

            var every_prduct = $(".every_single_product" + id);

            var product_id = every_prduct.find('.product_id').text();
            var product_name = every_prduct.find('.product_name').text();

            var attribute_id = every_prduct.find('.attribute_id').text();
            var attribute_name = every_prduct.find('.attribute_name').text();

            var attribute_map_id = every_prduct.find('.attribute_map_id').text();
            var attribute_map_name = every_prduct.find('.attribute_map_name').text();

            var warehouse_id = every_prduct.find('.warehouse_id').text();
            var per_price = every_prduct.find('.per_price').text();
            var stock_detail_id = every_prduct.find('.stock_detail_id').text();
            var available_quantity = every_prduct.find('.available_quantity').text();
            var html = `<tr class="parentTR everyTr` + stock_detail_id + `">
                            <td>` + product_name + ` <input type="hidden" class="get_stock_detail_id" name="stock_detail_id[]" value="` + stock_detail_id + `"> <input type="hidden" class="product_id" name="product_id[]" value="` + product_id + `"> <input type="hidden" class="warehouse_id" name="warehouse_id[]" value="` + warehouse_id + `"></td>
                            <td>` + attribute_name + `- ` + attribute_map_name + ` <input type="hidden" class="attribute_id" name="attribute_id[]" value="` + attribute_id + `"> <input type="hidden" class="attribute_map_id" name="attribute_map_id[]" value="` + attribute_map_id + `"> </td>
                            <td>
                              <input type="number"  class="form-control form-control-sm append_quantity" name="quantity[]" value="1" min="1" max="` + available_quantity + `">
                            </td>
                            <td>` + per_price + ` <input type="hidden" class="per_price" name="per_price[]" value="` + per_price + `"></td>
                            <td class="total_text">` + (per_price * 1) + ` <input type="hidden" class="total" name="total[]" value="` + (per_price * 1) + `"></td>
                            <td class="text-danger"><i class="fa fa-trash" onclick="everyTrRemove(this, ` + stock_detail_id + `)" style="cursor: pointer"></i></td>
                          </tr>`;

            //start==> check current tab
            var findappendTr = checkCurrentTab();
            //end==> check current tab
            if (available_quantity > 0) {
                var findTr = findappendTr.find(".everyTr" + stock_detail_id + "");
                if (findTr.length > 0) {
                    toastr.error('This item already added to your cart')
                } else {
                    findappendTr.append(html);
                    calc_total();
                }
            } else {
                toastr.error('Quantity is not available')
            }

        }

        //end==> add product to cart


        // remove every tr
        function everyTrRemove(element, id) {
            checkCurrentTab().find('.everyTr' + id).remove();
            calc_total();
        }

        function checkCurrentTab() {
            var findActiveClasee = $(".bill_list").find('a').attr('href');
            var getAppendTr = $(".tab-content").find(findActiveClasee).find('.appendTr');

            return getAppendTr;
        }


        /**************start calculation from here********************/

        //start===> change the quantity so we need to calculation all
        $(document).on('change', '.append_quantity:visible', function () {
            var parent = $(this).parents(".parentTR");
            var qty = parent.find('.append_quantity').val();
            var max_qty = parent.find('.append_quantity').attr('max');

            if (parseInt(max_qty) >= parseInt(qty)) {
                var per_price = parent.find('.per_price').val();
                parent.find('.total_text').html((parseFloat(qty) * parseFloat(per_price)) + '<input type="hidden" class="total" name="total[]" value="' + (parseFloat(qty) * parseFloat(per_price)) + '">');
                calc_total()
            } else {
                parent.find('.append_quantity').val('');
                toastr.error('Only ' + max_qty + ' items are available ')
            }


        });
        //end===> change the quantity so we need to calculation all

        //start==> calculation total
        function calc_total(total = 0) {
            var findActiveClasee = $(".nav-tabs").find('.active').find('a').attr('href');
            var calItem = $(".claculation_section").find(findActiveClasee);

            total = 0;
            $(".tab-content").find(findActiveClasee).find('.appendTr').find('.total').each(function () {
                total += parseFloat($(this).val());
            });

            calItem.find(".html_subtotal").html(`<span style="font-size: larger; font-weight: bold">` + total.toFixed(2) + `</span><input type="hidden" value="` + total.toFixed(2) + `">`);
            tax_sum = total / 100 * calItem.find('.tax').val();
            calItem.find(".final_total").html(`<span style="font-size: larger; font-weight: bold"> ` + (tax_sum + total + parseFloat(calItem.find(".shipping_charge").val() - (parseFloat(calItem.find(".discount").val())))).toFixed(2) + ` </span><input type="hidden" value="` + (tax_sum + total + parseFloat(calItem.find(".shipping_charge").val() - (parseFloat(calItem.find(".discount").val())))).toFixed(2) + `">`)
        }

        //end==> calculation total

        //start==> calculation with tax, shipping_ charge, discount
        $(document).on('change', '.tax:visible, .shipping_charge:visible, .discount:visible', function () {
            calc_total()
        });
        //end==> calculation with tax, shipping_ charge, discount

        /**************end calculation from here********************/


        $(document).on('click', '.sale_btn:visible', function (e) {
            e.preventDefault();
            var findActiveClasee = $(".nav-tabs").find('.active').find('a').attr('href');
            var getEveryTR = $(".tab-content").find(findActiveClasee).find('.appendTr');
            var getCalculation = $(".claculation_section").find(findActiveClasee);

            var get_stock_detail_id = getEveryTR.find("input[name='stock_detail_id[]']").map(function () {
                return parseInt($(this).val());
            }).get();

            var product_id_arr = getEveryTR.find("input[name='product_id[]']").map(function () {
                return parseInt($(this).val());
            }).get();

            var warehouse_id_arr = getEveryTR.find("input[name='warehouse_id[]']").map(function () {
                return parseInt($(this).val());
            }).get();

            var attribute_id_arr = getEveryTR.find("input[name='attribute_id[]']").map(function () {
                return parseInt($(this).val());
            }).get();

            var attribute_map_id_arr = getEveryTR.find("input[name='attribute_map_id[]']").map(function () {
                return parseInt($(this).val());
            }).get();

            var quantity_arr = getEveryTR.find("input[name='quantity[]']").map(function () {
                return parseInt($(this).val());
            }).get();

            var per_price_arr = getEveryTR.find("input[name='per_price[]']").map(function () {
                return parseFloat($(this).val());
            }).get();

            var total_arr = getEveryTR.find("input[name='total[]']").map(function () {
                return parseFloat($(this).val());
            }).get();

            var sub_total = parseFloat(getCalculation.find(".html_subtotal").find('input').val());
            var final_total = getCalculation.find(".final_total").find('input').val();
            var tax = getCalculation.find(".tax").val();
            var shipping_charge = getCalculation.find(".shipping_charge").val();
            var discount = getCalculation.find(".discount").val();
            var pos_customer_id = $(".tab-content").find(findActiveClasee).find(".pos_customer_id :selected").val();
            var pos_customer_name = $(".tab-content").find(findActiveClasee).find(".pos_customer_id :selected").text();
            // var pos_customer_name = $(".pos_customer_id").find("option:selected").text();
            // var pos_customer_id = $(".pos_customer_id").val();
            // alert("Selected Text: " + pos_customer_name + " Value: " + pos_customer_id);

            $.post("{{ route('admin.store.sale.value') }}",
                {
                    pos_customer_id: pos_customer_id,
                    stock_detail_id: get_stock_detail_id,
                    product: product_id_arr,
                    warehouse: warehouse_id_arr,
                    attribute: attribute_id_arr,
                    attribute_map: attribute_map_id_arr,
                    quantity: quantity_arr,
                    per_price: per_price_arr,
                    total: total_arr,
                    sub_total: sub_total,
                    tax: tax,
                    shipping_charge: shipping_charge,
                    discount: discount,
                    final_total: final_total,
                    dataType: 'json',
                },
                function (res) {

                    //start==> if quantity not available
                    if (res.quantity_error) {
                        checkCurrentTab().find('.everyTr' + res.quantity_error.stock_detail_id).remove();
                        calc_total();
                        toastr.error('Thats product quantity not Available')
                    }
                    //end==> if quantity not available

                    if (res.success) {
                        toastr.success("Sale Create done");
                        //strat===> now we need to empty all
                        $(".tab-content").find(findActiveClasee).find(".pos_customer_id").val('');
                        $(".tab-content").find(findActiveClasee).find('.appendTr').html('');
                        getCalculation.find(".tax").val(0);
                        getCalculation.find(".shipping_charge").val(0);
                        getCalculation.find(".discount").val(0);
                        calc_total();
                        $(".reloadbyAjax").load(location.href + " .reloadbyAjax");
                        //strat===> now we need to empty all

                        $(".payment_model").modal('show');
                        $(".p_c_name").html(pos_customer_name + `<input type="hidden" class="p_pos_customer_id" value="` + pos_customer_id + `">`);
                        $(".sale_pro_item").text("Items:" + product_id_arr.length);
                        $(".sale_total").text(final_total);
                        $(".pay_input_field").val(final_total);
                        $(".last_sale_id").val(res.success);
                    }
                    if (res.errors) {
                        for (var i = 0; i < res.errors.length; i++) {
                            toastr.error(res.errors[i])
                        }
                    }
                });

        });


        $(".product_search_field").keyup(function () {
            var text = $(".product_search_field").val();
            if (text) {
                $.get("{{ route('admin.product.search.for.sale') }}", {text: text}, function (res) {
                    $('.reloadbyAjax').html(res);
                });
            }

        })


    </script>


@endpush
