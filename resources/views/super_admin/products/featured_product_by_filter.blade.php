
        <thead>
        <tr>
            <th>
                <div class="form-check custom_checkbox text-center">
                    <input class="form-check-input checkbox-all" type="checkbox" id="checkbox-featured-product">
                    <label class="form-check-label" for="checkbox-featured-product"></label>
                </div>
            </th>
            <th class="text-center">SI</th>
            <th class="text-center">Name</th>
            <th class="text-center">Category</th>
            <th class="text-center">Model</th>
            <th class="text-center">Featured</th>
            <th class="text-center">Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($featuredProducts as $featuredProduct)
            <tr class="text-center">
                <td>
                    <div class="form-check custom_checkbox">
                        <input  name="ids[]" class="form-check-input checkbox-featured-product export-featured-product" type="checkbox" id="checkbox-featured-product-{{ $featuredProduct->id }}" data-id="{{ $featuredProduct->id }}" value="{{ $featuredProduct->id }}">
                        <label class="form-check-label" for="checkbox-featured-product-{{ $featuredProduct->id }}"></label>
                    </div>
                </td>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $featuredProduct->name }}</td>
                {{--                                <td>{{ isset($featuredProduct->productVendor->name) ? $featuredProduct->productVendor->name : 'N/A' }}</td>--}}
                <td>{{ isset($featuredProduct->productCategory->name) ? $featuredProduct->productCategory->name : 'N/A' }}</td>
                <td>{{ $featuredProduct->product_model }}</td>
                <td id="featured">
                    <a href="#0" id="ActiveUnactive" statusCode="{{ $featuredProduct->is_featured }}"
                       data_id="{{ $featuredProduct->id }}"
                       style="{{ $featuredProduct->is_featured == 1 ? 'background: #00c292' : 'background: red' }} "
                       class="badge badge-primary">
                        {{ $featuredProduct->is_featured == 1 ? 'Active' : 'Deactive'  }}
                    </a>
                </td>
                <td id="status">
                    <a href="#0" id="ActiveUnactive" statusCode="{{ $featuredProduct->status }}"
                       data_id="{{ $featuredProduct->id }}"
                       style="{{ $featuredProduct->status == 1 ? 'background: #00c292' : 'background: red' }} "
                       class="badge badge-primary">
                        {{ $featuredProduct->status == 1 ? 'Active' : 'Deactive'  }}
                    </a>
                </td>
            </tr>
        @endforeach
        <td colspan="12">
            <button type="button" class="btn btn-primary featuredProductSubmit" value="active"  >Active Featured Product</button>
            <button type="button" class="btn btn-primary featuredProductSubmit" value="deactive" >Deactive Featured Product</button>
        </td>

        </tbody>

