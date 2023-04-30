@extends('layouts.crud-master')
@section('title', 'Purchase Details')

@push('css')
@endpush

@section('main_content')
    <!--begin::Entry-->
    <div class="flex-column-fluid">
        <!--begin::Container-->

        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container-fluid">
                <div class="card card-custom min-h-500px" id="kt_card_1">
                    <div class="card-body">
                        @include('component.message')
                        <div id="ui-view" data-select2-id="ui-view">
                            <div>
                                <div class="card">
                                    <div class="card-header">Invoice
                                        <strong>#{{ $purchase->invoice_no }}</strong>
                                        <div>Date: {{ $purchase->date }}</div>
                                        <div class="">
                                            {{-- <a class="btn btn-sm btn-secondary float-right mr-1 d-print-none" href="#" onclick="javascript:window.print();" data-abc="true">
                                                 <i class="fa fa-print"></i> Print</a>
                                             <a class="btn btn-sm btn-info float-right mr-1 d-print-none" href="#" data-abc="true">
                                                 <i class="fa fa-save"></i> Save</a>--}}
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" style="padding: 20px;">
                                            <div class="col-sm-4">
                                                <h6 class="mb-3">From:</h6>
                                                <div>
                                                    <h5>{{ $purchase->purchaseVendor->name }},</h5>
                                                </div>
                                                <div><b>Email:</b> {{ $purchase->purchaseVendor->email }}</div>
                                                <div><b>Phone:</b> {{ $purchase->purchaseVendor->phone }}</div>
                                                <div><b>Address:</b> {{ $purchase->purchaseVendor->address }}</div>
                                            </div>
                                            <div class="col-sm-4"></div>
                                            <div class="col-sm-4">
                                                <h6 class="mb-3">To:</h6>
                                                <div><h5>{{ $purchase->purchaseSupplier->name }},</h5></div>
                                                <div><b>Email:</b> {{ $purchase->purchaseSupplier->email }}</div>
                                                <div><b>Phone:</b> {{ $purchase->purchaseSupplier->mobile }}</div>
                                                <div><b>Address:</b> {{ $purchase->purchaseSupplier->address }}</div>
                                            </div>
                                        </div>
                                        <div class="table-responsive-sm">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>SI</th>
                                                    <th>Product Name</th>
                                                    <th>PQ</th>
                                                    <th>Status</th>
                                                    <th>Stored Warehouse</th>
                                                    <th>Total Amount</th>
                                                    <th>Last Stored Date</th>
                                                    <th>Barcode</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($purchase->purchaseDetail as $item)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td style="width:200px">
                                                            {{ $item->product->name }}
                                                            @foreach($item->purchaseAttributeDetails as $pad)
                                                                <span class="badge badge-dark">{{ $pad->attribute_name }} - {{ $pad->attribute_map_name }}</span>
                                                            @endforeach
                                                        </td>
                                                        <td class="text-center">{{ $item->quantity }}</td>
                                                        <td class="text-center">
                                                            @if($item->status == 'FR')
                                                                <span class="badge badge-success" >Full Received</span>
                                                            @endif
                                                            @if($item->status == 'DC')
                                                                <span class="badge badge-danger" >Discard</span>
                                                            @endif
                                                            @if($item->status == 'PR')
                                                                <span class="badge badge-warning">Pertial Received</span>
                                                            @endif
                                                            @if($item->status == 'NY')
                                                                <span class="badge badge-danger" >Not Yet</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @foreach($item->stockWarehouse as $wareh)
                                                                <span
                                                                    class="badge badge-primary">{{ $wareh->warehouse->name }} - {{ $wareh->available_quantity }} </span>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            <span class="">{{ number_format($item->stockWarehouse->sum('total_price'), 2) }} </span>
                                                        </td>
                                                        <td>
                                                            @if(isset($item->stockWarehouse->last()->updated_at))
                                                                <span>{{ $item->stockWarehouse->last()->updated_at->isoFormat('MMM Do YY') }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info" onclick="viewBarcodeModal('{!! $item->id !!}')"><i class="fa fa-barcode"></i> </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Engine End-->


    <div class="modal fade" id="barcode_viewer" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background: #81abe0 !important;">
                    <h5 class="modal-title" style="color:white;">View Barcode</h5>
                    <button type="button" class="btn btn-link-danger" data-dismiss="modal" aria-label="Close" style="color:white;">
                        <i aria-hidden="true" class="fa fa-times" style="color: white"></i>
                    </button>
                </div>

                <div class="modal-body" id="printable">
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
        function viewBarcodeModal(purchaseDetailId) {
            $.ajax({
                url: '/admin/get-barcode-list/' + purchaseDetailId,
                method: 'get',
                data: {},
                success: function(response) {
                    $("#barcode_viewer .modal-body").html(response);
                    $("#barcode_viewer").modal('show')
                }
            })
        }
    </script>

@endpush
