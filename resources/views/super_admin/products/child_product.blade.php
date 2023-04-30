@extends('layouts.app')


@push('css')
@endpush


@section('main_content')
    <div class="card card-custom min-h-150px" id="kt_card_2">
        <div class="card-body">
            <div class="form-element-list">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group ic-cmp-int">
                            <div class="form-ic-cmp">
                                <i class="notika-icon notika-map"></i>
                            </div>
                            <div class="bootstrap-select ic-cmp-int">
                                <label>Vendor Name</label>
                                <select name="vendor_id" id="vendor_id" class="selectpicker form-control" data-live-search="true">
                                    <option value="">*Select Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" >{{ $vendor->name }}</option>
                                    @endforeach
                                </select><br/>
                                @error('vendor_id')
                                <strong class="text-danger" >
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    </div>
                </div>

            </div>
            <div class="normal-table-list">
                <div class="bsc-tbl">
                    <div class="row category-parent-element">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <select id="category-1" class="form-control category-dropdown" >
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
                        <div class="col-sm-9">
                            <ul class="list-inline selected-category-helper">
                                {{-- <li class="list-inline-item font-weight-boldest">Selected:</li>                     --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b max-h-100px" id="search-element" style="display:none">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-search icon-lg"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="input-product-search"
                                       placeholder="Search a product by title">
                                <div class="input-group-append">
                                    <button class="btn btn-success" id="btn-product-search">
                                        Search
                                    </button>
                                </div>
                            </div>
                            <span class="form-text text-muted">Search a product by title</span>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <!--End of filtering View content-->
    <!--Start View content-->

    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header {{ Session('breadcomb_container') }}">
            <div class="card-title">
                <h3 class="card-label">Products <i
                        class="mr-2"></i><small>List of Child Products</small></h3>
            </div>

        </div>
        <div class="card-body">

            @include('component.message')
            @if (isset($childProducts) && count($childProducts) > 0)
                <div class="table-responsive childProductTable">
                    <form id="form_export_child_product_id" method="post" action="{{route('superadmin.product.parent.for.map')}}">
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
                            <button type="button" class="btn btn-primary" id="btn_map_parent" >Map Parent Product</button>
                        </td>
                        </tbody>
                    </table>
                    </form>
                </div>
                {!! $childProducts->links() !!}
            @else
                <div class="alert alert-custom alert-outline-2x alert-outline-warning fade show mb-5">
                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                    <div class="alert-text h4 mb-0">No data found</div>
                </div>
            @endif
        </div>
    </div>


    <div id="ModalChildProduct" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Product Details</h4>
                </div>
                <div class="modal-body child_product_detail">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>





@endsection

@push('script')
    <script>
        $(document).ready(function () {
            // set csrf token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('.checkbox-all').change(function () {
                if ($(this).is(':checked')) {
                    $(this).closest('table').find('.checkbox-child-product').each(function () {
                        $(this).prop('checked', true);
                    });
                } else {
                    $(this).closest('table').find('.checkbox-child-product').each(function () {
                        $(this).prop('checked', false);
                    });
                }
            });

            $('body').on('click', '.childProductDetails', function () {
                var val_child_product_id = $(this).data('child_product_id');
                $.ajax({
                    method: "POST",
                    url: "{{ route('superadmin.product.child.details') }}",
                    data: {
                        'child_product_id': val_child_product_id,
                    },
                }).done(function(response) {
                        $(".child_product_detail").html('');
                        $(".child_product_detail").html(response);
                        $("#ModalChildProduct").modal('show');
                });
            });
            $('body').on('change', '#vendor_id', function () {
                NEW_PRODUCT.fetchProducts();
                // var val_vendor_id = $(this).val();
                // $.ajax({
                //     method: "POST",
                //     url: "{{ route('superadmin.product.child.filter') }}",
                //     data: {
                //         'val_vendor_id': val_vendor_id,
                //     },
                // }).done(function(response) {
                //         $(".childProductTable").html('');
                //         $(".childProductTable").html(response);
                // });
            });

            // for product category searching

            $("#category-1").val('');
            const NEW_PRODUCT = {
                categoryStack: {1:'', 2:'', 3:'', 4:''},
                categoryIdStack: {1:'', 2:'', 3:'', 4:''},
                fetchNextCategory: function (el) {
                    $("#search-element").show();
                    $(el).parent().parent().nextAll().hide();
                    let prId = $(el).val();
                    let currentCategorySerial = parseInt($(el).attr('id').split('-')[1]);
                    NEW_PRODUCT.setCategoryObject(NEW_PRODUCT.categoryIdStack, currentCategorySerial, $(el).find(':selected').val())
                    if(!isEmpty($(el).find(':selected').val())){
                        NEW_PRODUCT.setCategoryObject(NEW_PRODUCT.categoryStack, currentCategorySerial, $(el).find(':selected').text())
                    } else {
                        NEW_PRODUCT.setCategoryObject(NEW_PRODUCT.categoryStack, currentCategorySerial, '')
                    }
                    NEW_PRODUCT.setSelectedCategoryHelperText();
                    if (isEmpty(NEW_PRODUCT.categoryIdStack[1])) {
                        $("#search-element").hide();
                    }
                    if (isEmpty(prId)) {
                        return true;
                    }

                    let nextPlaceholder =
                        `#${$(el).attr('id').split('-')[0]}-${parseInt($(el).attr('id').split('-')[1])+1}`;
                    let base = "{!! url('/') !!}";
                    let url = base + '/superadmin/product/categories/' + prId;
                    let createUrlWithCategory = base + '/superadmin/product/create/' + prId;
                    $("#text-create-new-product").attr("href", createUrlWithCategory);

                    AJAX_HELPER.ajaxLoadCallback(url, function (response) {
                        if ($(nextPlaceholder).length) {
                            $(nextPlaceholder).parent().parent().show();
                            $(nextPlaceholder).val(0);
                            AJAX_HELPER.ajax_dropdown_map(nextPlaceholder, response, 'Select Category');
                        }
                        NEW_PRODUCT.fetchProducts();
                    });
                },
                fetchProducts: function () {
                    let search = $("#input-product-search").val();
                    let data = {
                        vendorId: $("#vendor_id").val(),
                        searchString: search,
                        category: NEW_PRODUCT.categoryIdStack,
                    }
                    let base = "{!! url('/') !!}";
                    let url = base + '/superadmin/product/child/filter';
                    AJAX_HELPER.ajaxSubmitDataCallback(url, data, 'html', function (htmlResponse) {
                        $(".childProductTable").html('');
                        $(".childProductTable").html(htmlResponse);
                        // $("#div-card-product-list").show();
                        // $("#div-product-list").html(htmlResponse);
                    })
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
                    if (isEmpty(NEW_PRODUCT.categoryIdStack[1])) {
                        selected_category_helper = '';
                    } else {
                        selected_category_helper = `<li class="list-inline-item font-weight-boldest text-danger">Selected:</li>`;
                    }
                    $.each(NEW_PRODUCT.categoryStack, function (i, v) {
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
                },
                sendChildIdForMap: function(){
                    var  ischecked = $(".export-child-product").is(":checked");
                    if (!ischecked) {
                        Swal.fire({
                            title: 'Warning!',
                            text: 'No product selected',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        })
                        return;
                    }
                    $("#form_export_child_product_id").submit();
                }
            }
            $(document).off("change", ".category-dropdown").on("change", ".category-dropdown", function () {
                NEW_PRODUCT.fetchNextCategory(this);
            })
            $(document).off("click", "#btn-product-search").on("click", "#btn-product-search", function () {
                if (!isEmpty(NEW_PRODUCT.categoryIdStack[1])) {
                    NEW_PRODUCT.fetchProducts();
                } else {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'No category selected',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    })
                    return;
                }
            })
            $(document).off("click", "#btn_map_parent").on("click", "#btn_map_parent", function (e) {
                NEW_PRODUCT.sendChildIdForMap();
            })
        });
        
        // function Export() {
        //     // 
        //     // return false;
        //     // var url = '{{route('superadmin.product.parent.for.map')}}';
        //     // $.post(url, {suggest: txt}, function(result){
        //     //     $("span").html(result);
        //     // });

        //     if (ischecked) {
        //         $("#form_export_child_product_id").attr("method", "post");
        //         $("#form_export_child_product_id").attr("action", "{{route('superadmin.product.parent.for.map')}}");
        //     }
        //     if (!ischecked) {
            
        // }

    </script>
@endpush
