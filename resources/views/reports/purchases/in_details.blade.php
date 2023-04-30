@extends('layouts.app')

@section('title', 'Purchase Reports Details')

@push('css')

@endpush

@section('main_content')

    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Purchases Reports Details <i class="mr-2"></i><small>purchases report
                        details</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.purchase.reports.index') }}" class="btn btn-sm btn-light-success">
                    <i class="fa fa-list"></i> Purchases Report index
                </a>
            </div>
        </div>
        <div class="card-body">

            @include('component.message')
            @if (isset($purchase))
                <div class="normal-table-list">
                    <div class="bsc-tbl">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th class="text-center">Invoice No</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Supplier Name</th>
                                    <th class="text-center">Total Items</th>
                                    <th class="text-center">Total Amount</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Pay Status</th>
                                    <th class="text-center" width="200px">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="text-center">
                                    <td>#{{ $purchase->invoice_no }}</td>
                                    <td>
                                        <a href="{{ route('admin.purchase.report.date.wise', $purchase->date) }}">{{ $purchase->date }}</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.get.supplier.report.indetails', $purchase->purchaseSupplier->id) }}">{{ $purchase->purchaseSupplier->name }}</a>
                                    </td>
                                    <td>{{ $purchase->purchaseDetail->count() }}</td>
                                    <td>{{ $purchase->purchase_total_amount }}</td>
                                    <td>
                                        <span class="badge"
                                              style="{{ $purchase->status == 1 ? 'background: green;' : 'background:danger' }}">{{ $purchase->status == 1 ? 'Submitted' : 'Not Submit' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $status = null;
                                        @endphp
                                        @foreach($purchase->purchasePayment as $getStatus)
                                            @php
                                                $status = $getStatus->status
                                            @endphp
                                        @endforeach
                                        @if(isset($status))
                                            @if($status == 1)
                                                <span class="badge" style="background: green">Paid</span>
                                            @else
                                                <span class="badge" style="background: orange">Not Full Paid</span>
                                            @endif
                                        @else
                                            <span class="badge" style="background: red">Full Due</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.purchase.show', $purchase->id) }}"
                                           class="btn btn-sm btn-info waves-effect" data-toggle="tooltip"
                                           data-placement="auto" title="" data-original-title="VIEW"><i
                                                class="fa fa-eye"></i> </a>
                                        @if($purchase->status == 1)
                                            <a href="{{ route('admin.purchase.add.payment', $purchase->id) }}"
                                               class="btn btn-sm btn-info waves-effect" data-toggle="tooltip"
                                               data-placement="auto" title="" data-original-title="Add Payment">Pay</a>
                                        @else
                                            <a href="{{ route('admin.purchase.stock', $purchase->id) }}"
                                               class="btn btn-sm btn-{{ $purchase->status == 1 ? 'success' : 'danger' }}   waves-effect"
                                               data-toggle="tooltip" data-placement="auto" title=""
                                               data-original-title="{{ $purchase->status == 1 ? 'Alrady Stocked' : 'Want to Stock' }}"><i
                                                    class="fa fa-check-circle"></i> </a>
                                        @endif
                                        @if($purchase->status !== 1)
                                            <a href="{{ route('admin.purchase.edit', $purchase->id) }}"
                                               class="btn btn-sm btn-warning waves-effect btn-icon" data-toggle="tooltip"
                                               data-placement="auto" title="" data-original-title="EDIT"><i
                                                    class="fas fa-pencil-alt"></i> </a>
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            @else
                <div class="alert alert-custom alert-outline-2x alert-outline-warning fade show mb-5">
                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                    <div class="alert-text h4 mb-0">No data found</div>
                </div>
            @endif
        </div>
    </div>

    <div class="area-chart-area" style="margin-top: 50px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @if (isset($products) && count($products) > 0)
                        <div class="table-responsive" style="background: #fff">
                            <h1 style="padding: 10px">Product List</h1>
                            <table class="table table-hover table-bordered table-condensed" id="productTable">
                                <thead>
                                <tr>
                                    <th class="text-center">SI</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Model</th>
                                    
                                        <th class="text-center">Company Name</th>
                                   
                                    <th class="text-center">Brand</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Updated By</th>
                                    <th width="150" class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr class="text-center">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            <img width="50" height="50" src="{{ asset($product->image_path) }}" alt="">
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->product_model }}</td>
                                        
                                            <td>{{ $product->productCompany->name }}</td>
                                        
                                        <td>{{ $product->productBrand->name }}</td>
                                        <td>{{ $product->productCategory->name }}</td>
                                        <td>
                                            {{ $product->productUpdatedUser->name }}
                                        </td>

                                        <td>
                                            <a href="{{ route('admin.product.show', $product->id) }}"
                                               class="btn btn-sm btn-info waves-effect" data-toggle="tooltip"
                                               data-placement="auto" title="" data-original-title="VIEW"><i
                                                    class="fa fa-eye"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">No data found</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection



@push('script')

@endpush
