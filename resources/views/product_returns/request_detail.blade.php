@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'ProductReturn Request Detail')
@push('css')
<style>
    .modal-full {
        min-width: 60%;
        margin-left: 80;
    }

    .modal-full .modal-content {
        min-height: 80vh;
    }
</style>
@endpush

@section('main_content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
            @if(isset($return_detail) && $return_detail->status == 'requested')
            <div class="col-xl-4">
                <!--begin::Mixed Widget 14-->
                <div class="card card-custom gutter-b card-stretch">
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="pt-5 dropdown">
                            <a href="#" data-key="{{ $return_detail->id }}" class="btn btn-success btn-shadow-hover font-weight-bolder py-3" id="show_approve_refund_modal">Approve Refund</a>
                            <a href="#" data-key="{{ $return_detail->id }}" class="btn btn-warning btn-shadow-hover dropdown-toggle font-weight-bolder py-3" id="exchange_request_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Approve Exchange</a>
                            <div class="dropdown-menu p-4" aria-labelledby="exchange_request_btn">
                                <div class="form-group">
                                    <label for="">Exchanged Product Barcode</label>
                                    <input type='text'
                                        name="exchanged_stocked_product_barcode"
                                        id="exchanged_product_barcode"
                                        class='form-control'>
                                    <span class="badge cursor-pointer badge-warning exchanged-product-show mr-2" style="display:none">View
                                        Product</span>
                                    <span class="badge badge-danger product-not-found-alert" style="display:none">Invalid
                                        Product</span>
                                    <span class="badge badge-success product-found-alert" style="display:none">Valid
                                        Product</span>
                                    <span class="badge badge-info product-quantity-text-alert ml-2" style="display:none">Stocked
                                        Quantity <strong class="product-quantity-alert">0</strong></span>
                                </div>
                                <button disabled data-key="{{ $return_detail->id }}" type="button" class="btn btn-primary" id="approve_exchange">Approve</button>
                            </div>
                            <a href="#" data-key="{{ $return_detail->id }}" class="btn btn-danger btn-shadow-hover font-weight-bolder py-3" id="reject_request">Reject Request</a>
                        </div>
                        {{-- <div class="pt-5">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-primary">
                                <input type="checkbox" name="product_restoct" id="product_restoct" checked="checked">
                                <span></span>Product Restock</label>
                            </div>
                        </div> --}}
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Mixed Widget 14-->
            </div>
            @endif
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
{{--                                <div class="font-size-h4 font-weight-bolder">{{ $return_stock_detail->quantity }}</div>--}}
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
        @if(isset($exchanged_barcode))
        <div class="row">
            <div class="col-xl-3">
                <!--begin::List Widget 13-->
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Header-->
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Exchanged Product Details</h3>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_product->name }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_barcode->bar_code }}</span>
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
                                    @foreach ($exchanged_product_attribute as $item)
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_product->product_model }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_product->productCategory->name }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_product->min_price }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_product->max_price }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_product->discountable_price }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale->invoice_no }}</span>
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
                                    @if(!empty($exchanged_sale->pos_customer_id))
                                    <span class="text-dark-50 font-weight-bolder">POS: {{ $exchanged_sale->posCustomer->name }}</span>
                                    @elseif(!empty($exchanged_sale->marketplace_user_id))
                                    <span class="text-dark-50 font-weight-bolder">Marketplace: {{ $exchanged_sale->marketplaceUser->name }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale->items }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale->sub_total }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale->tax}}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale->shipping_charge }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale->discount }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale->final_total }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale->status }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale->created_at }}</span>
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
                        <h3 class="card-title font-weight-bolder text-dark">Exchanged Product Sell Details</h3>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale_detail->warehouse->name }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale_detail->quantity }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale_detail->per_price }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale_detail->total }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $exchanged_sale_detail->created_at }}</span>
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
                        @foreach ($exchanged_sale_payment as $item)
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
        @endif
    </div>
</div>
@if(isset($return_detail))
<div class="modal fade" id="approve_refund_modal" role="dialog">
    <div class="modal-dialog modals-default modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="approve_refund_modal_body">
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
                <form action="{{ route('admin.return.request.action') }}" id="product_refund_form" method="post">
                    @csrf
                    <input type="hidden" name="product_return_id"/>
                    <input type="hidden" name="request_type"/>
                    <div class="appendModelContent">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="refund_sale_payment_by">Payment Type:</label>
                                    <select id="refund_sale_payment_by" name="refund_sale_payment_by" class="form-group form-control float-left payment_type">
                                        <option selected value="cash">Cash</option>
                                        <option value="card">Card</option>
                                        <option value="check">Check</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="refund_amount">Refund Amount</label>
                                    <input type='text' name="refund_sale_pay_amount" id="refund_amount" class='form-control pay_input_field' autocomplete="off" value={{ $return_sale_detail->per_price }}>
                                </div>
                            </div>
                        </div>
                        <div class="appendCardInput" style="display: none"></div>
                        <div class="appendCheckInput" style="display: none"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="approve_refund" data-key="{{ $return_detail->id }}">Submit Refund</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
<div class="modal fade" id="exchanged_product_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background: #81abe0 !important;">
                <h5 class="modal-title" style="color:white;">Product Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                    <i aria-hidden="true" class="fa fa-close"></i>
                </button>
            </div>

            <div class="modal-body">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<form action="{{ route('admin.return.request.action') }}" id="product_exchange_form" method="post">
    @csrf
    <input type="hidden" name="exchanged_barcode"/>
    <input type="hidden" name="product_return_id"/>
    <input type="hidden" name="request_type"/>
    <input type="submit" class="d-none" value="Submit">
