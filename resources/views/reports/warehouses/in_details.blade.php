@extends('layouts.app')

@section('title', 'Warehouse Reports Details')

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
                                        <h2>Warehouse Reports</h2>
                                        <p>warehouse details</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
                                <div class="breadcomb-report">
                                    <a href="{{ route('admin.report.warehouses') }}" class="btn waves-effect" data-toggle="tooltip" data-placement="left" title="" data-original-title="Warehouse index"><i class="fa fa-list"></i></a>
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
                        <h4>Warehouse Info</h4>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered table-condensed">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $warehouse->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Type</th>
                                        <td>{{ $warehouse->type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $warehouse->address }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @if (isset($warehouse->products) && count($warehouse->products) > 0)
                    <div class="area-chart-wp mg-t-30">
                        @include('component.message')
                        <h4>Product Info</h4>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-condensed" id="data-table-basic">
                                <thead>
                                <tr>
                                    <th class="text-center">SI</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Attribute</th>
                                    <th class="text-center">Stock Quantity</th>
                                    <th class="text-center">Purchases Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($warehouse->products->where('status', '!=', 'die') as $product)
                                    <tr class="text-center" @if($product->quantity == 0) style="background-color: #ffa0a0 @endif">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $product->product->name }}</td>
                                        <td>{{ $product->productAttribute->name }} - {{ $product->productAttributeMap->value }}</td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>
                                            @if($product->status == 'report_to_admin')
                                                <a class="btn btn-sm btn-danger"  title="Finish"  href="{{ route('admin.report.warehouse.detail.product.finish',$product->id) }}"> Finish </a>
                                           {{-- @elseif(Auth::user()->user_type_id == 2 && $product->quantity == 0)
                                                <a class="btn btn-sm btn-danger"  title="Finish"  href="{{ route('admin.report.warehouse.detail.product.finish',$product->id) }}"> Finish </a>--}}
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


    <div class="area-chart-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @if (isset($transfer_products_lists) && count($transfer_products_lists) > 0)
                    <div class="area-chart-wp mg-t-30">
                        @include('component.message')
                        <h4>Product Transfer Info</h4>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-condensed" id="data-table-basic">
                                <thead>
                                <tr>
                                    <th class="text-center">SI</th>
                                    <th class="text-center">Delivery Date</th>
                                    <th class="text-center">Receive Date</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Attribute</th>
                                    <th class="text-center">From Warehouse</th>
                                    <th class="text-center">Delivery Quantity</th>
                                    <th class="text-center">Receive Quantity</th>
                                    <th class="text-center">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($transfer_products_lists as $transfer_products)
                                    <tr class="text-center">
                                        <td>{{  $loop->index + 1 }}</td>
                                        <td>{{  $transfer_products->created_at->isoFormat('Do MMM YY') }}</td>
                                        <td>{{  $transfer_products->updated_at->isoFormat('Do MMM YY') }}</td>
                                        <td>{{  $transfer_products->product->name }}</td>
                                        <td>{{  $transfer_products->productAttribute->name }} - {{  $transfer_products->productAttributeMap->value }}</td>
                                        <td>{{  $transfer_products->fromWarehouse->name }}</td>
                                        <td>{{  $transfer_products->delivery_quantity }}</td>
                                        <td width="250">
                                            @if($transfer_products->status != 'full_receive')
                                                <span class="badge">{{ $transfer_products->receive_quantity }}</span>
                                            @else
                                                <span class="badge">{{ $transfer_products->receive_quantity }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transfer_products->status = 'full_receive')
                                                <span class="badge" style="background-color: green">Full Receive</span>
                                            @elseif($transfer_products->status == 'partial_receive')
                                                <span class="badge" style="background-color: orange">Partial Receive</span>
                                            @else
                                                <span class="badge" style="background-color: red">NY</span>
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
