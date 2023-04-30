@if(isset($productReturn))
    <div class="row pb-5">
        <div class="col-md-12 d-flex justify-content-center">
            <button type="button" class="close btn btn-primary btn-lg" id="backFromEditRequest" style="color:blue;">
            <i class="fa fa-arrow-left"></i>Back
        </button></div>
    </div>
@endif
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="card card-custom min-h-500px" id="kt_card_1">
            <div class="card-body">
                @include('component.message')
                <form action="{{ route('admin.return.request.endpoint.submit') }}" id="product_return_form"
                      method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Return Request Type<span
                                        style="color: red; font-size: 18px">
                                    <sup>*</sup>
                                </span></label>
                                <select name="request_type" id="request_type" class="form-control">
                                    <option value="">Please Select Request Type</option>
                                    <option value="refund">Refund Request</option>
                                    <option value="exchange">Exchange Request</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Returned Invoice<span
                                        style="color: red; font-size: 18px">
                                    <sup>*</sup>
                                </span></label>
                                <input type='hidden' value="{{ isset($productReturn) ? $productReturn->id : '' }}"
                                       name="id">
                                <input type='text'
                                       value="{{ isset($productReturn) ? $productReturn->returned_stocked_product_barcode : '' }}"
                                       name="invoice_no" class='form-control available_qty return_product_search_field'>
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
                    </div>
                    <div class="row">
                        {{--                        Return Product Barcode--}}
                        <div class="col-md-6 d-none" id="invoice_detail">
                        </div>
                        {{--                        Exchanged Product Barcode--}}
                        <div class="col-md-6 d-none" id="exchanged_product_barcode_elem">
                            {{--                            <div class="form-group">--}}
                            {{--                                <label for="">Exchanged Product Barcode<span--}}
                            {{--                                    style="color: red; font-size: 18px">--}}
                            {{--                                    <sup>*</sup>--}}
                            {{--                                </span></label>--}}
                            {{--                                <input type='text'--}}
                            {{--                                    value="{{ isset($productReturn) ? $productReturn->exchanged_stocked_product_barcode : '' }}"--}}
                            {{--                                    name="exchanged_stocked_product_barcode"--}}
                            {{--                                    id="exchanged_product_barcode"--}}
                            {{--                                    class='form-control'>--}}
                            {{--                                <span class="badge cursor-pointer badge-warning exchanged-product-show mr-2" style="display:none">View--}}
                            {{--                                    Product</span>--}}
                            {{--                                <span class="badge badge-danger product-not-found-alert" style="display:none">Invalid--}}
                            {{--                                    Product</span>--}}
                            {{--                                <span class="badge badge-success product-found-alert" style="display:none">Valid--}}
                            {{--                                    Product</span>--}}
                            {{--                                <span class="badge badge-info product-quantity-text-alert ml-2" style="display:none">Stocked--}}
                            {{--                                    Quantity <strong class="product-quantity-alert">0</strong></span>--}}
                            {{--                            </div>--}}
                        </div>
                        <div class="col-md-6 d-none" id="exchanged_product_barcode_elem_dd">
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
                                          class="form-control">{{ isset($productReturn) ? $productReturn->reason : '' }}</textarea>
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
    </div>
</div>
<form action="{{ route('admin.return.request.detail') }}" method="POST" target="_blank"
      id="return_detail_view_helper_form">
    @csrf
    <input type="hidden" name="return_product_barcode" id="return_product_barcode" />
    <input type="hidden" name="return_product_id" id="return_product_id" />
    <input type="submit" class="d-none" value="Submit">
</form>

<div class="modal fade" id="exchanged_product_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background: #81abe0 !important;">
                <h5 class="modal-title" style="color:white;">Product Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                    <i aria-hidden="true" class="fa fa-close"></i>
                </button>
            </div>

            <div class="modal-body">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>

{{--    var rtn_bar_check = false;--}}
{{--    var ex_bar_check = false;--}}

{{--    $("#instant_exchange_checkbox").prop( "checked", false);--}}

