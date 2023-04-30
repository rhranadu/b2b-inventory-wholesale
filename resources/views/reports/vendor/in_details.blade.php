@extends('layouts.app')

@section('title', 'Supplier Reports Details')

@push('css')

@endpush

@section('main_content')
    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Supplier Reports <i class="mr-2"></i><small>vendor details</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.supplier.reports.index') }}" class="btn btn-sm btn-light-success">
                    <i class="fa fa-plus"></i> Supplier index
                </a>
            </div>
        </div>
        <div class="card-body">

            <div class="area-chart-wp">
                <h4>Supplier Info</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered table-condensed">
                        <tbody>
                        <tr>
                            <td>Image</td>
                            <td>
                                <img width="200" height="150" src="{{ asset($supplier->image) }}" alt="">
                            </td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td>{{ $supplier->name }}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>{{ $supplier->email }}</td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>{{ $supplier->mobile }}</td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td>{{ $supplier->type }}</td>
                        </tr>
                            <tr>
                                <td>Company</td>
                                <td>{{ $supplier->vendor->name }}</td>
                            </tr>
                        <tr>
                            <td>Address</td>
                            <td>{{ $supplier->address }}</td>
                        </tr>
                        <tr>
                            <td>Details</td>
                            <td>{{ $supplier->details }}</td>
                        </tr>

                        <tr>
                            <td>Website</td>
                            <td>{{ $supplier->website }}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            @if (isset($supplier->supplierPurchases) && count($supplier->supplierPurchases) > 0)
                <div class="area-chart-wp mg-t-30">
                    <h5>Purchecs Info</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-condensed" id="productTable">
                            <thead>
                            <tr>
                                <th class="text-center">SI</th>
                                <th class="text-center">Invoice No</th>
                                <th class="text-center">Date</th>
                                {{--    <th class="text-center">Supplier Name</th>--}}
                                <th class="text-center">Total Items</th>
                                <th class="text-center">Total Amount</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Pay Status</th>
                                <th class="text-center" width="200px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($supplier->supplierPurchases as $purchas)
                                <tr class="text-center">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>#{{ $purchas->invoice_no }}</td>
                                    <td>{{ $purchas->date }}</td>
                                    {{-- <td>{{ $purchas->purchaseSupplier->name }}</td>--}}
                                    <td>{{ $purchas->purchaseDetail->count() }}</td>
                                    <td>{{ $purchas->purchase_total_amount }}</td>
                                    <td>
                                        <span class="badge"
                                              style="{{ $purchas->status == 1 ? 'background: green;' : 'background:danger' }}">{{ $purchas->status == 1 ? 'Submitted' : 'Not Submit' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $status = null;
                                        @endphp
                                        @foreach($purchas->purchasePayment as $getStatus)
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
                                        <a href="{{ route('admin.purchase.show', $purchas->id) }}"
                                           class="btn btn-sm btn-info waves-effect" data-toggle="tooltip"
                                           data-placement="auto" title="" data-original-title="VIEW"><i
                                                class="fa fa-eye"></i> </a>
                                        @if($purchas->status == 1)
                                            <a href="{{ route('admin.purchase.add.payment', $purchas->id) }}"
                                               class="btn btn-sm btn-info waves-effect" data-toggle="tooltip"
                                               data-placement="auto" title="" data-original-title="Add Payment">Pay</a>
                                        @else
                                            <a href="{{ route('admin.purchase.stock', $purchas->id) }}"
                                               class="btn btn-sm btn-{{ $purchas->status == 1 ? 'success' : 'danger' }}   waves-effect"
                                               data-toggle="tooltip" data-placement="auto" title=""
                                               data-original-title="{{ $purchas->status == 1 ? 'Alrady Stocked' : 'Want to Stock' }}"><i
                                                    class="fa fa-check-circle"></i> </a>
                                        @endif
                                        @if($purchas->status !== 1)
                                            <a href="{{ route('admin.purchase.edit', $purchas->id) }}"
                                               class="btn btn-sm btn-warning waves-effect btn-icon" data-toggle="tooltip"
                                               data-placement="auto" title="" data-original-title="EDIT"><i
                                                    class="fas fa-pencil-alt"></i> </a>
                                            <form method="POST"
                                                  action="{{ route('admin.purchase.destroy', $purchas->id) }}"
                                                  accept-charset="UTF-8" class="btn-group">
                                                @csrf
                                                <input name="_method" type="hidden" value="DELETE">
                                                <button type="submit" class="btn btn-sm btn-danger waves-effect btn-icon"
                                                        onclick="return confirm('Are you sure to Delete this Recode ?');">
                                                    <i class="fa fa-trash"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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

@endsection



@push('script')

@endpush
