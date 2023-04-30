@extends('layouts.crud-master')
@section('main_content')
    <div class="card card-custom min-h-500px">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Product Stocks Details
                    <small>product Stocks details</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.product.index') }}" class="btn btn-sm btn-light-primary"
                   data-card-tool="remove" data-toggle="tooltip" data-placement="top" title="Manufacturer list">
                    <i class="fa fa-plus"></i> Product List
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('component.message')
            <div class="normal-table-list">
                <div class="bsc-tbl">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-condensed" id="data-table-basic">
                            <thead>
                            <tr>
                                <th class="text-center">Product Name</th>
                                <th class="text-center">Attibute</th>
                                <th class="text-center">Warehouse Name</th>
                                <th class="text-center">Stored Quantity</th>
                                <th class="text-center">Purchases Price</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($stock_details->productStockDetails as $stock_detail)
                                <tr class="text-center">
                                    <td>{{ $stock_detail->product->name }}</td>
                                    <td>{{ $stock_detail->productAttribute->name }}
                                        - {{ $stock_detail->productAttributeMap->value }}</td>
                                    <td>{{ $stock_detail->warehouse->name }}</td>
                                    <td>{{ $stock_detail->quantity }}</td>
                                    <td>{{ $stock_detail->price }}</td>
                                    <td>{{ $stock_detail->total }}</td>
                                    <td>{{ $stock_detail->purchasesDetailsStatus->status }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')

@endpush
