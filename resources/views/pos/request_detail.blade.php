<div class="row pb-5">
    <div class="col-md-12 d-flex justify-content-center">
        <button type="button" class="close btn btn-primary btn-lg" id="{{ $from_return_request ? 'backFromRequestDetail' : 'backFromReturnedProductDetail'}}" style="color:blue;">
        <i class="fa fa-arrow-left"></i>Back
    </button></div>
</div>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body pt-2">
                        <div class="row m-0">
                            <div class="col px-8 py-6 mr-8">
                                <div class="font-size-sm text-muted font-weight-bold">Product Name</div>
                                <div class="font-size-h4 font-weight-bolder">{{ $return_product->name }}</div>
                            </div>
                            <div class="col px-8 py-6">
                                <div class="font-size-sm text-muted font-weight-bold">Barcode</div>
                                <div class="font-size-h4 font-weight-bolder">{{ $return_barcode->bar_code }}</div>
                            </div>
                            <div class="col px-8 py-6">
                                <div class="font-size-sm text-muted font-weight-bold">Stock</div>
                                <div class="font-size-h4 font-weight-bolder">{{ $return_stock_detail->quantity }}</div>
                            </div>
                            @if(isset($return_detail))
                            <div class="col px-8 py-6">
                                <div class="font-size-sm text-muted font-weight-bold">Requested For</div>
                                <div class="font-size-h4 font-weight-bolder">{{ $return_detail->request_type }}</div>
                            </div>
                            @if($return_detail->status == "approved")
                            <div class="col px-8 py-6">
                                <div class="font-size-sm text-muted font-weight-bold">Approved For</div>
                                <div class="font-size-h4 font-weight-bolder">{{ $return_detail->approved_request_type }}</div>
                            </div>
                            @endif
                            <div class="col px-8 py-6">
                                <div class="font-size-sm text-muted font-weight-bold">Status</div>
                                <div class="font-size-h4 font-weight-bolder">{{ $return_detail->status }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3">
                <!--begin::List Widget 13-->
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Header-->
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Returned Product Details</h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-2">
                        <!--begin::Item-->
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Name</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_product->name }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Barcode</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_barcode->bar_code }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Attribute</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    @foreach ($return_product_attribute as $item)
                                        <span class="text-dark-50 font-weight-bolder">{{ $item->attribute_name }}: {{ $item->attribute_map_name }}</span>
                                        @if ($loop->first)
                                            <span class="text-dark-50 font-weight-bolder">; </span>
                                        @endif
                                    @endforeach
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Model</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_product->product_model }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Category</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_product->productCategory->name }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Min Price</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_product->min_price }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Max Price</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_product->max_price }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Discount Price</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_product->discountable_price }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::List Widget 13-->
            </div>
            <div class="col-xl-3">
                <!--begin::List Widget 13-->
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Header-->
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Invoice Sell Details</h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-2">
                        <!--begin::Item-->
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Invoice No</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale->invoice_no }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">User Name</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    @if(!empty($return_sale->pos_customer_id))
                                    <span class="text-dark-50 font-weight-bolder">POS: {{ $return_sale->posCustomer->name }}</span>
                                    @elseif(!empty($return_sale->marketplace_user_id)) 
                                    <span class="text-dark-50 font-weight-bolder">Marketplace: {{ $return_sale->marketplaceUser->name }}</span>
                                    @else
                                    <span class="text-dark-50 font-weight-bolder">N/A</span>
                                    @endif
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Sold Items</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale->items }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Subtotal</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale->sub_total }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Tax</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale->tax}}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Shipping Charge</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale->shipping_charge }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Sell Discount</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale->discount }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Final Total</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale->final_total }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Status</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale->status }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Created At</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale->created_at }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::List Widget 13-->
            </div>
            <div class="col-xl-3">
                <!--begin::List Widget 13-->
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Header-->
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Returned Product Sell Details</h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-2">
                        <!--begin::Item-->
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Warehouse</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale_detail->warehouse->name }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Sold Quantity</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale_detail->quantity }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Per Price</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale_detail->per_price }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Total</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale_detail->total }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Created At</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $return_sale_detail->created_at }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::List Widget 13-->
            </div>
            <div class="col-xl-3">
                <!--begin::List Widget 13-->
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Header-->
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Sell Payment By Invoice</h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-2">
                        @foreach ($return_sale_payment as $item)
                            <!--begin::Item-->
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <!--begin::Title-->
                                <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                    <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Total</a>
                                </div>
                                <!--end::Title-->
                                <!--begin::Section-->
                                <div class="d-flex align-items-center mt-lg-0 mt-3">
                                    <!--begin::Label-->
                                    <div class="mr-6">
                                        <span class="text-dark-50 font-weight-bolder">{{ $item->final_total }}</span>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <!--begin::Title-->
                                <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                    <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Payment By</a>
                                </div>
                                <!--end::Title-->
                                <!--begin::Section-->
                                <div class="d-flex align-items-center mt-lg-0 mt-3">
                                    <!--begin::Label-->
                                    <div class="mr-6">
                                        <span class="text-dark-50 font-weight-bolder">{{ $item->payment_by }}</span>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <!--begin::Title-->
                                <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                    <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Pay Amount</a>
                                </div>
                                <!--end::Title-->
                                <!--begin::Section-->
                                <div class="d-flex align-items-center mt-lg-0 mt-3">
                                    <!--begin::Label-->
                                    <div class="mr-6">
                                        <span class="text-dark-50 font-weight-bolder">{{ $item->pay_amount }}</span>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <!--begin::Title-->
                                <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                    <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Due Amount</a>
                                </div>
                                <!--end::Title-->
                                <!--begin::Section-->
                                <div class="d-flex align-items-center mt-lg-0 mt-3">
                                    <!--begin::Label-->
                                    <div class="mr-6">
                                        <span class="text-dark-50 font-weight-bolder">{{ $item->due_amount }}</span>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <!--begin::Title-->
                                <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                    <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Give Back</a>
                                </div>
                                <!--end::Title-->
                                <!--begin::Section-->
                                <div class="d-flex align-items-center mt-lg-0 mt-3">
                                    <!--begin::Label-->
                                    <div class="mr-6">
                                        <span class="text-dark-50 font-weight-bolder">{{ $item->give_back }}</span>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <!--begin::Title-->
                                <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                    <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Status</a>
                                </div>
                                <!--end::Title-->
                                <!--begin::Section-->
                                <div class="d-flex align-items-center mt-lg-0 mt-3">
                                    <!--begin::Label-->
                                    <div class="mr-6">
                                        <span class="text-dark-50 font-weight-bolder">{{ $item->status }}</span>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <!--begin::Title-->
                                <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                    <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Comment</a>
                                </div>
                                <!--end::Title-->
                                <!--begin::Section-->
                                <div class="d-flex align-items-center mt-lg-0 mt-3">
                                    <!--begin::Label-->
                                    <div class="mr-6">
                                        <span class="text-dark-50 font-weight-bolder">{{ $item->comment }}</span>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Section-->
                            </div>
                            @if ($loop->first)
                                <hr>
                            @endif
                        @endforeach
                        <!--end::Item-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::List Widget 13-->
            </div>
        </div>
    </div>
</div>