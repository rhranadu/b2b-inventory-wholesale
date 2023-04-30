@if (isset($childProductDetails))
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px">
                <div class="card-body">
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered table-condensed">
                                    <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <td>{{ $childProductDetails->name }}</td>
                                    </tr>

                                    <tr>
                                        <td>Image</td>
                                        <td>
                                            <img width="200" height="150" src="{{ isset($childProductDetails->childProductImage[0]) ? asset($childProductDetails->childProductImage[0]->x_100_path) : 'N/A' }}" alt="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Product Model</td>
                                        <td>{{ $childProductDetails->product_model }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Details</td>
                                        <td>{{ $childProductDetails->product_details }}</td>
                                    </tr>
                                    <tr>
                                        <td>Qr Code</td>
                                        <td>{{ $childProductDetails->qr_code }}</td>
                                    </tr>
                                    <tr>
                                        <td>Model No</td>
                                        <td>{{ $childProductDetails->model_no }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Specification</td>
                                        <td>{{ $childProductDetails->product_specification }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tax</td>
                                        <td>{{ $childProductDetails->productTax->percentage ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Category</td>
                                        <td>{{ $childProductDetails->productCategory->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Brand</td>
                                        <td>{{ $childProductDetails->productBrand->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Manufacturer</td>
                                        <td>{{ $childProductDetails->productManufacturer->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Minimum Price</td>
                                        <td>{{ $childProductDetails->min_price }}</td>
                                    </tr>
                                    <tr>
                                        <td>Maximum Price</td>
                                        <td>{{ $childProductDetails->max_price }}</td>
                                    </tr>
                                    <tr>
                                        <td>POS Discount Price</td>
                                        <td>{{ $childProductDetails->pos_discount_price }}</td>
                                    </tr>
                                    <tr>
                                        <td>Marketplace Discount Price</td>
                                        <td>{{ $childProductDetails->marketplace_discount_price }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alert Quantity</td>
                                        <td>{{ $childProductDetails->alert_quantity }}</td>
                                    </tr>
                                    <tr>
                                        <td>Created By</td>
                                        <td>{{ isset($childProductDetails->productCreatedUser->name) ? $childProductDetails->productCreatedUser->name : 
                                        'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Updated By</td>
                                        <td>{{ isset($childProductDetails->productUpdatedUser->name) ? $childProductDetails->productUpdatedUser->name : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Created At</td>
                                        <td>{{ $childProductDetails->created_at->diffForHumans() }}</td>
                                    </tr>
                                    <tr>
                                        <td>Updated At</td>
                                        <td>{{ $childProductDetails->updated_at->diffForHumans() }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->

@else
    <div class="alert alert-custom alert-outline-2x alert-outline-warning fade show mb-5"
         >
        <div class="alert-icon"><i class="flaticon-warning"></i></div>
        <div class="alert-text h4 mb-0">No data found</div>
    </div>
@endif
