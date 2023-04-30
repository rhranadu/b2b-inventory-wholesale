@extends('layouts.crud-master')
@section('title', 'Product Details')
@section('main_content')
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div
            class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">View Product</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-muted font-weight-bold mr-4">View a product of your company</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <!--begin::Actions-->
                <a
                    data-toggle="tooltip"
                    title="Add Product"
                    href="{{route('admin.product.parent')}}"
                    class="btn btn-light-success btn-sm btn-clean font-weight-bold font-size-base mr-1">
                    <i class="fa fa-plus"></i>Add Product
                </a>
                <a
                    data-toggle="tooltip"
                    title="Product List"
                    href="{{route('admin.product.index')}}"
                    class="btn btn-light-primary btn-sm btn-clean font-weight-bold font-size-base mr-1">
                    <i class="fa fa-list"></i>Product List
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
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered table-condensed">
                                    <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <td>{{ $product->name }}</td>
                                    </tr>
            
                                    <tr>
                                        <td>Image</td>
                                        <td>
                                            <img width="200" height="150" src="{{ asset($product->image_path) }}" alt="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Product Model</td>
                                        <td>{{ $product->product_model }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Details</td>
                                        <td>{{ $product->product_details }}</td>
                                    </tr>
                                    <tr>
                                        <td>Qr Code</td>
                                        <td>{{ $product->qr_code }}</td>
                                    </tr>
                                    <tr>
                                        <td>Model No</td>
                                        <td>{{ $product->model_no }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Specification</td>
                                        <td>{{ $product->product_specification }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tax</td>
                                        <td>{{ $product->productTax->percentage ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Category</td>
                                        <td>{{ $product->productCategory->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Brand</td>
                                        <td>{{ $product->productBrand->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Manufacturer</td>
                                        <td>{{ $product->productManufacturer->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Minimum Price</td>
                                        <td>{{ $product->min_price }}</td>
                                    </tr>
                                    <tr>
                                        <td>Maximum Price</td>
                                        <td>{{ $product->max_price }}</td>
                                    </tr>
                                    <tr>
                                        <td>POS Discount Price</td>
                                        <td>{{ $product->pos_discount_price }}</td>
                                    </tr>
                                    <tr>
                                        <td>Marketplace Discount Price</td>
                                        <td>{{ $product->marketplace_discount_price }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alert Quantity</td>
                                        <td>{{ $product->alert_quantity }}</td>
                                    </tr>
                                    <tr>
                                        <td>Created By</td>
                                        <td>{{ isset($product->productCreatedUser->name) ? $product->productCreatedUser->name : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Updated By</td>
                                        <td>{{ isset($product->productUpdatedUser->name) ? $product->productUpdatedUser->name : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Created At</td>
                                        <td>{{ $product->created_at->diffForHumans() }}</td>
                                    </tr>
                                    <tr>
                                        <td>Updated At</td>
                                        <td>{{ $product->updated_at->diffForHumans() }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
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

@endpush
