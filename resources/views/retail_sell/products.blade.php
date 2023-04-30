@foreach($products as $stock)
    <div class="col-xxl-2 col-xl-2 col-lg-3 col-sm-4 mb-6 every_single_product{{$stock->id}}" onclick="everySingleProduct(this, {{ $stock->id }})">
        <div class="card shadow-sm border-0 pos-item">
            <div class="card-body">
                @php($product_image = url('assets/media/products/18.png'))
                @php($stockQuantity = $stock->ppsd_stock_quantity - intval($stock->ppsd_stock_transfer_quantity ) -intval($stock->ppsd_mp_order_confirmed_quantity ) - intval($stock->ppsd_pos_order_submitted_quantity) - intval($stock->ppsd_exchange_quantity))
                @if ($stock->product->product_images->count() > 0)
                    @php($product_image = $stock->product->product_images->first()->x_300_url ?? $product_image)
                @endif
                <div style="background-image: url({!! $product_image !!})" class="card-thumb"></div>
                <span class="badge badge-primary" style="position: absolute;top: 5px;">{{ $stockQuantity }}</span>
            </div>
            <div class="card-footer p-3 text-center" style="min-height: 90px;">
                <div class="h7">{!! $stock->product->name !!}</div>
                <div class="badge badge-primary" style="white-space: break-spaces;">{!! $stock->product_attributes !!}</div>
                <div class="product-price">
                <span class="font-weight-bolder font-size-h7 text-success">
                    ৳ {{ (!empty($stock->product->pos_discount_price)) ? $stock->product->pos_discount_price :$stock->product->max_price }}
                </span>
                </div>
            </div>
        </div>
        <?php
        $barcode_ids = $stock->productBarcodesFromStockDetail->whereNull('sale_detail_id')->whereNotIn('id', $without_barcode);
	    $barcode_id_exact_barcode = null;
        if ($search_key) {
            $barcode_id_exact_barcode = $barcode_ids->where('bar_code', $search_key);
        }
        if (!empty($barcode_id_exact_barcode)) {
            $barcode_id = $barcode_id_exact_barcode->first();
        } else {
            $barcode_id = $barcode_ids->first();
        }
        ?>
        <div class="needValue" style="display: none">
            <span class="stock_detail_id">{{ $stock->id }}</span>
            <span class="stocked_product_barcode_id">{{ ($barcode_id) ? $barcode_id->id : 0 }}</span>
            <span class="product_name">{{ isset($stock->product->name) ? $stock->product->name : 'N/A' }}</span>
            <span class="product_id">{{ $stock->product_id }}</span>
            <span class="attribute_name">{{ $stock->productAttributes }}</span>
            <span class="attribute_id">{{ $stock->attribute_id }}</span>
            <span class="attribute_map_name">{{ $stock->productAttributes }}</span>
            <span class="attribute_map_id">{{ $stock->product_attribute_map_id }}</span>
            <span class="warehouse_id">{{ $stock->warehouse_id }}</span>
            <span class="available_quantity">{{ $stockQuantity }}</span>
            <span class="min_price">{{ $stock->product->min_price }}</span>
            @if (!empty($stock->product->pos_discount_price))
                <span class="per_price">{{ $stock->product->pos_discount_price }}</span>
            @else
                <span class="per_price">{{ $stock->product->max_price }}</span>
            @endif
            @if (!empty($stock->product->vat))
                <span class="vat">{{ $stock->product->vat }}</span>
            @else
                <span class="vat">0</span>
            @endif
        </div>
    </div>
@endforeach
