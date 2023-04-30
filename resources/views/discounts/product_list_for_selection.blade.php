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
                    <form id="form_export_child_product_id" method="post" action="">
                    <table class="table table-hover table-bordered table-condensed productTable"
                           id="product_table">
                        <thead>
                        <tr>
                            <th>
                                <div class="form-check custom_checkbox text-center">
                                    <input class="form-check-input checkbox-all" type="checkbox" id="checkbox-discount-product">
                                    <label class="form-check-label" for="checkbox-discount-product"></label>
                                </div>
                            </th>
                            <th class="text-center">SI</th>
                            {{-- <th class="text-center">Image</th> --}}
                            <th class="text-center">Name</th>
                            <th class="text-center">Vendor</th>
                            <th class="text-center">Parent Product</th>
                            <th class="text-center">Model</th>
                            <th class="text-center">Brand</th>
                            <th class="text-center">Manufacturer</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Status</th>
{{--                            <th class="text-center">Action</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr class="text-center">
                                <td>
                                    <div class="form-check custom_checkbox">
                                        <input  name="ids[]" class="form-check-input checkbox-discount-product export-discount-product" type="checkbox" id="checkbox-discount-product-{{ $product->id }}" data-id="{{ $product->id }}" value="{{ $product->id }}">
                                        <label class="form-check-label" for="checkbox-discount-product-{{ $product->id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $loop->index + 1 }}</td>
                                @if(isset($product->name))
                                <td>
                                    <a href="#"
                                        data-toggle="tooltip"
                                        data-child_product_id = {{ $product->id }}
                                        class="childProductDetails"
                                        data-placement="auto" title="" data-original-title="">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                @else
                                <td>N/A</td>
                                @endif
                                <td>{{ isset($product->productVendor->name) ? $product->productVendor->name : "N/A" }}</td>
                                <td>{{ isset($product->parentProduct->name) ? $product->parentProduct->name : "N/A" }}</td>
                                <td>{{ isset($product->product_model) ? $product->product_model : "N/A" }}</td>
                                <td>{{ isset($product->productBrand->name) ? $product->productBrand->name : "N/A" }}</td>
                                <td>{{ isset($product->productManufacturer->name) ? $product->productManufacturer->name : "N/A" }}</td>
                                <td>{{ isset($product->productCategory->name) ? $product->productCategory->name : "N/A" }}</td>
                                <td>{{ isset($product->status) ? 'Active' : "Inactive" }}</td>
{{--                                <td>--}}
{{--                                    <div class="btn-group">--}}
{{--                                        <a href="#"--}}
{{--                                           class="btn btn-sm btn-info btn-icon select_product"--}}
{{--                                           data-toggle="tooltip"--}}
{{--                                           data-id="{{$product->id}}"--}}
{{--                                           data-placement="auto" title="" data-original-title="Select"><i--}}
{{--                                                class="fa fa-check"></i></a>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
                            </tr>
                        @endforeach
                        <td colspan="12">
                            <button type="button" class="btn btn-primary select_product" value="active"  >Select Product</button>
                        </td>
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
<script>

    $('.checkbox-all').change(function () {
        if ($(this).is(':checked')) {
            $(this).closest('table').find('.checkbox-discount-product').each(function () {
                $(this).prop('checked', true);
            });
        } else {
            $(this).closest('table').find('.checkbox-discount-product').each(function () {
                $(this).prop('checked', false);
            });
        }
    });

</script>
