@extends('layouts.app')

@section('title', 'Supplier Reports')

@push('css')

@endpush



@section('main_content')
    <div class="normal-table-area">
        <div class="normal-table-list">
            <div class="bsc-tbl">
                @include('component.message')
                @if (isset($stocks_products) && count($stocks_products) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-condensed" id="data-table-basic">
                            <thead>
                            <tr>
                                <th class="text-center">SI</th>
                                <th class="text-center" width="150">Product Name</th>
                                <th class="text-center" width="150">Attribute Name</th>
                                <th class="text-center" width="150">Attribute Map</th>
                                <th class="text-center" width="150">Warehouse Name</th>
                                <th class="text-center" width="150">Available Quantity</th>
                                <th class="text-center" width="150">Alert Quantity</th>
                                <th class="text-center" width="150">Purchases Price</th>
                                <th class="text-center" width="150">Salling Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($stocks_products as $stocks_product)
                                <tr class="text-center">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $stocks_product->product->name }}</td>
                                    <td>{{ $stocks_product->productAttribute->name }}</td>
                                    <td>{{ $stocks_product->productAttributeMap->value }}</td>
                                    <td>{{ $stocks_product->productStockWarehouse->name }}</td>
                                    <td>{{ $stocks_product->quantity }}</td>
                                    <td>{{ $stocks_product->alert_qty }}</td>
                                    <td>{{ \App\ProductStock::stockSingleProductPurchasePrice($stocks_product->product_id, $stocks_product->purchase_id, $stocks_product->attribute_id, $stocks_product->product_attribute_map_id)->purchase_price }}</td>
                                    <td>{{ \App\ProductStock::stockSingleProductPurchasePrice($stocks_product->product_id, $stocks_product->purchase_id, $stocks_product->attribute_id, $stocks_product->product_attribute_map_id)->max_price }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{--                                {!! $stocks_products->links() !!}--}}
                @else
                    <div class="alert alert-info text-center">No data found</div>
                @endif
            </div>
        </div>
    </div>

@endsection



@push('script')

@endpush
