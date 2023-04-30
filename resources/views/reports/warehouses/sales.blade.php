@extends('layouts.app')

@section('title', 'Warehouses Reports')

@push('css')

@endpush



@section('main_content')
    <div class="normal-table-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            @include('component.message')
                            @if (isset($warehouse->warehouseSales) && count($warehouse->warehouseSales) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-condensed" id="data-table-basic">
                                        <thead>
                                        <tr>
                                            <th class="text-center">SI</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Product Name</th>
                                            <th class="text-center">Attribute</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Par price</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach($warehouse->warehouseSales as $warehouse_sale)
                                            <tr class="text-center">
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $warehouse_sale->created_at->isoFormat('Do MMM YY') }}</td>
                                                <td>{{ $warehouse_sale->product->name }}</td>
                                                <td>{{ $warehouse_sale->attribute->name }}  -  {{ $warehouse_sale->attributeMap->value }}</td>
                                                <td>{{ $warehouse_sale->quantity }}</td>
                                                <td class="text-right">{{ number_format($warehouse_sale->per_price, 2) }}</td>
                                                <td class="text-right">{{number_format( $warehouse_sale->total, 2) }}</td>
                                            </tr>
                                            @php
                                                $total += $warehouse_sale->total;
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td colspan="6" class="text-right">Total</td>
                                            <td colspan="6" class="text-right">{{  number_format($total, 2) }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center">No data found</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection



@push('script')

@endpush
