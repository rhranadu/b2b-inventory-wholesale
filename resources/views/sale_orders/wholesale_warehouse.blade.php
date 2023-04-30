@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', '')

@push('css')
@endpush

@section('main_content')
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    @include('component.message')
                    <div class="row align-items-center">
                    {{--                                <div class="form-group col-md-3">--}}
                    {{--                                    <label for="#">Vendor Name</label>--}}
                    {{--                                    <select name="vendor_id" id="vendor_id" class="selectpicker form-control" data-live-search="true">--}}
                    {{--                                        <option value="">*Select Vendor</option>--}}
                    {{--                                        @foreach($vendors as $vendor)--}}
                    {{--                                            <option value="{{ $vendor->id }}" >{{ $vendor->name }}</option>--}}
                    {{--                                        @endforeach--}}
                    {{--                                    </select>--}}
                    {{--                                </div>--}}
                        <div class="form-group col-md-2">
                            <label for="#">Start Date</label>
                            <input required name="date" data-date="" data-date-format="DD MMMM YYYY"
                                     type="date" class="form-control"
                                    id="startDate">
                    {{--                                    <p id="autofocusDate"></p>--}}
                        </div>
                        <div class="form-group col-md-2">
                            <label for="#">End Date</label>
                            <input required name="date" data-date="" data-date-format="DD MMMM YYYY"
                             type="date" class="form-control"
                            id="endDate">
                    {{--                                    <p id="autofocusDate"></p>--}}
                        </div>
                        <div class="form-group col-md-2">
                            <label for="#">Order Status</label>
                            <select name="order_status" id="orderStatus" class="form-control" >
                                <option value="">All</option>
                                <option value="submitted">Submitted</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="processed">Processed</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                            </select>
                        </div>
                        {{-- <div class="form-group col-md-2">
                            <label for="#">Order From</label>
                            <select name="order_from" id="orderFrom" class="form-control">
                                <option value="">Both</option>
                                <option value="1">POS</option>
                                <option value="2">Marketplace</option>
                            </select>
                        </div> --}}
                        <div class="form-group col-md-2">
                            <label for="#">Invoice</label>
                            <select name="invoice" id="invoice" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-primary mt-7" id="search">Search</button>
                        </div>
                    </div>
                        <div class="table-responsive">
                            <table
                                class="table table-hover table-bordered table-condensed manufacturerDataTable"
                                id="datatable">
                                <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th class="text-center">Placed At</th>
                                    <th class="text-center">Placed From</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Attribute</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Per Price</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Available Stock</th>
                                    <th class="text-center">Invoice</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modalBarcode" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmed Product Barcode</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <input type="hidden" id="unique_sale_detail_id" value="">
                <input type="hidden" id="unique_qty" value="">
                <br>
                <div class="modal-body">
                    <table class="table table-light-info" >
                        <thead>
                            <tr>
                                <th class="span6" scope="col" id="totalStoreQty">  </th>
                                <th class="span4" scope="col" id="givenBarcode"> </th>
                            </tr>
                        </thead>
                    </table>
                    <p id="validateMsg" style="color: #9d0006"><b></b></p>
                    <div class="appendBarcodeType"></div>
                    <div id="tag-list" style="display:none"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="save_barcode_data" onclick="" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <form target="_blank" action="{{ route('admin.sale.order.detail') }}" id="orderDetailForm" method="post">
        @csrf
        <input type="hidden" name="sale_detail_id" />
        <input type="submit" class="d-none" value="Submit">
    </form>

@endsection