</form>
<form action="{{ route('admin.return.request.reject') }}" id="product_reject_form" method="post">
    @csrf
    <input type="hidden" name="product_return_id" />
    <input type="hidden" name="request_type"/>
    <input type="submit" class="d-none" value="Submit">
</form>
@endsection

@push('script')
<script>
    $(document).off('click', '.exchanged-product-show').on('click', '.exchanged-product-show', function (event) {
        $.ajax({
            url: "{{ route('admin.return.prduct.not.sold.detail') }}",
            type: 'post',
            data: {barcode: $("#exchanged_product_barcode").val()},
            dataType: 'html',
            beforeSend: function(){
                $.blockUI();
            },
            complete: function(){
                $.unblockUI();
            },
            success: function(data) {
                $("#exchanged_product_modal .modal-body").html(data);
                $("#exchanged_product_modal").modal({show:true});
            },
            error: function(data) {


            }
        });
    });
    $("#exchanged_product_barcode").keyup(function () {
        var elem = $("#exchanged_product_barcode");
        var text = elem.val();
        if(text == 0 || text == ''){
            elem.siblings(".product-not-found-alert").hide();
            elem.siblings(".product-found-alert").hide();
            elem.siblings(".product-quantity-text-alert").hide();
            elem.siblings(".product-quantity-alert").hide();
            elem.siblings(".exchanged-product-show").hide();
            $("#approve_exchange").prop("disabled", true);
        }
        if (text) {
            $.post("{{ route('admin.exchanged.product.info.with.ajax') }}", {text: text}, function (res) {

                if (res.code == 0) {
                    ex_bar_check = false;
                    $("#approve_exchange").prop("disabled", true);
                    elem.siblings(".product-not-found-alert").text(res.error);
                    elem.siblings(".product-not-found-alert").show();
                    elem.siblings(".product-found-alert").hide();
                    elem.siblings(".product-quantity-text-alert").hide();
                    elem.siblings(".product-quantity-alert").hide();
                    elem.siblings(".exchanged-product-show").hide();
                } else {

                    ex_bar_check = true;
                    $("#approve_exchange").prop("disabled", false);
                    elem.siblings(".product-not-found-alert").hide();
                    elem.siblings(".exchanged-product-show").show();
                    elem.siblings(".product-found-alert").show();
                    elem.siblings(".product-quantity-text-alert").show();
                    elem.siblings(".product-quantity-text-alert").find('.product-quantity-alert').text(res.available_qty);
                    elem.siblings(".product-quantity-text-alert").find('.product-quantity-alert').show();

                }
            });
        }

    })
    $(document).on('change', ".payment_type", function () {
        var value = $(this).val();
        if (value == 'cash') {
            $(".appendCardInput").hide();
            $(".appendCheckInput").hide();
        } else if (value == 'card') {
            var html = `<div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Card Name</label>
                                    <input type='text' name="refund_sale_card_name" class='form-control card_name'>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Card Number</label>
                                    <input type='text' name="refund_sale_card_number" class='form-control card_number'>
                                </div>
                            </div>`;
            $(".appendCheckInput").hide();
            $(".appendCardInput").show().html(html);
        } else if (value == 'check') {
            var html = ` <div class="form-group">
                                <label for="formGroupExampleInput2">Check No</label>
                                <input type='text' name="refund_sale_check_no" class='form-control check_no'>
                            </div>`;

            $(".appendCheckInput").show().html(html);
            $(".appendCardInput").hide();
        }

    });

    $(document).off('click', '#approve_exchange').on('click', '#approve_exchange', function () {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButtonColor: '#00c292',
                cancelButton: 'btn btn-danger mt-0'
            },
            buttonsStyling: true
        });
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "This product will be exchanged with stocked product",
            type: 'warning',
            cancelButtonColor: "#AF0000",
            showCancelButton: true,
            confirmButtonText: 'Yes, Exchange it!'
        }).then((result) => {
            if (result.value) {
                $("#product_exchange_form").find('input[name="exchanged_barcode"]').val($("#exchanged_product_barcode").val());
                $("#product_exchange_form").find('input[name="product_return_id"]').val($(this).data('key'));
                $("#product_exchange_form").find('input[name="request_type"]').val('exchange');
                $('#product_exchange_form').submit();
            }
        })
    })
    $(document).off('click', '#show_approve_refund_modal').on('click', '#show_approve_refund_modal', function () {
        $("#approve_refund_modal").modal({'show':true});
    })
    $(document).off('click', '#approve_refund').on('click', '#approve_refund', function () {

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButtonColor: '#00c292',
                cancelButton: 'btn btn-danger mt-0'
            },
            buttonsStyling: true
        });
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "Refund will be submitted and this can not be reversed",
            type: 'warning',
            cancelButtonColor: "#AF0000",
            showCancelButton: true,
            confirmButtonText: 'Yes, Refund it!'
        }).then((result) => {
            if (result.value) {
                $("#product_refund_form").find('input[name="product_return_id"]').val($(this).data('key'));
                $("#product_refund_form").find('input[name="request_type"]').val('refund');
                $('#product_refund_form').submit();
            }
        })
    })
    $(document).off('click', '#reject_request').on('click', '#reject_request', function () {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButtonColor: '#00c292',
                cancelButton: 'btn btn-danger mt-0'
            },
            buttonsStyling: true
        });
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "This request will be rejected and this can not be reversed",
            type: 'warning',
            cancelButtonColor: "#AF0000",
            showCancelButton: true,
            confirmButtonText: 'Yes, Reject it!'
        }).then((result) => {
            if (result.value) {
                $("#product_reject_form").find('input[name="product_return_id"]').val($(this).data('key'));
                $("#product_reject_form").find('input[name="request_type"]').val('');
                $('#product_reject_form').submit();
            }
        })
    })
    $("#exchanged_product_barcode").trigger('keyup');
</script>
@endpush
