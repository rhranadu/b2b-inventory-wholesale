@extends('layouts.crud-master')
@include('component.dataTable_resource')

@section('title', 'Product Create')
@push('css')

@endpush
@section('main_content')

<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                @include('component.message')
                <div class="normal-table-list">
                    <div class="bsc-tbl">
                        <div class="row category-parent-element">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <select id="vendor" class="form-control vendor-dropdown">
                                        <option value=""> Select Vendor </option>
                                        @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <select id="category-1" class="form-control category-dropdown">
                                        <option value=""> Select Category </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2" style="display:none">
                                <div class="form-group">
                                    <select id="category-2" class="form-control category-dropdown">

                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2" style="display:none">
                                <div class="form-group">
                                    <select id="category-3" class="form-control category-dropdown">

                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2" style="display:none">
                                <div class="form-group">
                                    <select id="category-4" class="form-control category-dropdown">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-9">
                                <ul class="list-inline selected-category-helper">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b max-h-100px" id="">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <select name="status_id" id="status_id" class="form-control status_id" data-live-search="true">
                                <option value="">All</option>
                                <option value="1" >Approved And Mapped</option>
                                <option value="2" >Mapped But Not Approved</option>
                                <option value="3" >Not Mapped and not Approved</option>
                            </select>
                        </div>

                    </div>
                    <div class="col-sm-9">
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


        <div class="card card-custom gutter-b" id="div-card-product-list">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" id="div-product-list">
                        <div class="table-responsive">
                            <form id="form_export_child_product_id" method="post" action="{{route('superadmin.product.parent.for.map')}}">
                                @csrf
                                <table class="table table-hover table-bordered table-condensed productTable"
                                       id="product_table">
                                    <thead>
                                    <tr>
                                        <th class="text-center no-sort">
                                            <div class=" form-check custom_checkbox">
                                                <input class="form-check-input checkbox-all" type="checkbox" id="checkbox-product">
                                                <label class="form-check-label" for="checkbox-product"></label>
                                            </div>
                                        </th>
                                        <th class="text-center">SI</th>
                                        {{-- <th class="text-center">Image</th> --}}
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Vendor</th>
                                        <th class="text-center">Parent Product</th>
                                        <th class="text-center">Model</th>
                                        <th class="text-center">Brand</th>
                                        <th class="text-center">Manufacturer</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Admin Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </form>
                        </div>
                        <td colspan="12">
                            <button type="button" style="display: none" class="btn btn-primary approveProductSubmit" >Approve</button>
                            <button type="button" style="display: none" class="btn btn-primary disapproveProductSubmit" value="deactive"  >Disapprove</button>
                            <button type="button" style="display: none" class="btn btn-primary approveParentManufacturerSubmit"  >Approve As Parent</button>
                            <button type="button" class="btn btn-primary mapParentProductSubmit"  >Map Selected with Parent</button>
                            <button type="button" style="display: none" class="btn btn-primary unmapParentProductSubmit" value="parent" >UnMap from Selected Parent</button>
                        </td>
                    </div>
                </div>
            </div>
        </div>
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
@include('super_admin.products.modal.parent_product_list_for_map')
{{-- @include('super_admin.products.modal.create') --}}

@endsection

