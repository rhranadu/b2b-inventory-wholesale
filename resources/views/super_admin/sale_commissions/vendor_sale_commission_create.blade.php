<div class="card card-custom mb-2" id="kt_card_1">
    <div class="card-body">
        <div class="normal-table-list">
            <div class="bsc-tbl">
                <form method="POST" id="vendorForm" action="{{ route('superadmin.commission.vendor.store') }}"
                    accept-charset="UTF-8" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="vendorStoreId" value={{ $commission->id }} />
                    <div class="row align-items-center">
                        <div class="form-group col-md-3">
                            <label for="#">Vendor</label>
                            <select name="vendor_id" id="storeVendor" class="form-control"
                                data-live-search="true">
                                @foreach ($vendors as $k=>$v)
                                <option {{ $k == $commission->vendor_id ? 'selected' : '' }} value={{ $k }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="#">Commission (%)</label>
                            <div class="input-group">
                                <input type="number" name="commission_percentage" class="form-control" id="commissionPercentage" value="{{ $commission->commission_percentage }}"/>
                                <div class="input-group-append"><span class="input-group-text">%</span></div>
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <div class="checkbox-inline pt-7">
                                <label class="checkbox">
                                    <input type="checkbox" {{ !empty($commission->status) ? 'checked' : '' }} name="status" value={{ $commission->status }} id="vendorStoreStatus" />
                                    <span></span>
                                    Status
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <button class="btn btn-success mt-7" id="vendorFormSubmit" type="button">Update</button>
                            <button class="btn btn-danger mt-7" id="vendorFormReset" type="reset">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
