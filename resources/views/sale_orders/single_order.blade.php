@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Sale Order Details')
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
            <div class="col-xl-4">
                <!--begin::List Widget 13-->
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Header-->
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Product Details</h3>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $product['name'] }}</span>
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
                                    <span class="text-dark-50 font-weight-bolder">{!! $saleDetail['product_attributes_pair'] !!}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Model
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{!! $product['product_model'] !!}</span>
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
            <div class="col-xl-4">
                <!--begin::List Widget 13-->
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Header-->
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Invoice Details</h3>
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
                                    <span class="text-dark-50 font-weight-bolder">{{ $singleSale['invoice_no'] }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Quantity</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $saleDetail['quantity'] }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
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
                                    <span class="text-dark-50 font-weight-bolder">{{ number_format($saleDetail['per_price'], 2) }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Total Price</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ number_format($saleDetail['total'], 2) }}</span>
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
            <div class="col-xl-4">
                <!--begin::List Widget 13-->
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Header-->
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Order Status</h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-2">
                        <!--begin::Item-->
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Current Status</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $saleOrder['status'] }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Submitted At</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $saleOrder['submitted_at'] }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Item-->

                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Confirmed At</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $saleOrder['confirmed_at'] }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Processed At</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $saleOrder['processed_at'] }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Shipped At</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $saleOrder['shipped_at'] }}</span>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <!--begin::Title-->
                            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
                                <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Delivered At</a>
                            </div>
                            <!--end::Title-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mt-lg-0 mt-3">
                                <!--begin::Label-->
                                <div class="mr-6">
                                    <span class="text-dark-50 font-weight-bolder">{{ $saleOrder['delivered_at'] }}</span>
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

        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card card-custom min-h-500px" id="kt_card_1">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Confirmed Barcode</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                class="table table-hover table-bordered table-condensed" id="datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">SL</th>
                                        <th class="text-center">Barcode</th>
                                        <th class="text-center">Warehouse</th>
                                        <th class="text-center">Warehouse type</th>
                                        <th class="text-center">Warehouse Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barcodes as $bc)
                                    <tr class="text-center">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $bc['bar_code'] }}</td>
                                        <td>{{ $bc['product_pool_stock_detail']['warehouse']['name'] }}</td>
                                        <td>{{ $bc['product_pool_stock_detail']['warehouse']['type'] }}</td>
                                        <td>{{ isset($bc['product_pool_stock_detail']['warehouse_detail']) ? $bc['product_pool_stock_detail']['warehouse_detail']['section_name'] : 'N/A'}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
var datatable =   $('#datatable').DataTable({})
</script>
@endpush
