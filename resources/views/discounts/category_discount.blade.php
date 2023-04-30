<div class="card card-custom min-h-200px mb-5">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon">
                <i class="flaticon2-grids text-primary"></i>
            </span>
            <h3 class="card-label">Category Discount</h3>
        </div>

        <div class="card-toolbar">
            <a
                data-toggle="tooltip"
                id="store_this"
                data-return = "#category_nav_link"
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
                    <input value="product_categories" name="discountable_type" type="hidden">
                    <input name="type_tab" type="hidden" value="category">
                    <input value="{{ !empty($singleDiscount) ? $singleDiscount->discountable_id : '' }}" name="discountable_id" id="discountable_id" type="hidden">
                    <div class="row">
                        <div class="col-sm-9">
                            <ul class="list-inline selected-category-helper">
                                {{-- <li class="list-inline-item font-weight-boldest">Selected:</li>                     --}}
                            </ul>
                        </div>
                    </div>
                    <div class="row category-parent-element">
                        @error('discountable_id')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                        @enderror
                        <div class="col-sm-3">
                            <div class="form-group">
                                <select id="category-1" class="form-control category-dropdown">
                                    <option value=""> Select Categories </option>
                                    @foreach($parentCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3" style="display:none">
                            <div class="form-group">
                                <select id="category-2" class="form-control category-dropdown">

                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3" style="display:none">
                            <div class="form-group">
                                <select id="category-3" class="form-control category-dropdown">

                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3" style="display:none">
                            <div class="form-group">
                                <select id="category-4" class="form-control category-dropdown">

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <select class="form-control" id="discount_for" name="discount_for">
                                    <option value="">Please select Pos/Marketplace</option>
                                    <option value="pos" {{ !empty($singleDiscount) && $singleDiscount->discount_for == 'pos' ? "selected" : "" }}>POS</option>
                                    <option value="marketplace" {{ !empty($singleDiscount) && $singleDiscount->discount_for == 'marketplace' ? "selected" : "" }}>Marketplace</option>
                                </select>
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
                    </div>
                    <div class="row">

                        <div class="col-sm-4 align-self-center">
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-outline checkbox-success">
                                        <input  value="1" {{ !empty($singleDiscount) && $singleDiscount->status == 1 ? 'checked' : '' }} type="checkbox" id="addedItemCheckbox" name="status" class="i-checks">
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
                                        <input onclick="handleCheckbox();" value="1" {{ !empty($singleDiscount) && $singleDiscount->is_ongoing == 1 ? 'checked' : '' }} type="checkbox" id="is_ongoing" name="is_ongoing" class="i-checks">
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
                            class="table table-hover table-bordered table-condensed"
                            id="data-table-basic">
                            <thead>
                            <tr class="text-nowrap">
                                <th class="text-center">SI</th>
                                <th style="min-width: 150px" class="text-center">Category</th>
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
                                            data-trigger = "#category_nav_link"
                                            data_id="{{ $discount->id }}"
                                            class="badge cursor-pointer {{ $discount->status == 1 ? 'badge-success' : 'badge-danger' }} toggle-active-status">
                                            {{ $discount->status == 1 ? 'Active' : 'Deactive'  }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a
                                                href="{{ route('admin.discount.category') }}"
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
                                                data-return = "#category_nav_link"
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
            })
        }
    });
    $('input[name="start_at"], input[name="end_at"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
    $(document).off("change", ".category-dropdown").on("change", ".category-dropdown", function () {
        CATEGORY_DISCOUNT.fetchNextCategory(this);
    })
    var CATEGORY_DISCOUNT = {
        categoryStack: {1:'', 2:'', 3:'', 4:''},
        categoryIdStack: {1:'', 2:'', 3:'', 4:''},
        fetchNextCategory: function (el) {
            $("#search-element").show();
            $(el).parent().parent().nextAll().hide();
            let prId = $(el).val();
            let currentCategorySerial = parseInt($(el).attr('id').split('-')[1]);
            CATEGORY_DISCOUNT.setCategoryObject(CATEGORY_DISCOUNT.categoryIdStack, currentCategorySerial, $(el).find(':selected').val())
            if(!isEmpty($(el).find(':selected').val())){
                CATEGORY_DISCOUNT.setCategoryObject(CATEGORY_DISCOUNT.categoryStack, currentCategorySerial, $(el).find(':selected').text())
            } else {
                CATEGORY_DISCOUNT.setCategoryObject(CATEGORY_DISCOUNT.categoryStack, currentCategorySerial, '')
            }
            CATEGORY_DISCOUNT.setSelectedCategoryHelperText();
            if (isEmpty(CATEGORY_DISCOUNT.categoryIdStack[1])) {
                $("#search-element").hide();
            }
            if (isEmpty(prId)) {
                return true;
            }
            $("#discountable_id").val(prId);
            let nextPlaceholder =
            `#${$(el).attr('id').split('-')[0]}-${parseInt($(el).attr('id').split('-')[1])+1}`;
            let base = "{!! url('/') !!}";
            let url = base + '/admin/product/categories/' + prId;
            let createUrlWithCategory = base + '/admin/product/create/' + prId;
            $("#text-create-new-product").attr("href", createUrlWithCategory);

            AJAX_HELPER.ajaxLoadCallback(url, function (response) {
                if ($(nextPlaceholder).length) {
                    $(nextPlaceholder).parent().parent().show();
                    $(nextPlaceholder).val(0);
                    AJAX_HELPER.ajax_dropdown_map(nextPlaceholder, response, 'Select Category');
                }
            });
        },
        setCategoryObject: function (obj, currentCategorySerial, value) {
            $.each(obj, function (i, v) {
                if ((i > currentCategorySerial) &&  !isEmpty(v)) {
                    obj[i] = '';
                }
            })
            obj[currentCategorySerial] = value
        },
        setSelectedCategoryHelperText: function(){
            $(".selected-category-helper").html('');
            let selected_category_helper = '';
            if (isEmpty(CATEGORY_DISCOUNT.categoryIdStack[1])) {
                selected_category_helper = '';
            } else {
                selected_category_helper = `<li class="list-inline-item font-weight-boldest text-danger">Selected:</li>`;
            }
            $.each(CATEGORY_DISCOUNT.categoryStack, function (i, v) {
                if (!isEmpty(v)) {
                    if (i == 1) {
                        selected_category_helper +=
                        `<li class="list-inline-item text-danger">${v}</li>`;
                    } else {
                        selected_category_helper +=
                            `<li class="list-inline-item text-danger">   ➡ ${v}</li>`;
                    }
                }
            })
            $(".selected-category-helper").html(selected_category_helper);
        }
    }


</script>
