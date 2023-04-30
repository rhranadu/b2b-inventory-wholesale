
@if(Session::has('search_with_barcode') && Session::get('search_with_barcode'))
    @if($products->count() > 0)
    @foreach($products as $product)
        @if($product->productStockDetailFromBarcode != null)
            <div class="col-lg-3 cursor-pointer every_single_product{{$product->id}}"
                 onclick="everySingleProduct(this, {{ $product->id }})">
                <div class="card shadow-sm mb-3">

                    <div class="image_square_product pop_img"
                         style="background-image: url({{ asset($product->product->image_path) }});"
                         data-img="{{ asset($product->productStockDetailFromBarcode->product->image_path) }}">
                    </div>

                    <div class="product_desc py-3">
                        <h4 class="mb-0 font-weight-bold">{{$product->productStockDetailFromBarcode->product->name }}</h4>
                        <p class="mb-0">{{ $product->productStockDetailFromBarcode->productAttribute->name }}
                            - {{ $product->productStockDetailFromBarcode->productAttributeMap->value }}</p>
                    </div>
                    <div class="needValue" style="display: none">
                        <span class="stock_detail_id">{{ $product->productStockDetailFromBarcode->id }}</span>--}}
                        <span class="product_name">{{ $product->productStockDetailFromBarcode->product->name }}</span>
                        <span class="product_id">{{ $product->productStockDetailFromBarcode->product_id }}</span>
                        <span
                            class="attribute_name">{{ $product->productStockDetailFromBarcode->productAttribute->name }}</span>
                        <span class="attribute_id">{{ $product->productStockDetailFromBarcode->product_attribute_id }}</span>
                        <span
                            class="attribute_map_name">{{ $product->productStockDetailFromBarcode->productAttributeMap->value }}</span>
                        <span
                            class="attribute_map_id">{{ $product->productStockDetailFromBarcode->product_attribute_map_id }}</span>
                        <span class="warehouse_id">{{ $product->productStockDetailFromBarcode->warehouse_id }}</span>
                        <span class="available_quantity">{{ $product->productStockDetailFromBarcode->quantity }}</span>
                        <span class="per_price">{{ $product->productStockDetailFromBarcode->product->max_price }}</span>
                    </div>

                </div>
            </div>
        @endif
    @endforeach
    @else
        <div class="alert alert-info text-center">No data found</div>
    @endif
@else
    @if($products->count() > 0)
        @foreach($products as  $product_arr)
            @foreach($product_arr['productStockes'] as $product)
                <div class="col-lg-3 cursor-pointer every_single_product{{$product->id}}"
                     onclick="everySingleProduct(this, {{ $product->id }})">
                    <div class="card shadow-sm mb-3">

                        <div class="image_square_product pop_img"
                             style="background-image: url({{ asset($product->product->image_path) }});"
                             data-img="{{ asset($product->product->image_path) }}">
                        </div>

                        <div class="product_desc py-3">
                            <h4 class="mb-0 font-weight-bold">{{$product->product->name }}</h4>
                            <p class="mb-0">{{ $product->productAttribute->name }}
                                - {{ $product->productAttributeMap->value }}</p>
                        </div>
                        <div class="needValue" style="display: none">
                            <span class="stock_detail_id">{{ $product->id }}</span>--}}
                            <span class="product_name">{{ $product->product->name }}</span>
                            <span class="product_id">{{ $product->product_id }}</span>
                            <span
                                class="attribute_name">{{ $product->productAttribute->name }}</span>
                            <span class="attribute_id">{{ $product->product_attribute_id }}</span>
                            <span
                                class="attribute_map_name">{{ $product->productAttributeMap->value }}</span>
                            <span
                                class="attribute_map_id">{{ $product->product_attribute_map_id }}</span>
                            <span class="warehouse_id">{{ $product->warehouse_id }}</span>
                            <span class="available_quantity">{{ $product->quantity }}</span>
                            <span class="per_price">{{ $product->product->max_price }}</span>
                        </div>

                    </div>
                </div>

            @endforeach
        @endforeach
    @else
        <div class="alert alert-info text-center">No data found</div>
    @endif
@endif


