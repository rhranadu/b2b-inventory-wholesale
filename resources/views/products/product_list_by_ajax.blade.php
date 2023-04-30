@extends('layouts.ajax')
@section('title', 'Products')

@push('css')
<style>
    .table th, .table td{vertical-align:inherit;}
</style>
@endpush

@section('main_content')
    <div class="normal-table-list">
        <div class="bsc-tbl">
            @include('component.message')
            @if (isset($products) && count($products) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-condensed productTable"
                           id="data-table-basic">
                        <thead>
                            <tr>
                                <th class="text-center">SL</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Brand</th>
                                <th class="text-center">Manufacturer</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr class="text-center">
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ isset($product->name) ? $product->name : "N/A" }}</td>
                                <td>{{ isset($product->product_model) ? $product->product_model : "N/A" }}</td>
                                <td>{{ isset($product->productBrand->name) ? $product->productBrand->name : "N/A" }}</td>
                                <td>{{ isset($product->productManufacturer->name) ? $product->productManufacturer->name : "N/A" }}</td>
                                <td>{{ isset($product->productCategory->name) ? $product->productCategory->name : "N/A" }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.product.show', [$product->id, true]) }}"
                                           class="btn btn-sm btn-info"
                                           data-toggle="tooltip"
                                           data-placement="auto" title="" data-original-title=""><i
                                                class="fa fa-eye"></i> View</a>
                                        <a href="{{ route('admin.product.edit', [$product->id, true]) }}"
                                           class="btn btn-sm btn-primary"
                                           data-toggle="tooltip"
                                           data-placement="auto" title="" data-original-title="Use This Product"><i
                                                class="fas fa-plus"></i> Use This Product</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {!! $products->links() !!}
            @else
                <div class="alert alert-custom alert-outline-2x alert-outline-warning fade show mb-5">
                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                    <div class="alert-text h4 mb-0">No data found</div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('script')

@endpush