@push('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".alert").delay(5000).slideUp(300);
    $("#category-1").val('');
    const NEW_PRODUCT = {
        categoryStack: {1:'', 2:'', 3:'', 4:''},
        categoryIdStack: {1:'', 2:'', 3:'', 4:''},
        fetchFirstCategorByVendor:function() {
            let base = "{!! url('/') !!}";
            let url = base + '/superadmin/product/categories/' + 0 + '/' + $("#vendor :selected").val();
            AJAX_HELPER.ajaxLoadCallback(url, function (response) {
                AJAX_HELPER.ajax_dropdown_map("#category-1", response, 'Select Category');
            });
            $("#category-1").val(0).trigger('change');
        },
        fetchNextCategory: function (el) {
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
            if (isEmpty(prId)) {
                return true;
            }

            let nextPlaceholder =
                `#${$(el).attr('id').split('-')[0]}-${parseInt($(el).attr('id').split('-')[1])+1}`;
            let base = "{!! url('/') !!}";
            let url = base + '/superadmin/product/categories/' + prId;
            let createUrlWithCategory = base + '/admin/product/create/' + prId;
            $("#text-create-new-product").attr("href", createUrlWithCategory);

            AJAX_HELPER.ajaxLoadCallback(url, function (response) {
                console.log($(nextPlaceholder).length);
                if ($(nextPlaceholder).length) {
                    $(nextPlaceholder).parent().parent().show();
                    $(nextPlaceholder).val(0);
                    AJAX_HELPER.ajax_dropdown_map(nextPlaceholder, response, 'Select Category');
                }
                product_table.draw();
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
            if (isEmpty(NEW_PRODUCT.categoryIdStack[1])) {
                selected_category_helper = '';
            } else {
                selected_category_helper = `<li class="list-inline-item font-weight-boldest text-danger">Selected Category:</li>`;
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
        approveAsParent: function (elem) {
            var getId = $(elem).data('id');
            $.ajax({
                url: "{{ route('superadmin.product.parent.approve.new')}}",
                method: "POST",
                data: {
                    id: getId,
                },
                success: function (feedbackResult) {
                    console.log(feedbackResult)
                    if (feedbackResult.success == true) {
                        var product_table = $('#product_table').dataTable();
                        product_table.fnDraw(false);
                        toastr.success('Product approved as parent');
                    }
                    if (feedbackResult.success == false){
                        toastr.error(feedbackResult.msg);
                    }
                },
            });
        },

        showChildProductDetails: function (elem) {
            var val_child_product_id = $(elem).data('child_product_id');
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
        }

    }
    var product_table =   $('#product_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            // url: '{{route('superadmin.manufacturer.child.ajax')}}',
            url: '{{route('superadmin.product.list.map')}}',
            type: "POST",
            data: function (d) {
                d.status_id = $('#status_id').val();
                d.searchString= $("#input-product-search").val();
                d.category= NEW_PRODUCT.categoryIdStack;
                d.vendor= $("#vendor :selected").val();
            }
        },
        dom:'Blfrtip',
        lengthMenu: [
            [ 10, 25, 50, 100, -1 ],
            [ '10', '25', '50', '100', 'All' ]
        ],
        buttons: [
            {
                extend: 'excel',
                className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                text: 'Excel',
                exportOptions: {
                    columns: ':not(.notexport)'
                }
            },
            {
                extend: 'pdf',
                className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                text: 'Pdf',
                exportOptions: {
                    columns: ':not(.notexport)'
                }
            },
            {
                extend: 'print',
                text: 'Print',
                className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                exportOptions: {
                    columns: ':not(.notexport)'
                }
            }
        ],
        columns: [
            {data: 'checkbox', name: 'checkbox'},
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'product_vendor.name', name: 'vendor_name'},
            {data: 'parent_name', name: 'parent_name'},
            {data: 'product_model', name: 'product_model'},
            {data: 'product_brand', name: 'product_brand'},
            {data: 'product_manufacturer', name: 'product_manufacturer'},
            {data: 'product_category', name: 'product_category'},
            {data: 'status', name: 'status'},
            {data: 'is_approved', name: 'is_approved'},
            {data: 'action', name: 'action'},
        ],
        columnDefs: [
            {
                targets: '_all',
                defaultContent: 'N/A'
            },
            {
                targets: 'no-sort',
                orderable: false,
            },
        ],
        order: [[1, 'asc']]
    });
    $(document).off("click", ".childProductDetails").on("click", ".childProductDetails", function (e) {
        NEW_PRODUCT.showChildProductDetails(this);
    })
    $(document).off("click", ".btn-approve-as-parent").on("click", ".btn-approve-as-parent", function (e) {
        NEW_PRODUCT.approveAsParent(this);
    })

    $(document).off("change", "#vendor").on("change", "#vendor", function (e) {
        NEW_PRODUCT.fetchFirstCategorByVendor();
    })

    product_table.draw();
    $(document).off("change", ".category-dropdown").on("change", ".category-dropdown", function () {
        NEW_PRODUCT.fetchNextCategory(this);
    })
    $(document).off("click", "#btn-product-search").on("click", "#btn-product-search", function (e) {
        product_table.draw();
        e.preventDefault();
    })


    $(document).off('change', '.checkbox-all').on('change', '.checkbox-all', function () {
        if ($(this).is(':checked')) {
            $(this).closest('table').find('.checkbox-product').each(function () {
                $(this).prop('checked', true);
            });
        } else {
            $(this).closest('table').find('.checkbox-product').each(function () {
                $(this).prop('checked', false);
            });
        }
    });
    $(document).off('click', '.approveProductSubmit').on('click', '.approveProductSubmit', function () {
        var  ischecked = $(".export-approve-product").is(":checked");
        if (ischecked) {
            var val_arr = [];
            $('.export-approve-product:checked').each(function(i){
                val_arr.push($(this).val());
            });
            var productsInPack = val_arr;
            $.ajax({
                method: "POST",
                url: "{{ route('superadmin.product.approval.status') }}",
                data: {
                    'productsInPack': productsInPack,
                },
            }).done(function(response) {
                if (response.success == true){
                    var product_table = $('#product_table').dataTable();
                    product_table.fnDraw(false);
                    toastr.success('Product approve status updated success!');
                }
            });
        }
        if (!ischecked) {
            Swal.fire({
                title: 'Warning!',
                text: 'No Product Selected',
                icon: 'warning',
                confirmButtonText: 'OK'
            })
            return;
        }
    });

    $(document).off('click', '.disapproveProductSubmit').on('click', '.disapproveProductSubmit', function () {
        var  ischecked = $(".export-approve-product").is(":checked");
        if (ischecked) {
            var val_arr = [];
            $('.export-approve-product:checked').each(function(i){
                val_arr.push($(this).val());
            });
            var productsInPack = val_arr;
            $.ajax({
                method: "POST",
                url: "{{ route('superadmin.product.disapproval.status') }}",
                data: {
                    'productsInPack': productsInPack,
                },
            }).done(function(response) {
                if (response.success == true){
                    var product_table = $('#product_table').dataTable();
                    // product_table.draw();
                    product_table.fnDraw(false);
                    toastr.success('Product approve status updated success!');
                }else{
                    toastr.error(response.msg);
                }
            });
        }
        if (!ischecked) {
            Swal.fire({
                title: 'Warning!',
                text: 'No Product Selected',
                icon: 'warning',
                confirmButtonText: 'OK'
            })
            return;
        }
    });


    $(document).off('click', '.mapParentProductSubmit').on('click', '.mapParentProductSubmit', function () {
        var  ischecked = $(".export-approve-product").is(":checked");
        if (ischecked) {
            $("#ParentProductModal").modal('show');
            var parentProductDataTable = $('#parentProductDataTable').dataTable();
            parentProductDataTable.fnDraw(false);
        }
            if (!ischecked) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'No Product Selected',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                })
                return;
            }
    });
    $(document).off('click', '.unmapParentProductSubmit').on('click', '.unmapParentProductSubmit', function () {
        var  ischecked = $(".export-approve-product").is(":checked");
        if (ischecked) {
            var val_arr = [];
            $('.export-approve-product:checked').each(function(i){
                val_arr.push($(this).val());
            });
            var productsInPack = val_arr;
            $.ajax({
                method: "POST",
                url: "{{ route('superadmin.product.parent.unmap') }}",
                data: {
                    'productsInPack': productsInPack,
                },
            }).done(function(response) {
                if (response.success == true){
                    var product_table = $('#product_table').dataTable();
                    product_table.fnDraw(false);
                    // product_table.draw();
                    toastr.success('Parent Product Unmaped Successfully!');
                }
            });
        }
        if (!ischecked) {
            Swal.fire({
                title: 'Warning!',
                text: 'No Product selected',
                icon: 'warning',
                confirmButtonText: 'OK'
            })
            return;
        }
    });
    // $(document).off('click', '.parentProductCreate').on('click', '.parentProductCreate', function () {
    //     $("#parentProductCreateModal").modal('show');
    // });

    $(document).off('change', '.status_id').on('change', '.status_id', function (e) {
        var val = $(this).val();
        if (val == 1){
            $('.approveProductSubmit').hide();
            $('.disapproveProductSubmit').show();
            $('.unmapParentProductSubmit').show();
            $('.mapParentProductSubmit').hide();
        }else if (val == 2){
            $('.approveProductSubmit').show();
            $('.unmapParentProductSubmit').show();
            $('.mapParentProductSubmit').hide();
            $('.disapproveProductSubmit').hide();
        }else if(val == 3){
            $('.approveProductSubmit').hide();
            $('.mapParentProductSubmit').show();
            $('.unmapParentProductSubmit').hide();
            $('.disapproveProductSubmit').hide();
        }
        product_table.draw();
        e.preventDefault();
    });
    $(document).off('change', '.vendor_id').on('change', '.vendor_id', function (e) {
        product_table.draw();
        e.preventDefault();
    });


</script>
@endpush
