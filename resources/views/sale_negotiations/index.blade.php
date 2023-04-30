@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Order Negotiation')

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
                                <option value="requested">Requested</option>
                                <option value="vendor_confirmed">Confirmed By Vendor</option>
                                <option value="client_confirmed">Confirmed By Client</option>
                                <option value="vendor_rejected">Rejected By Vendor</option>
                                <option value="client_rejected">Rejected By Client</option>
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
                        {{-- <div class="form-group col-md-2">
                            <label for="#">Invoice</label>
                            <select name="invoice" id="invoice" class="form-control" data-live-search="true"></select>
                        </div> --}}
                       <div class="form-group col-md-1">
                            <button class="btn btn-danger mt-7" id="reset_btn" type="reset">Reset</button>
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
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Attribute</th>

                                    <th class="text-center">Original Price</th>
                                    <th class="text-center">Customer Requested Price</th>
                                    <th class="text-center">Customer Requested Quantity</th>
                                    <th class="text-center">Total Requested Price</th>

                                    <th class="text-center">Your Asking Price</th>
                                    <th class="text-center">Your Asking Quantity</th>
                                    <th class="text-center">Total Asking Price</th>

                                    <th class="text-center">Available Stock</th>

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
    <div id="negotiateModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Negotiate This Order</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                <form id="negotiationForm" >
                    @csrf
                    <input type="hidden" name="id" id="sni" value="">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="vap">Asking Price
                                <span style="color: red; font-size: 18px;"><sup>*</sup></span>
                            </label>
                            <input class="form-control" id="vap" autocomplete="off" name="vendor_asking_price" type="number">
                        </div>
                        <div class="col-sm-4">
                            <label for="vaq">Quantity</label>
                            <input class="form-control" id="vaq" autocomplete="off" name="vendor_asking_quantity" type="number">
                        </div>
                        <div class="col-sm-4">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                <input type="checkbox" value="1" id="final" name="is_final">
                                <span></span>Final Negotiation</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="vr">Remarks</label>
                            <input class="form-control" id="vr" value="" name="vendor_remarks" type="textarea">
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col">
                            <p>Client Remarks: <span id="cr">This is a test client remarks</span></p>
                        </div>
                    </div>
                </form>
                </div>


                <div class="modal-footer">
                    <button type="submit" id="submitNegotiation" class="btn btn-primary">Save</button>
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
    $(document).off('click', '#reset_btn').on('click', '#reset_btn', function() {
        $("select[name=order_status]").val("").trigger("change");
        $('input[name=date]').val("").trigger("change");
    });
    $(".alert").delay(5000).slideUp(300);
    $.ajaxSetup({
        headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
    });

    var datatable =   $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{route('admin.sale.negotiation.ajax')}}',
            type: "POST",
            data: function (d) {
                d.start_date = $('#startDate').val();
                d.end_date = $('#endDate').val();
                d.order_status = $('#orderStatus :selected').val();
                // d.order_from = $('#orderFrom :selected').val();
                // d.invoice = $('#invoice :selected').val();
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
            {data: 'product_name', name: 'product_name'},
            {data: 'attribute_pair', name: 'attribute'},
            {data: 'original_price', name: 'original_price'},


            {data: 'client_asking_price', name: 'client_asking_price'},
            {data: 'client_asking_quantity', name: 'client_asking_quantity'},
            {data: 'client_asking_total', name: 'client_asking_total'},

            {data: 'vendor_asking_price', name: 'vendor_asking_price'},
            {data: 'vendor_asking_quantity', name: 'vendor_asking_quantity'},
            {data: 'vendor_asking_total', name: 'vendor_asking_total'},
            {data: 'available_quantity', name: 'available_quantity'},
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
    $(document).off('click', '.negotiate').on('click', '.negotiate', function () {
        showNegotiateModal(this);
    });
    $(document).off('click', '.reject').on('click', '.reject', function () {

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
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                $.ajax({
                    url: "{{url('admin/negotiation-reject')}}",
                    type: 'get',
                    dataType: 'json',
                    data: {id : $(this).data('id')},
                    beforeSend: function(){
                        $.blockUI();
                    },
                    complete: function(){
                        $.unblockUI();
                    },
                    success: function(response) {
                        if(response.status == 'success'){
                            toastr.success(response.data.message);
                            datatable.draw();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(response) {
                        toastr.error('Request not completed');
                    }
                });
            }
        })
    });
    $(document).off('click', '.confirm').on('click', '.confirm', function () {

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
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                confirmNegotiation(this);
            }
        })
    });

    // $(document).off('click', '.detail').on('click', '.detail', function (e) {
    //     $("#orderDetailForm").find('input[name="sale_detail_id"]').val($(this).data('id'));
    //     $("#orderDetailForm").submit();
    // });

    $(document).off('click', '#submitNegotiation').on('click', '#submitNegotiation', function () {
        var vap = $('#negotiateModal').find('#vap').val();
        var vaq = $('#negotiateModal').find('#vaq').val();
        if(isEmpty(vap)){
            toastr.error('Asking price can not be empty');
            return false;
        }
        if(! Math.sign(vap) > 0){
            toastr.error('Asking price only positive number');
            return false;
        }
        if(! Math.sign(vaq) > 0){
            toastr.error('Quantity can only full positive number');
            return false;
        }
        if((vaq^0) === vaq){
            toastr.error('Quantity can only full positive number');
            return false;
        }

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
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                submitNegotiation();
            }
        })
    });
    function submitNegotiation() {
        var formData = new FormData($("#negotiationForm")[0]);

        $.ajax({
            url: "{{route('admin.sale.negotiate')}}",
            type: 'post',
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $.blockUI();
            },
            complete: function(){
                $.unblockUI();
            },
            success: function(response) {
                if(response.status == 'success'){
                    toastr.success(response.message);
                    $("#negotiateModal").modal('hide');
                    datatable.draw();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(response) {
                toastr.error('Request not completed');
            }
        });
    }
    function confirmNegotiation(elem) {
        $.ajax({
            url: "{{route('admin.sale.negotiation.confirm')}}",
            type: 'post',
            dataType: 'json',
            data: {id : $(elem).data('id')},
            beforeSend: function(){
                $.blockUI();
            },
            complete: function(){
                $.unblockUI();
            },
            success: function(response) {
                if(response.status == 'success'){
                    Swal.fire({
                        title: '<strong>'+response.data.message+'</strong>',
                        icon: 'success',
                        html: 'Invoice no: <b>'+response.data.invoice_no+'</b>',
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonText: 'Ok',
                        cancelButtonText: 'Close',
                    })
                    datatable.draw();
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: response.message,
                    })
                }
            },
            error: function(response) {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong!',
                })
            }
        });
    }

    function showNegotiateModal(elem) {
        var sni = $(elem).data('id');
        $('#negotiateModal').find('#sni').val($(elem).data('id'));
        $('#negotiateModal').find('#vap').val($(elem).data('vap'));
        if(parseInt($(elem).data('vaq')) > 0){
            $('#negotiateModal').find('#vaq').val($(elem).data('vaq'));
        }else{
            $('#negotiateModal').find('#vaq').val($(elem).data('caq'));
        }
        $('#negotiateModal').find('#vr').val($(elem).data('vr'));
        $('#negotiateModal').find('#cr').text($(elem).data('cr'));
        $('#negotiateModal').find('#final').prop('checked', parseInt($(elem).data('final')) > 0);
        $('#negotiateModal').modal('show');
    }

</script>
@endpush
