@extends('layouts.crud-master')
@section('title', 'Discounts')

@push('css')
<style>
    .table th,
    .table td {
        vertical-align: inherit;
    }
</style>
@endpush

@section('main_content')
    <div class="d-flex flex-column-fluid py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div
            class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-2">
{{--                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Discounts</h5>--}}
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-muted font-weight-bold mr-4">Discounts For: </span>

                <ul class="nav nav-success nav-pills" role="tablist">
                    <li class="nav-item">
                        <a style="cursor: pointer" href="{!! route('admin.discount.brand') !!}" data-page=1, id="brand_nav_link" class="nav-link" data-toggle="tabTableAjax">
                            <span class="nav-icon">
                                <i class="flaticon2-open-box"></i>
                            </span>
                            <span class="nav-text">Brand</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{!! route('admin.discount.vendor') !!}" id="vendor_nav_link" class="nav-link" data-toggle="tabTableAjax">
                            <span class="nav-icon">
                                <i class="flaticon2-group"></i>
                            </span>
                            <span class="nav-text">Vendor</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{!! route('admin.discount.category') !!}" id="category_nav_link" class="nav-link" data-toggle="tabTableAjax">
                            <span class="nav-icon">
                                <i class="flaticon2-grids"></i>
                            </span>
                            <span class="nav-text">Category</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{!! route('admin.discount.product') !!}" id="product_nav_link" class="nav-link" data-toggle="tabTableAjax">
                            <span class="nav-icon">
                                <i class="flaticon2-delivery-package"></i>
                            </span>
                            <span class="nav-text">Product</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid" id="discount_placeholder">

        </div>
    </div>
@endsection

@push('script')

<script>
    $(document).ready(function () {
        let searchParams = new URLSearchParams(window.location.search)
        const val_type = searchParams.get('type_tab');
        if (val_type == 'brand') {
            $('#brand_nav_link').trigger('click');
        } else if (val_type == 'vendor') {
            $('#vendor_nav_link').trigger('click');
        } else if (val_type == 'category') {
            $('#category_nav_link').trigger('click');
        } else if (val_type == 'product') {
            $('#product_nav_link').trigger('click');
        } else {
            $('#brand_nav_link').trigger('click');
        }
    });
    $(document).off('click', '.toggle-active-status').on('click', '.toggle-active-status', function () {
        var trigger = $(this).data('trigger');
        var id = $(this).attr('data_id');
        var getStatus = $(this).attr('statusCode');
        var setStatus = (getStatus > 0) ? 0 : 1;
        $.ajax({
            url: "{{ route('admin.discounts.statusActiveUnactive') }}",
            type: "get",
            data: {setStatus: setStatus, id: id},
            success: function (res) {
                if (res === 'true') {
                    $(trigger).trigger('click');
                    toastr.success('Discount status updated success');
                } else {
                    toastr.error('Not found !');
                }
            }
        })
    });

    function handleCheckbox(){
        var  ischecked = $('#is_ongoing').is(':checked');
        if (ischecked) {
            $("#end_at").val('');
            $("#end_at").attr('disabled','disabled');
        }
        if (!ischecked) {
            $("#end_at").removeAttr('disabled');
        }
    }
    $(document).off('click', '[data-toggle="tabTableAjax"], .edit-this').on('click', '[data-toggle="tabTableAjax"], .edit-this', function (e) {
        e.preventDefault();
        if($(this).hasClass('nav-link')){
            $('[data-toggle="tabTableAjax"]').removeClass('active');
            $(this).addClass('active');
        }
        DISCOUNT.renderDiscount(this);
        return false;
    })
    $(document).off('click', '#store_this').on('click', '#store_this', function () {
        var val_amount =  $("#discount_amount").val();
        var val_percentage =  $("#discount_percentage").val();
        if($(this).data('return') ==  "#product_nav_link"){
            if( !$("#selected_product_arr").val() && !$("#discountable_id").val() ) {
                    Swal.fire({
                        text: "Sorry, Please Select Product",
                        icon: "error",
                        confirmButtonText: "Ok, got it!",
                    });
                return false;
            }
        }
        if(isEmpty(val_amount) && !isEmpty(val_percentage)) {
            DISCOUNT.storeDiscount();
            return false;
        }else if(isEmpty(val_percentage) && !isEmpty(val_amount)) {
            DISCOUNT.storeDiscount();
            return false;
        }else {
            Swal.fire({
                text: "Sorry, Please insert Discount Amount / Discount Percentage",
                icon: "error",
                confirmButtonText: "Ok, got it!",
            });
        }
        var val_discount_for =  $("#discount_for").val();
        if(isEmpty(val_discount_for)){
            Swal.fire({
                text: "Sorry, Please Select Discount For!",
                icon: "error",
                confirmButtonText: "Ok, got it!",
            });
            return false;
        }
    })
    $(document).off('click', '#clear_this').on('click', '#clear_this', function () {
        DISCOUNT.clearDiscountForm();
        return false;
    })
    const DISCOUNT = {
        renderDiscount:function (elem) {
            let data = {
                id: $(elem).data('id'),
                page:  $(elem).data('page')
            }
            AJAX_HELPER.ajaxSelectedMethodSubmitDataCallback('GET', $(elem).attr('href'), data, 'html', function (response) {
                $("#discount_placeholder").html(response);
                if(!$(elem).hasClass('nav-link')){
                    if(!isEmpty($('#select_product_list').length)){
                        $('#select_product_list').hide();
                    }
                }
            })
        },
        clearDiscountForm:function () {
            $("#discount_form").find('input').val('');
            $("#discount_form option"). attr("selected", false);
            $("#selected_product_panel").html('');
            $('#select_product_list').show();
            $("input[type='checkbox']").prop('checked', false);

        },
        storeDiscount:function () {
            $("#discount_form").submit();
        },
        deleteDiscount: function (id, elem) {

            var url = "{{url('admin/discounts')}}/"+id
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButtonColor: '#00c292',
                    cancelButton: 'btn btn-danger mt-0'
                },
                buttonsStyling: true
            });
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this! ⚠️",
                type: 'warning',
                cancelButtonColor: "#AF0000",
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    $.ajax({
                        url: url,
                        type: 'delete',
                        dataType: 'json',
                        beforeSend: function(){
                            $.blockUI();
                        },
                        complete: function(){
                            $.unblockUI();
                        },
                        success: function(data) {
                            toastr.success('Discount Deleted');
                            $($(elem).data('return')).click();
                        },
                        error: function(data) {

                            toastr.error('Failed to delete');
                        }
                    });
                }
            })
        }
    }
</script>

@endpush
