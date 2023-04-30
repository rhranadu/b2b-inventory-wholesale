@if(isset($productReturn))
    <div class="row pb-5">
        <div class="col-md-12 d-flex justify-content-center">
            <button type="button" class="close btn btn-primary btn-lg" id="backFromEditRequest" style="color:blue;">
            <i class="fa fa-arrow-left"></i>Back
        </button></div>
    </div>
@endif
        <div class="card card-custom min-h-500px" id="kt_card_1">
            <div class="card-body">
                <form action="{{ route('admin.return.request.endpoint.submit') }}" id="product_return_form"
                    method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Type<span
                                    style="color: red; font-size: 18px">
                                    <sup>*</sup>
                                </span></label>
                                <select name="request_type" id="request_type" class="form-control">
                                    <option value="">Select</option>
                                    <option value="refund">Refund</option>
                                    <option value="exchange">Exchange</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Returned Product Barcode<span
                                    style="color: red; font-size: 18px">
                                    <sup>*</sup>
                                </span></label>
                                    <input type='hidden' value="{{ isset($productReturn) ? $productReturn->id : '' }}"
                                        name="id">
                                    <input type='text'
                                        value="{{ isset($productReturn) ? $productReturn->returned_stocked_product_barcode : '' }}"
                                        name="barcode" class='form-control' id="return_product_search_field">
                                    <span class="badge cursor-pointer badge-warning product-show mr-2" style="display:none">View
                                        Product</span>
                                    <span class="badge badge-danger product-not-found-alert" style="display:none">Invalid
                                        Product</span>
                                    <span class="badge badge-success product-found-alert" style="display:none">Valid
                                        Product</span>
                                    <span class="badge badge-info product-quantity-text-alert ml-2" style="display:none">Stocked
                                        Quantity <strong class="product-quantity-alert">0</strong></span>
                            </div>
                        </div>
                        <div class="col-md-2 instant_exchange_element">
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-outline checkbox-success">
                                        <input value="1" type="checkbox" id="instant_exchange_checkbox" name="instant_exchange">
                                        <span></span>
                                        Instant Exchange ?
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 instant_exchange_element"  style="display:none" id="exchanged_product_barcode_elem">
                            <div class="form-group">
                                <label for="">Exchanged Product Barcode<span
                                    style="color: red; font-size: 18px">
                                    <sup>*</sup>
                                </span></label>
                                <input type='text'
                                    value="{{ isset($productReturn) ? $productReturn->exchanged_stocked_product_barcode : '' }}"
                                    name="exchanged_stocked_product_barcode"
                                    id="exchanged_product_barcode"
                                    class='form-control'>
                                <span class="badge cursor-pointer badge-warning exchanged-product-show mr-2" style="display:none">View
                                    Product</span>
                                <span class="badge badge-danger product-not-found-alert" style="display:none">Invalid
                                    Product</span>
                                <span class="badge badge-success product-found-alert" style="display:none">Valid
                                    Product</span>
                                <span class="badge badge-info product-quantity-text-alert ml-2" style="display:none">Stocked
                                    Quantity <strong class="product-quantity-alert">0</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Reason<span
                                    style="color: red; font-size: 18px">
                                    <sup>*</sup>
                                </span></label>
                                <textarea name="reason"
                                    id="reason" class="form-control">{{ isset($productReturn) ? $productReturn->reason : '' }}</textarea>
                                @error('reason')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Comment</label>
                                <textarea name="comment"
                                    class="form-control">{{ isset($productReturn) ? $productReturn->comment : '' }}</textarea>
                                @error('description')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4" style="margin-top: 5px;">
                            <div class="form-group">
                                <button type="button" class="btn btn-success" id="submit_request_btn">Request</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>