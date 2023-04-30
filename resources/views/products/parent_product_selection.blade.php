@extends('layouts.crud-master')
@section('title', 'Product Create')
@push('css')

@endpush
@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label font-weight-bolder text-dark">
                            Choose Product Category
                            <small></small>
                        </h3>
                        <a href="{{route('admin.product.create', false)}}"
                           class="btn btn-light-success btn-sm btn-clean font-weight-bold font-size-base mr-1" style="display: none" id="createNewProduct"><i class="fa fa-plus"></i>Create new product with this category</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('component.message')
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <div class="row category-parent-element">
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
                                <div class="col-sm-9">
                                    <ul class="list-inline selected-category-helper">
                                        {{-- <li class="list-inline-item font-weight-boldest">Selected:</li>                     --}}
                                    </ul>
                                </div>
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
                                            Search and Create
                                        </button>
                                    </div>
                                </div>
                                <span class="form-text text-muted">Search a product by title</span>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
            <div class="card card-custom gutter-b" id="div-card-product-list" style="display:none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12" id="div-product-list">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col">
                                    <p class="text-secondary">Not found desired product? <a
                                            href="{{route('admin.product.create', false)}}"
                                            class="btn btn-light-success btn-sm btn-clean font-weight-bold font-size-base mr-1" id="text-create-new-product"><i class="fa fa-plus"></i>Create new product with this category</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <!--begin::Container-->
    <!--begin::Entry-->

@endsection

@push('script')
    <script>
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
                    $("#createNewProduct").hide();
                }
                if (isEmpty(prId)) {
                    return true;
                }
                $("#createNewProduct").show();
                let nextPlaceholder =
                    `#${$(el).attr('id').split('-')[0]}-${parseInt($(el).attr('id').split('-')[1])+1}`;
                let base = "{!! url('/') !!}";
                let url = base + '/admin/product/categories/' + prId;
                let createUrlWithCategory = base + '/admin/product/create/' + prId;
                $("#text-create-new-product").attr("href", createUrlWithCategory);
                $("#createNewProduct").attr("href", createUrlWithCategory);

                AJAX_HELPER.ajaxLoadCallback(url, function (response) {
                    if ($(nextPlaceholder).length) {
                        $(nextPlaceholder).parent().parent().show();
                        $(nextPlaceholder).val(0);
                        AJAX_HELPER.ajax_dropdown_map(nextPlaceholder, response, 'Select Category');
                    }
                });
            },
            fetchProducts: function () {
                let search = $("#input-product-search").val();
                let data = {
                    searchString: search,
                    category: NEW_PRODUCT.categoryIdStack,
                }
                let base = "{!! url('/') !!}";
                let url = base + '/admin/product/search';
                AJAX_HELPER.ajaxSubmitDataCallback(url, data, 'html', function (htmlResponse) {
                    $("#div-card-product-list").show();
                    $("#div-product-list").html(htmlResponse);
                })
                localStorage.setItem('HiddenProductSearchVal',search);
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

    </script>
@endpush