@push('script')
<script>
    $(".alert").delay(5000).slideUp(300);
    $.ajaxSetup({
        headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
    });
    $("#orderFrom").select2({
        minimumResultsForSearch: Infinity
    });
    $("#orderStatus").select2({
        minimumResultsForSearch: Infinity
    });
    $("#invoice").select2({
        width: '100%',
        ajax: {
            url: "{{ route("admin.sale.order.invoice.list") }}",
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {search: params.term};
            },
            processResults: function (data, params) {
                return {
                    results: $.map(data, function (item) {
                        return {id: item, text: item}
                    }),

                };
            },
            cache: true
        },
        placeholder: 'Search for a invoice',
    });

    var datatable =   $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{route('admin.sale.order.wholesale.ajax')}}',
            type: "POST",
            data: function (d) {
                d.start_date = $('#startDate').val();
                d.end_date = $('#endDate').val();
                d.order_status = $('#orderStatus :selected').val();
                d.order_from = $('#orderFrom :selected').val();
                d.invoice = $('#invoice :selected').val();
            },
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
                download: 'open',
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
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'placed_at', name: 'placed_at'},
            {data: 'placed_from', name: 'placed_from'},
            {data: 'name', name: 'product_name'},
            {data: 'attribute', name: 'attribute'},
            {data: 'quantity', name: 'order_quantity'},
            {data: 'per_price', name: 'per_price'},
            {data: 'total_price', name: 'total_price'},
            {data: 'stock', name: 'stock'},
            {data: 'invoice', name: 'invoice_no'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'},
        ],
        columnDefs: [{
            targets: '_all',
            defaultContent: 'N/A'
        }],
    });

    $('#search').on('click', function(e) {
        datatable.draw();
        e.preventDefault();
    });
    $(document).off('click', '.advance').on('click', '.advance', function (e) {
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
            cancelButtonColor: "#AF0000",
            showCancelButton: true,
            confirmButtonText: 'Yes, go for it!'
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                updateSaleOrderStatus(this);
            }
        })
    });
    $(document).off('click', '.detail').on('click', '.detail', function (e) {
        $("#orderDetailForm").find('input[name="sale_detail_id"]').val($(this).data('id'));
        $("#orderDetailForm").submit();
    });
    function updateSaleOrderStatus(elem) {
        $.ajax({
            url: "{{ route('admin.sale.order.details.status')}}",
            method: "POST",
            data: {
                sale_detail_id: $(elem).data('id'),
            },
            success: function (response) {
                if (response.success == true) {
                    toastr.success(response.msg);
                    datatable.draw();
                }
                if (response.success == false){
                    toastr.error(response.msg);
                }
            },
        });
    }
    var global_barcode_array = {};
    $(document).off('click', '.confirm').on('click', '.confirm', function () {
        showBarcodeModal(this);
    });
    $(document).off('click', '#save_barcode_data').on('click', '#save_barcode_data', function () {
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
            cancelButtonColor: "#AF0000",
            showCancelButton: true,
            confirmButtonText: 'Yes, go for it!'
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                confirmOrder();
            }
        })
    });
    function confirmOrder() {
        var qty = $('#unique_qty').val();
        var unique_sale_detail_id = $('#unique_sale_detail_id').val();
        if (global_barcode_array[unique_sale_detail_id].length < qty){
            var validateMsg = "Please Insert total "+qty+" number of Barcodes . Currently inserted "+global_barcode_array[unique_sale_detail_id].length+" Barcodes!";
            $('#validateMsg').html(validateMsg);
            document.getElementById("save_barcode_data").disabled = true;
            return false;
        }else {
            var data = {
                sale_detail_id: $('#unique_sale_detail_id').val(),
                quantity: $('#unique_qty').val(),
                barcode: global_barcode_array[unique_sale_detail_id],
            }
            $.ajax({
                url: "{{ route('admin.sale.order.confirm')}}",
                method: "POST",
                data: data,
                success: function (response) {
                    if (response.success == true) {
                        toastr.success(response.msg);
                        datatable.draw();
                    }
                    if (response.success == false){
                        toastr.error(response.msg);
                    }
                },
            });
            document.getElementById("save_barcode_data").disabled = false;
            $('#modalBarcode').modal('hide');
        }
    }
    function showBarcodeModal(elem) {
        var qty = $(elem).data('qty');
        var sale_detail_id = $(elem).data('id');
        $('#unique_sale_detail_id').val(sale_detail_id);
        $('#unique_qty').val(qty);
        if(isEmpty(global_barcode_array[sale_detail_id])){
            global_barcode_array[sale_detail_id] = [];
        }
        if (qty > 0 ) {
            $('.appendBarcodeType').html('');
            $('#validateMsg').html('');
            $('#givenBarcode').html('');
            $('#modalBarcode').modal('show');
            $('#totalStoreQty').html('<p style="color: #AF0000">Total Store Quantity = '+qty+' </p>');
            let inserted_barcode = global_barcode_array[sale_detail_id].length;
            $('#givenBarcode').html('<p style="color: #AF0000">Total Barcode Inserted = '+inserted_barcode+' </p>');
            manual_barcode_generate_type(elem);
        }
        else {
            alert ('Please Insert Store Quantity');
        }
    }
    function manual_barcode_generate_type(elem) {
        var qty = $(elem).data('qty');
        var sale_detail_id = $(elem).data('id');
        $('.appendBarcodeType').html('');
        var setInput = '';
        setInput += "<div class='col-xs-2'><input type='text' id='barcode_row_first' placeholder='Type your barcodes here and press enter' name='barcodeFirst' class='form-control barcodeFirst' ></div>";
        $('.appendBarcodeType').append(setInput);

        var input = document.getElementById("barcode_row_first");
        input.focus();

        // Execute a function when the user releases a key on the keyboard
        input.addEventListener("keyup", function(event) {
            // Number 13 is the "Enter" key on the keyboard
            if (event.keyCode === 13) {
                // Cancel the default action, if needed
                event.preventDefault();
                manual_barcode_checking(elem);
            }
        });
        // Execute a function when the user blur the input field
        input.addEventListener("blur", function(event) {
                event.preventDefault();
                manual_barcode_checking(elem);
        });
    }

    function manual_barcode_checking(elem){
        var qty = $(elem).data('qty');
        var sale_detail_id = $(elem).data('id');
        var input_barcode_val = document.getElementById("barcode_row_first").value;
        // var product_id = document.getElementById("unique_product_id").value;


        if(input_barcode_val){
            $.post("{{ route('admin.sale.order.barcode.check') }}", {
                sale_detail_id: sale_detail_id,
                barcode: input_barcode_val,
            }, function (res) {
                var unique_sale_detail_id = sale_detail_id;
                if(res.code == 1){
                    let unique_barcode_val = $("#barcode_row_first").val();

                    if ( global_barcode_array[unique_sale_detail_id].length >= qty){
                        var barcodeLimitMsg = "Already inserted the total quantity of barcodes please save!";
                        $('#validateMsg').html(barcodeLimitMsg);
                        // $('.appendBarcodeType').html('');
                        document.getElementById("save_barcode_data").disabled = false;
                        return false;
                    }else{
                        if($.inArray(unique_barcode_val,global_barcode_array[unique_sale_detail_id]) != -1){
                            var validateMsg = "This Barcode already added!";
                            $('#validateMsg').html(validateMsg);
                            $("#barcode_row_first").addClass('is-invalid');
                            if(!(global_barcode_array[unique_sale_detail_id].length >= qty)){
                                document.getElementById("save_barcode_data").disabled = true;
                            }
                            return false;
                        }
                        $('#validateMsg').html('');
                        $("#barcode_row_first").removeClass('is-invalid');
                        if (unique_barcode_val !== '' ){
                            global_barcode_array[unique_sale_detail_id].push(unique_barcode_val);
                            $("#barcode_row_first").val('');
                            let inserted_barcode = global_barcode_array[unique_sale_detail_id].length;
                            $('#givenBarcode').html('<p style="color: #AF0000">Total Barcode Inserted = '+inserted_barcode+' </p>');
                            $("#tag-list").show();
                            var output = '<span class="badge badge-secondary mr-2" data-barcode_id="'+inserted_barcode+'" data-barcode_val="'+unique_barcode_val+'">' +unique_barcode_val+
                                        '&nbsp;<a class="btn-link mr-2 single_barcode_remove" href="#0" data-unique_sale_detail_id="'+unique_sale_detail_id+'" data-barcode_val="'+unique_barcode_val+'"> <i class="fa fa-times"></i></a>'+
                                        '</span>';
                            $("#tag-list").append(output);
                        }
                        document.getElementById("save_barcode_data").disabled = false;
                    }
                } else {
                    $('#validateMsg').html("Not Valid For This Order!");
                    $("#barcode_row_first").addClass('is-invalid');
                    if(!(global_barcode_array[unique_sale_detail_id].length >= qty)){
                        document.getElementById("save_barcode_data").disabled = true;
                    }
                }

            });

        }
    }

    Array.prototype.remove = function() {
        var what, a = arguments, L = a.length, ax;
        while (L && this.length) {
            what = a[--L];
            while ((ax = this.indexOf(what)) !== -1) {
                this.splice(ax, 1);
            }
        }
        return this;
    };
    $(document).off('click', '.single_barcode_remove').on('click', '.single_barcode_remove', function () {
        var barcode_val = $(this).data('barcode_val');
        var unique_sale_detail_id = $(this).data('unique_sale_detail_id');
        var ary = global_barcode_array[unique_sale_detail_id];
        if($.inArray(barcode_val,global_barcode_array[unique_sale_detail_id]) != -1){
            ary.remove(barcode_val);
        }
        $(this).parent().remove();
        let inserted_barcode = global_barcode_array[unique_sale_detail_id].length;
        $('#givenBarcode').html('')
        if(inserted_barcode > 0){
            $('#givenBarcode').html('<p style="color: #AF0000">Total Barcode Inserted = '+inserted_barcode+' </p>');
        }
    });

</script>
@endpush
