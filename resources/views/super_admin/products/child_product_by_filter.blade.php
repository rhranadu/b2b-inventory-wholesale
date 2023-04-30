

<form id="exportChildProductsId" method="" action="">
    @csrf
    <table class="table table-hover table-bordered table-condensed " id="data-table-basic">
        <thead>
        <tr>
            <th>
                <div class="form-check custom_checkbox">
                    <input class="form-check-input checkbox-all" type="checkbox" id="checkbox-child-product">
                    <label class="form-check-label" for="checkbox-new-order"></label>
                </div>
            </th>
            <th class="text-center">SI</th>
            <th class="text-center">Name</th>
            <th class="text-center">Parent Product</th>
            <th class="text-center">Vendor</th>
            <th class="text-center">Category</th>
            <th class="text-center">Model</th>
            <th class="text-center">Sku</th>
            <th class="text-center">Status</th>
            <th class="text-center" width="200">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($childProducts as $childProduct)
        <tr class="text-center">
            <td>
                <div class="form-check custom_checkbox">
                    <input  name="id[]" class="form-check-input checkbox-child-product export-child-product" type="checkbox" id="checkbox-child-product-{{ $childProduct->id }}" data-id="{{ $childProduct->id }}" value="{{ $childProduct->id }}">
                    <label class="form-check-label" for="checkbox-child-product-{{ $childProduct->id }}"></label>
                </div>
            </td>
            <td>{{ $loop->index + 1 }}</td>
            <td>{{ $childProduct->name }}</td>
            <td>{{ isset($childProduct->parentProduct->name) ? $childProduct->parentProduct->name : 'N/A' }}</td>
            <td>{{ isset($childProduct->productVendor->name) ? $childProduct->productVendor->name : 'N/A' }}</td>
            <td>{{ isset($childProduct->productCategory->name) ? $childProduct->productCategory->name : 'N/A' }}</td>
            <td>{{ $childProduct->product_model }}</td>
            <td>{{ $childProduct->sku }}</td>
            <td id="status">
                <a href="#0" id="ActiveUnactive" statusCode="{{ $childProduct->status }}"
                   data_id="{{ $childProduct->id }}"
                   style="{{ $childProduct->status == 1 ? 'background: #00c292' : 'background: red' }} "
                   class="badge badge-primary">
                    {{ $childProduct->status == 1 ? 'Active' : 'Deactive'  }}
                </a>
            </td>
            <td>
                <div class="btn-group">
                    <a href="#"
                       class="btn btn-sm btn-info btn-icon childProductDetails"
                       data-toggle="tooltip"
                       data-child_product_id="{{$childProduct->id}}"
                       data-placement="auto" title="" data-original-title="VIEW"><i
                            class="fa fa-eye"></i> </a>
                </div>
            </td>
        </tr>
        @endforeach
        <td colspan="12">
            <button type="submit" class="btn btn-primary" onclick="Export()">Map Parent Product</button>
        </td>
        </tbody>
    </table>
</form>
