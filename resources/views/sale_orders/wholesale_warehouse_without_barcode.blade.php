@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Sale Orders Wholesale')

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
            confirmButtonText: 'Yes, go for it!'
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                confirmOrder(this);
            }
        })
    });

    function confirmOrder(elem) {
        var data = {
            sale_detail_id: $(elem).data('id'),
            quantity: $(elem).data('qty'),
        }
        $.ajax({
            url: "{{ route('admin.sale.order.confirm.without.barcode')}}",
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

    }

</script>
@endpush
