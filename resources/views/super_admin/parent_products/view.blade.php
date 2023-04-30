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
                                        <td>{{ $parentProduct->name }}</td>
                                    </tr>

                                    <tr>
                                        <td>Image</td>
                                        <td>
                                            <img class="pop_img" data-img ="{{ isset($parentProduct->childProductImage[0]) ? asset($parentProduct->childProductImage[0]->x_100_path) : 'N/A' }}" src="{{ isset($parentProduct->childProductImage[0]) ? asset($parentProduct->childProductImage[0]->x_100_path) : 'N/A' }}" alt="">
{{--                                            <img width="200" height="150" src="{{ asset($product->image_path) }}" alt="">--}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Product Model</td>
                                        <td>{{ $parentProduct->product_model }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Details</td>
                                        <td>{!! $parentProduct->product_details !!} </td>
                                    </tr>
                                    <tr>
                                        <td>Qr Code</td>
                                        <td>{{ $parentProduct->qr_code }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Specification</td>
                                        <td>
                                            @if ($parentProduct->product_specification)
                                                @foreach($parentProduct->product_specification as $singleItem)
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
                                        <td>Product Category</td>
                                        <td>{{ $parentProduct->productCategory->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Brand</td>
                                        <td>{{ $parentProduct->productBrand->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Manufacturer</td>
                                        <td>{{ isset($parentProduct->productManufacturer->name) ? $parentProduct->productManufacturer->name  : 'N/A' }}</td>
                                    </tr>
{{--                                    <tr>--}}
{{--                                        <td>Created By</td>--}}
{{--                                        {{ isset($parentProduct->createdBy->name) ? $parentProduct->createdBy->name  : 'N/A' }}--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>Updated By</td>--}}
{{--                                        {{ isset($parentProduct->updatedBy->name) ? $parentProduct->updatedBy->name  : 'N/A' }}--}}
{{--                                    </tr>--}}
                                    <tr>
                                        <td>Created At</td>
                                        <td>{{ $parentProduct->created_at->diffForHumans() }}</td>
                                    </tr>
                                    <tr>
                                        <td>Updated At</td>
                                        <td>{{ $parentProduct->updated_at->diffForHumans() }}</td>
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
