
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-2"></div>
            <div class="col-xl-8">
                <!--begin::Base Table Widget 7-->
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Header-->
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body py-0 mt-n3">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table table-borderless table-vertical-center">
                                <thead>
                                    <tr>
                                        <th class="p-0" style="min-width: 150px"></th>
                                        <th class="p-0" style="min-width: 150px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="pl-0">
                                            <a href="#"
                                                class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Product
                                                Name</a>
                                        </td>
                                        <td class="text-right pr-0">
                                            <span
                                                class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $product->name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-0">
                                            <a href="#"
                                                class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Barcode</a>
                                        </td>
                                        <td class="text-right pr-0">
                                            <span
                                                class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $barcode_product->bar_code }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-0">
                                            <a href="#"
                                                class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Product
                                                Model</a>
                                        </td>
                                        <td class="text-right pr-0">
                                            <span
                                                class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $product->product_model }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-0">
                                            <a href="#"
                                                class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Stock
                                                Quantity</a>
                                        </td>
                                        <td class="text-right pr-0">
                                            <span
                                                class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $stock_detail->quantity }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-0">
                                            <a href="#"
                                                class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Attribute</a>
                                        </td>
                                        <td class="text-right pr-0">
                                            <span class="text-dark-75 font-weight-bolder d-block font-size-lg">
                                            @foreach ($product_attribute as $item)
                                                {{ $item->attribute_name }}: {{ $item->attribute_map_name }}@if ($loop->first) ; @endif
                                                @endforeach
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-0">
                                            <a href="#"
                                                class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Product
                                                Category</a>
                                        </td>
                                        <td class="text-right pr-0">
                                            <span
                                                class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $product->productCategory->name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-0">
                                            <a href="#"
                                                class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Min
                                                Price</a>
                                        </td>
                                        <td class="text-right pr-0">
                                            <span
                                                class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $product->min_price }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-0">
                                            <a href="#"
                                                class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Max
                                                Price</a>
                                        </td>
                                        <td class="text-right pr-0">
                                            <span
                                                class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $product->max_price }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-0">
                                            <a href="#"
                                                class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Discount</a>
                                        </td>
                                        <td class="text-right pr-0">
                                            <span
                                                class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $product->discountable_price }}</span>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Base Table Widget 7-->
            </div>
            <div class="col-xl-2"></div>
        </div>
    </div>
</div>