{{--    $(".instant_exchange_element").hide();--}}
{{--    $(".return_product_search_field").focus();--}}
{{--    function checkReturnRequestValidation() {--}}
{{--        if($(".return_product_search_field").val() == '') rtn_bar_check = false;--}}
{{--        //if($("#exchanged_product_barcode").val() == '') ex_bar_check = false;--}}
{{--        $("#submit_request_btn").prop("disabled", true);--}}
{{--        if($("#request_type :selected").val() != '' && $("#request_type :selected").val() != undefined){--}}
{{--            if($("#instant_exchange_checkbox").is(':checked')){--}}
{{--                if(rtn_bar_check && ex_bar_check){--}}
{{--                    $("#submit_request_btn").prop("disabled", false);--}}
{{--                }--}}
{{--            } else {--}}
{{--                if(rtn_bar_check) $("#submit_request_btn").prop("disabled", false);--}}
{{--            }--}}
{{--        }--}}
{{--    }--}}
{{--    $(document).off('click', '.exchanged-product-show').on('click', '.exchanged-product-show', function (event) {--}}
{{--        $.ajax({--}}
{{--            url: "{{ route('admin.return.prduct.not.sold.detail') }}",--}}
{{--            type: 'post',--}}
{{--            data: {barcode: $("#exchanged_product_barcode").val()},--}}
{{--            dataType: 'html',--}}
{{--            beforeSend: function(){--}}
{{--                $.blockUI();--}}
{{--            },--}}
{{--            complete: function(){--}}
{{--                $.unblockUI();--}}
{{--            },--}}
{{--            success: function(data) {--}}
{{--                $("#exchanged_product_modal .modal-body").html(data);--}}
{{--                $("#exchanged_product_modal").modal({show:true});--}}
{{--            },--}}
{{--            error: function(data) {--}}


{{--            }--}}
{{--        });--}}
{{--    });--}}
{{--    $(document).off('click', '.product-show').on('click', '.product-show', function () {--}}
{{--        $("#return_product_barcode").val($(".return_product_search_field").val());--}}
{{--        $("#return_product_id").val('');--}}
{{--        $("#return_detail_view_helper_form").submit();--}}
{{--    })--}}
//     $(document).off('change', '#request_type').on('change', '#request_type', function () {
//         if ($(this).val() == 'exchange') {
//             $(".instant_exchange_element").show();
//             // $("#exchanged_product_barcode_elem").toggle($("#instant_exchange_checkbox").is(':checked'));
//         } else {
//             $("#instant_exchange_checkbox").prop( "checked", false );
//             $(".instant_exchange_element").hide();
//             $("#exchanged_product_barcode_elem").removeClass('d-none').addClass('d-none');
//         }
//         checkReturnRequestValidation();
//     })
{{--    // $(document).off('click', '#instant_exchange_checkbox').on('click', '#instant_exchange_checkbox', function () {--}}
{{--    //     $("#exchanged_product_barcode_elem").toggle($(this).is(':checked'));--}}
{{--    //     checkReturnRequestValidation()--}}
{{--    // })--}}

{{--    $(document).off('click', '#submit_request_btn').on('click', '#submit_request_btn', function (e) {--}}
{{--        if($("#reason").val() == ''){--}}
{{--            toastr.error("Reason field is mandatory");--}}
{{--            $("#reason").focus();--}}
{{--            return false;--}}
{{--        }--}}
{{--        const swalWithBootstrapButtons = Swal.mixin({--}}
{{--            customClass: {--}}
{{--                confirmButtonColor: '#00c292',--}}
{{--                cancelButton: 'btn btn-danger mt-0'--}}
{{--            },--}}
{{--            buttonsStyling: true--}}
{{--        });--}}
{{--        swalWithBootstrapButtons.fire({--}}
{{--            title: 'Are you sure?',--}}
{{--            type: 'warning',--}}
{{--            cancelButtonColor: "#AF0000",--}}
{{--            showCancelButton: true,--}}
{{--            confirmButtonText: 'Yes, submit it!'--}}
{{--        }).then((result) => {--}}
{{--            if (result.value) {--}}
{{--                $('#product_return_form').submit();--}}
{{--            }--}}
{{--        })--}}
{{--    })--}}
{{--    $(".return_product_search_field").keyup(function () {--}}
{{--        var elem = $(".return_product_search_field");--}}
{{--        var text = elem.val();--}}
{{--        var id = $("#product_return_form").find("input[name='id']").val();--}}
{{--        if(text == 0 || text == ''){--}}
{{--            elem.siblings(".product-not-found-alert").hide();--}}
{{--            elem.siblings(".product-found-alert").hide();--}}
{{--            elem.siblings(".product-quantity-text-alert").hide();--}}
{{--            elem.siblings(".product-quantity-alert").hide();--}}
{{--            elem.siblings(".product-show").hide();--}}
{{--            checkReturnRequestValidation()--}}
{{--        }--}}
{{--        if (text) {--}}
{{--            $.post("{{ route('admin.return.product.info.with.ajax') }}", {--}}
{{--                text: text,--}}
{{--                id: id--}}
{{--            }, function (res) {--}}
{{--                if (res.code == 0) {--}}
{{--                    rtn_bar_check = false;--}}
{{--                    elem.siblings(".product-not-found-alert").text(res.error);--}}
{{--                    elem.siblings(".product-not-found-alert").show();--}}
{{--                    elem.siblings(".product-found-alert").hide();--}}
{{--                    elem.siblings(".product-quantity-text-alert").hide();--}}
{{--                    elem.siblings(".product-quantity-alert").hide();--}}
{{--                    elem.siblings(".product-show").hide();--}}
{{--                } else {--}}
{{--                    rtn_bar_check = true;--}}
{{--                    // elem.siblings(".product-not-found-alert").hide();--}}
{{--                    // elem.siblings(".product-show").show();--}}
{{--                    // elem.siblings(".product-found-alert").show();--}}
{{--                    // elem.siblings(".product-quantity-text-alert").show();--}}
{{--                    // elem.siblings(".product-quantity-text-alert").find('.product-quantity-alert').text(res.available_qty);--}}
{{--                    // elem.siblings(".product-quantity-text-alert").find('.product-quantity-alert').show();--}}
{{--                }--}}
{{--                rtn_bar_check = true;--}}
{{--                ex_bar_check = true;--}}
{{--                $('#invoice_detail').html('');--}}
{{--                $('#invoice_detail').html(res);--}}
{{--                $('#invoice_detail').addClass('d-none').removeClass('d-none');--}}
{{--                checkReturnRequestValidation()--}}
{{--            });--}}
{{--        }--}}
{{--    })--}}
{{--    $("#exchanged_product_barcode").keyup(function () {--}}
{{--        var elem = $("#exchanged_product_barcode");--}}
{{--        var text = elem.val();--}}
{{--        if(text == 0 || text == ''){--}}
{{--            elem.siblings(".product-not-found-alert").hide();--}}
{{--            elem.siblings(".product-found-alert").hide();--}}
{{--            elem.siblings(".product-quantity-text-alert").hide();--}}
{{--            elem.siblings(".product-quantity-alert").hide();--}}
{{--            elem.siblings(".exchanged-product-show").hide();--}}
{{--            checkReturnRequestValidation()--}}
{{--        }--}}
{{--        if (text) {--}}
{{--            $.post("{{ route('admin.exchanged.product.info.with.ajax') }}", {text: text}, function (res) {--}}
{{--                if (res.code == 0) {--}}
{{--                    ex_bar_check = false;--}}
{{--                    elem.siblings(".product-not-found-alert").text(res.error);--}}
{{--                    elem.siblings(".product-not-found-alert").show();--}}
{{--                    elem.siblings(".product-found-alert").hide();--}}
{{--                    elem.siblings(".product-quantity-text-alert").hide();--}}
{{--                    elem.siblings(".product-quantity-alert").hide();--}}
{{--                    elem.siblings(".exchanged-product-show").hide();--}}
{{--                } else {--}}
{{--                    ex_bar_check = true;--}}
{{--                    // elem.siblings(".product-not-found-alert").hide();--}}
{{--                    // elem.siblings(".exchanged-product-show").show();--}}
{{--                    // elem.siblings(".product-found-alert").show();--}}
{{--                    // elem.siblings(".product-quantity-text-alert").show();--}}
{{--                    // elem.siblings(".product-quantity-text-alert").find('.product-quantity-alert').text(res.available_qty);--}}
{{--                    // elem.siblings(".product-quantity-text-alert").find('.product-quantity-alert').show();--}}
{{--                }--}}
{{--                checkReturnRequestValidation();--}}
{{--            });--}}
{{--        }--}}
{{--    })--}}
{{--    checkReturnRequestValidation();--}}
{{--    $(".return_product_search_field").trigger('keyup');--}}

{{--    /////////////////////////--}}
{{--    $(document).off('click', '#instant_exchange_checkbox').on('click', '#instant_exchange_checkbox', function () {--}}
{{--        if( $(this).is(':checked') ) {--}}
{{--            var idsArr = [];--}}

{{--            $('.return_bar_code').find('input[type=checkbox]:checked').each(function() {--}}
{{--                idsArr .push(this.value);--}}
{{--            });--}}

{{--            console.log(idsArr);--}}
{{--            var elem = $("#exchanged_product_barcode");--}}
{{--            var text = idsArr;--}}
{{--            // var text = $("input[name=return_product_bar_code[]]").val();--}}
{{--            // if (text == 0 || text == '') {--}}
{{--            //     elem.siblings(".product-not-found-alert").hide();--}}
{{--            //     elem.siblings(".product-found-alert").hide();--}}
{{--            //     elem.siblings(".product-quantity-text-alert").hide();--}}
{{--            //     elem.siblings(".product-quantity-alert").hide();--}}
{{--            //     elem.siblings(".exchanged-product-show").hide();--}}
{{--            //     checkReturnRequestValidation()--}}
{{--            // }--}}
{{--            // if (text) {--}}
{{--            console.log(text)--}}
{{--            --}}{{--$.post("{{ route('admin.exchanged.product.info.with.ajax') }}", {text: text}, function (res) {--}}
{{--            $.post("{{ route('admin.exchanged.product.barcode.list') }}", {text: text}, function (res) {--}}
{{--                if (res.code == 0) {--}}
{{--                    ex_bar_check = false;--}}
{{--                    elem.siblings(".product-not-found-alert").text(res.error);--}}
{{--                    elem.siblings(".product-not-found-alert").show();--}}
{{--                    elem.siblings(".product-found-alert").hide();--}}
{{--                    elem.siblings(".product-quantity-text-alert").hide();--}}
{{--                    elem.siblings(".product-quantity-alert").hide();--}}
{{--                    elem.siblings(".exchanged-product-show").hide();--}}
{{--                } else {--}}
{{--                    // ex_bar_check = true;--}}
{{--                    // elem.siblings(".product-not-found-alert").hide();--}}
{{--                    // elem.siblings(".exchanged-product-show").show();--}}
{{--                    // elem.siblings(".product-found-alert").show();--}}
{{--                    // elem.siblings(".product-quantity-text-alert").show();--}}
{{--                    // elem.siblings(".product-quantity-text-alert").find('.product-quantity-alert').text(res.available_qty);--}}
{{--                    // elem.siblings(".product-quantity-text-alert").find('.product-quantity-alert').show();--}}
{{--                }--}}
{{--                $('#exchanged_product_barcode_elem').html('');--}}
{{--                $('#exchanged_product_barcode_elem').html(res);--}}
{{--                $('#exchanged_product_barcode_elem').addClass('d-none').removeClass('d-none');--}}
{{--                // $(".return_checkbox").prop("disabled", true);--}}
{{--                // $(".exchange_checkbox").prop("disabled", true);--}}
{{--                rtn_bar_check =true;--}}
{{--                ex_bar_check = true;--}}
{{--                //console.log('dd')--}}
{{--                checkReturnRequestValidation();--}}
{{--            });--}}
{{--        }else{--}}
{{--            $('#exchanged_product_barcode_elem').html('');--}}
{{--            // $(".return_checkbox").prop("disabled", false);--}}
{{--            // $(".exchange_checkbox").prop("disabled", false);--}}
{{--        }--}}
{{--        // }--}}
{{--    })--}}
</script>
