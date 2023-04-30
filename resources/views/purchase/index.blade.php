@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Purchases')
@push('css')
    .table th, .table td{vertical-align:inherit;}
@endpush

@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    @include('component.message')
                        <div class="normal-table-list">
                            <div class="bsc-tbl">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-condensed" id="purchaseOrdersDataTable">
                                        <thead>
                                        <tr>
                                            <th class="text-center">SI</th>
                                            <th class="text-center" >Date</th>
                                            <th class="text-center">Invoice No</th>
                                            <th class="text-center" >Supplier Name</th>
                                            <th class="text-center" >Total Item</th>
                                            <th class="text-center" >Total Qty</th>
                                            <th class="text-center" >Total Receive</th>
                                            <th class="text-center" >Status</th>
                                            <th class="text-center notexport" >Action</th>
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
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->

@endsection

@push('script')


    <script>

        $(".alert").delay(5000).slideUp(300);
        $.ajaxSetup({
            headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
        });
        var purchaseOrdersDataTable =   $('#purchaseOrdersDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('admin.purchase.ajax')}}',
                type: "POST",
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
                {data: 'date', name: 'date'},
                {data: 'invoice_no', name: 'invoice_no'},
                {data: 'purchase_supplier.name', name: 'purchase_supplier.name'},
                {data: 'purchaseDetail_count', name: 'purchaseDetail_count'},
                {data: 'purchaseDetail_quantity', name: 'purchaseDetail_quantity'},
                {data: 'total_rcv', name: 'total_rcv'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'},
            ],
            columnDefs: [{
                targets: '_all',
                defaultContent: 'N/A'
            }],
        });

        $('#search').on('click', function(e) {
            purchaseOrdersDataTable.draw();
            e.preventDefault();
        });



        // delete  purchases
        function deletePurchases(id) {
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
                    document.getElementById('deletePurchasesForm-' + id).submit();
                }
            })
        };

        // purchase stocked
        function stockPurchases(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButtonColor: '#00c292',
                    cancelButton: 'btn btn-danger mt-0'
                },
                buttonsStyling: true
            });
            swalWithBootstrapButtons.fire({
                title: 'Are you sure to stock this item?',
                type: 'warning',
                cancelButtonColor: "#AF0000",
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('purchasesStock-' + id).submit();
                }
            })
        };
    </script>


@endpush
