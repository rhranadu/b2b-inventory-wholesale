<div class="card card-custom min-h-200px mb-5">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon">
                <i class="flaticon2-open-box text-primary"></i>
            </span>
            <h3 class="card-label">Brand Discount
        </div>
        <div class="card-toolbar">
            <a
                data-toggle="tooltip"
                id="store_this"
                data-return = "#brand_nav_link"
                title="Add Discount"
                href="#"
                class="btn btn-light-info btn-sm btn-clean font-weight-bold font-size-base mr-1">
                <i class="fa fa-plus"></i>Save Information
            </a>
            <a
                data-toggle="tooltip"
                id="clear_this"
                title="Clear"
                href="#"
                class="btn btn-light-danger btn-sm btn-clean font-weight-bold font-size-base mr-1">
                <i class="fa fa-times"></i>Clear
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="normal-table-list">
            <div class="bsc-tbl">
                <form method="POST" action="{{ route('admin.discounts.store') }}" id="discount_form" accept-charset="UTF-8"
                      enctype="multipart/form-data" id="">
                    @csrf
                    @if(!empty($singleDiscount))
                        <input name="id" type="hidden" value="{{ $singleDiscount->id }}">
                    @endif
                    <input value="product_brands" name="discountable_type" type="hidden">
                    <input name="type_tab" type="hidden" value="brand">

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <select class="form-control select2" id="discountable_id" name="discountable_id">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option
                                            value="{{ $brand->id }}" {{ !empty($singleDiscount) && $singleDiscount->discountable_id == $brand->id ? "selected" : "" }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('discountable_id')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <select class="form-control" id="discount_for" name="discount_for">
                                    <option value="">Please select Pos/Marketplace</option>
                                    <option value="pos" {{ !empty($singleDiscount) && $singleDiscount->discount_for == 'pos' ? "selected" : "" }}>POS</option>
                                    <option value="marketplace" {{ !empty($singleDiscount) && $singleDiscount->discount_for == 'marketplace' ? "selected" : "" }}>Marketplace</option>
                                </select>
                                @error('discount_for')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input class="form-control" id="title" value="{{ $singleDiscount->title ?? '' }}"
                                       autocomplete="off" name="title" type="text" placeholder="Discount Title">
                                @error('title')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input class="form-control" id="discount_amount" value="{{ $singleDiscount->discount_amount ?? '' }}"
                                       autocomplete="off" name="discount_amount" type="number" placeholder="Discount Amount">
                                @error('discount_amount')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input class="form-control" id="discount_percentage" value="{{ $singleDiscount->discount_percentage ?? '' }}"
                                       autocomplete="off" name="discount_percentage" type="number" placeholder="Discount Percentage">
                                @error('discount_percentage')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input class="form-control" id="start_at" value="{{ $singleDiscount->start_at ?? '' }}"
                                       autocomplete="off" name="start_at" type="text" placeholder="Start At">
                                @error('start_at')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <input class="form-control" id="end_at" value="{{ $singleDiscount->end_at ?? '' }}"
                                       autocomplete="off" name="end_at" type="text" placeholder="End At">
                                @error('end_at')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4 align-self-center">
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-outline checkbox-success">
                                        <input value="1" {{ !empty($singleDiscount) && $singleDiscount->status == 1 ? 'checked' : '' }} type="checkbox" id="addedItemCheckbox" name="status" class="i-checks">
                                        <span></span>
                                        Status
                                    </label>
                                </div>
                                @error('status')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4 align-self-center">
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-outline checkbox-success">
                                        <input onclick="handleCheckbox();" value="1" {{ !empty($singleDiscount) && $singleDiscount->is_ongoing == 1 ? 'checked' : '' }} type="checkbox" id="is_ongoing"  name="is_ongoing" class="i-checks">
                                        <span></span>
                                        On Going
                                    </label>
                                </div>
                                @error('is_ongoing')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card card-custom min-h-500px">
    <div class="card-body">
        <div class="normal-table-list">
            <div class="bsc-tbl">
                @include('component.message')
                <div id="">
                    <div class="table-responsive">
                        <table
                            class="table table-hover table-bordered table-condensed discountDeatilsTable"
                            id="data-table-basic">
                            <thead>
                            <tr class="text-nowrap">
                                <th class="text-center">SI</th>
                                <th style="min-width: 150px" class="text-center">Brand</th>
                                <th class="text-center">Discount Title</th>
                                <th class="text-center">Discount Amount</th>
                                <th class="text-center">Discount For</th>
                                <th class="text-center">Discount Percentage</th>
                                <th class="text-center">Start</th>
                                <th class="text-center">End</th>
                                <th class="text-center">On Going</th>
                                <th class="text-center">Status</th>
                                <th width="200" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($discounts as $discount)

                                <tr class="text-center align-middle">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $discount->discountable->name}}</td>
                                    <td>{{ $discount->title }}</td>
                                    <td>{{ isset($discount->discount_amount) ? $discount->discount_amount : 'N/A' }}</td>
                                    <td>{{ isset($discount->discount_for) ? $discount->discount_for : 'N/A' }}</td>
                                    <td>{{ isset($discount->discount_percentage) ? $discount->discount_percentage : 'N/A' }}</td>
                                    <td>{{ $discount->start_at }}</td>
                                    <td>{{ isset($discount->end_at) ? $discount->end_at : 'N/A' }}</td>
                                    <td id="is_ongoing">
                                        <span
                                            href="#0" id="" statusCode="{{ $discount->is_ongoing }}"
                                            data_id="{{ $discount->is_ongoing }}"
                                            class="badge cursor-pointer {{ $discount->is_ongoing == 1 ? 'badge-success' : 'badge-danger' }}">
                                            {{ $discount->is_ongoing == 1 ? 'Yes' : 'No'  }}
                                        </span>
                                    </td>
                                    <td id="status">
                                        <span
                                            href="#0" statusCode="{{ $discount->status }}"
                                            data-trigger = "#brand_nav_link"
                                            data_id="{{ $discount->id }}"
                                            class="badge cursor-pointer {{ $discount->status == 1 ? 'badge-success' : 'badge-danger' }} toggle-active-status">
                                            {{ $discount->status == 1 ? 'Active' : 'Deactive'  }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a
                                                href="{{ route('admin.discount.brand') }}"
                                                data-id="{{ $discount->id }}"
                                                data-page="{{ $discounts->currentPage() }}"
                                                class="btn btn-sm btn-icon btn-warning edit-this"
                                                data-toggle="tooltip"
                                                data-placement="auto" title="" data-original-title="EDIT">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <a
                                                href="#0" class="btn btn-sm btn-icon btn-danger"
                                                onclick="DISCOUNT.deleteDiscount({{ $discount->id }}, this)"
                                                data-return = "#brand_nav_link"
                                                data-toggle="tooltip"
                                                data-placement="auto"
                                                data-original-title="DELETE">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $discounts->onEachSide(1)->links('pagination.pagination_ajax', ['#discount_placeholder']) !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#discount_amount").on('keyup', function () {
        $("#discount_percentage").val('');
        var val_amount = $(this).val();
        if (val_amount >= 0 && val_amount != ''){
            $("#discount_percentage").attr('disabled','disabled');
        }else {
            $("#discount_percentage").removeAttr('disabled');
        }
    });
    $("#discount_percentage").on('keyup', function () {
        $("#discount_amount").val('');
        var val_amount = $(this).val();
        if (val_amount >= 0 && val_amount != ''){
            $("#discount_amount").attr('disabled','disabled');
        }else {
            $("#discount_amount").removeAttr('disabled');
        }
    });

    $('#discountable_id').select2({
        width: '100%',
        placeholder: "Select Brand"
    });
    $('input[name="start_at"], input[name="end_at"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        timePicker:true,
        timePickerSeconds:true,
        timePicker24Hour:true,
        autoUpdateInput:false,
        locale: {
            format: 'YYYY-MM-DD HH:mm:ss',
            cancelLabel:'clear'
        }
    });
    $('input[name="start_at"], input[name="end_at"]').on('apply.daterangepicker', function(ev, picker) {

      $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
        var startDate = new Date($('#start_at').val());
        var endDate = new Date($('#end_at').val());
        if (startDate > endDate){
            $(this).val('');
            Swal.fire({
                text: "Sorry, Start date greater than End date please select valid date",
                icon: "error",
                confirmButtonText: "Ok, got it!",
            });
        }
    });
    $('input[name="start_at"], input[name="end_at"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
</script>
