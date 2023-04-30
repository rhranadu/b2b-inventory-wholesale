<form id="exportparentProductId" method="" action="">
    @csrf
    <table class="table table-hover table-bordered table-condensed " id="data-table-basic">
        <thead>
        <tr>
            <th class="text-center">SI</th>
            <th class="text-center">Name</th>
            <th class="text-center">Category</th>
            <th class="text-center">Model</th>
            <th class="text-center">Sku</th>
            <th class="text-center">Status</th>
            <th class="text-center" width="200">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($parentProducts as $parentProduct)
            <tr class="text-center">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $parentProduct->name }}</td>
                <td>{{ isset($parentProduct->productCategory->name) ? $parentProduct->productCategory->name : 'N/A' }}</td>
                <td>{{ $parentProduct->product_model }}</td>
                <td>{{ $parentProduct->sku }}</td>
                <td id="status">
                    <a href="#0" id="ActiveUnactive" statusCode="{{ $parentProduct->status }}"
                       data_id="{{ $parentProduct->id }}"
                       style="{{ $parentProduct->status == 1 ? 'background: #00c292' : 'background: red' }} "
                       class="badge badge-primary">
                        {{ $parentProduct->status == 1 ? 'Active' : 'Deactive'  }}
                    </a>
                </td>
                <td>
                    <div class="btn-group">
                        <a href="#"
                           class="btn btn-sm btn-info btn-icon parentProductSelect"
                           data-toggle="tooltip"
                           data-parent_product_id="{{$parentProduct->id}}"
                           data-placement="auto" title="" data-original-title="Select"><i
                                class="fa fa-edit"></i> </a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</form>
