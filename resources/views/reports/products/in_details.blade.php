@extends('layouts.app')

@section('title', 'Product Reports Details')

@push('css')

@endpush

@section('main_content')

    <!-- Breadcomb area Start-->
    <div class="breadcomb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcomb-list">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="breadcomb-wp">
                                    <div class="breadcomb-icon">
                                        <i class="notika-icon notika-windows"></i>
                                    </div>
                                    <div class="breadcomb-ctn">
                                        <h2>Product Reports</h2>
                                        <p>product details</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
                                <div class="breadcomb-report">
                                    <a href="{{ route('admin.report.products') }}" class="btn waves-effect" data-toggle="tooltip" data-placement="left" title="" data-original-title="Warehouse index"><i class="fa fa-list"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcomb area End-->


    <div class="area-chart-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="area-chart-wp">
                        <h4>Product Info</h4>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th class="text-center">Image</th>
                                        <th class="text-center" width="200">Name</th>
                                        <th class="text-center">Model</th>
                                        <th class="text-center">Brand</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Minimum Price</th>
                                        <th class="text-center">Maximum Price</th>
                                        <th class="text-center">Alert Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr class="text-center">
                                    <td>
                                        <img width="50" height="50" src="{{ asset($product->image_path) }}" alt="">
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->product_model }}</td>
                                    <td>{{ $product->productBrand->name }}</td>
                                    <td>{{ $product->productCategory->name }}</td>
                                    <td>{{ $product->min_price }}</td>
                                    <td><span class="badge">{{ $product->max_price }}</span></td>
                                    <td><span class="badge">{{ $product->alert_quantity }}</span></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @if (isset($product->productStockes) && count($product->productStockes) > 0)
                    <div class="area-chart-wp mg-t-30">
                        <h5>Product Info</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-condensed" id="data-table-basic">
                                <thead>
                                <tr>
                                    <th class="text-center">SI</th>
                                    <th class="text-center">Stocked Date</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Attribute</th>
                                    <th class="text-center">Warehouse</th>
                                    <th class="text-center">Stock Quantity</th>
                                    <th class="text-center">Purchases Price</th>
                                    <th class="text-center">Total Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($product->productStockes->where('quantity', '!=', 0) as $stock_product)
                                    <tr class="text-center">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $stock_product->created_at->isoFormat('MMM Do YY')   }}</td>
                                        <td>{{ $stock_product->product->name }}</td>
                                        <td>{{ $stock_product->productAttribute->name }} - {{ $stock_product->productAttributeMap->value }}</td>
                                        <td>{{ $stock_product->warehouse->name }}</td>
                                        <td>
                                            @if($stock_product->quantity > $product->alert_quantity )
                                                <span class="badge" style="background: green">{{ $stock_product->quantity }}</span>
                                            @elseif($stock_product->quantity == $product->alert_quantity)
                                                <span class="badge" style="background: orange">{{ $stock_product->quantity }}</span>
                                                @else
                                                <span class="badge" style="background: red">{{ $stock_product->quantity }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $stock_product->price }}</td>
                                        <td>{{ $stock_product->total }}</td>
                                        <td>
                                            @if($stock_product->price > $product->max_price)
                                                <a href="{{ route('admin.product.edit', [$stock_product->product_id, false]) }}" onclick="{{ Session::put('autofocusMax', true)  }}" class="btn btn-xs btn-danger">Price Reset</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                        <div class="alert alert-info text-center mg-t-30">No data found</div>
                    @endif
                </div>
            </div>

        </div>
    </div>

@endsection



@push('script')

@endpush
