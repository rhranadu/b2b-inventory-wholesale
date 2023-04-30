@extends('layouts.crud-master')
@section('title', 'Product Details')
@section('main_content')

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
                                            <img class="pop_img" data-img ="{{ isset($product->childProductImage[0]) ? asset($product->childProductImage[0]->x_100_path) : 'N/A' }}" src="{{ isset($product->childProductImage[0]) ? asset($product->childProductImage[0]->x_100_path) : 'N/A' }}" alt="">
{{--                                            <img width="200" height="150" src="{{ asset($product->image_path) }}" alt="">--}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Product Model</td>
                                        <td>{{ $product->product_model }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Details</td>
                                        <td>{!! $product->product_details !!} </td>
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
                                        <td>
                                            @if ($product->product_specification)
                                                @foreach($product->product_specification as $singleItem)
                                                    <ul>
                                                        @if(isset($singleItem['label']))
                                                            <li>
                                                                Label - {{$singleItem['label']}} </br>
                                                                Value - {{$singleItem['value']}} </br>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                @endforeach
                                            @endif
                                        </td>
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
{{--                                        <td>{{ $product->productCreatedUser->name }}</td>--}}
                                    </tr>
                                    <tr>
                                        <td>Updated By</td>
                                        <td>{{ isset($product->productUpdatedUser->name) ? $product->productUpdatedUser->name : 'N/A' }}</td>
{{--                                        <td>{{ $product->productUpdatedUser->name }}</td>--}}
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
