@extends('layouts.app')

@section('title', 'Supplier Reports')

@push('css')

@endpush



@section('main_content')
    <div class="normal-table-area">
        <div class="normal-table-list">
            <div class="bsc-tbl">
                @include('component.message')
                @if (isset($suppliers) && count($suppliers) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-condensed" id="data-table-basic">
                            <thead>
                            <tr>
                                <th class="text-center">SI</th>
                                <th class="text-center">Image</th>
                                <th class="text-center" width="150">Name</th>
                                <th class="text-center" width="100">Email</th>
                                <th class="text-center">Bank Acount</th>
                                <th class="text-center">Purchases</th>
                                <th class="text-center">Purchases Count</th>
                                <th class="text-center">Total Amount</th>
                                <th class="text-center">Total Pay</th>
                                <th class="text-center">Total Due</th>
                                <th class="text-center">Last Payment Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($suppliers as $supplier)
                                <tr class="text-center">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <img width="50" height="50" src="{{ asset($supplier->image) }}" alt="">
                                    </td>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->email }}</td>
                                    <td>
                                        @if(isset($supplier->bankAccount))
                                            <span class="badge" style="background: #00c292">Yes</span>
                                        @else
                                            <span class="badge" style="background: red">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($supplier->supplierPurchases))
                                            @if($supplier->supplierPurchases->count() > 0)
                                                <span class="badge" style="background: #00c292">Yes</span>
                                            @else
                                                <span class="badge" style="background: red">No</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($supplier->supplierPurchases))
                                            @if($supplier->supplierPurchases->count() > 0)
                                                <span class="badge"
                                                      style="background: #00c292">{{ $supplier->supplierPurchases->count() }}</span>
                                            @else
                                                <span class="badge" style="background: red">0</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($supplier->supplierPurchases))
                                            @if($supplier->supplierPurchases->sum('purchase_total_amount') > 0)
                                                <span class="badge"
                                                      style="background: #00c292">{{ $supplier->supplierPurchases->sum('purchase_total_amount') }}</span>
                                            @else
                                                <span class="badge" style="background: red">0</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($supplier->vendorPaymentTranstion))
                                            @if($supplier->vendorPaymentTranstion->count() > 0)
                                                <span class="badge"
                                                      style="background: #00c292">{{ $supplier->vendorPaymentTranstion->sum('pay_amount') }}</span>
                                            @else
                                                <span class="badge" style="background: red">No Transction</span>
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        @if(isset($supplier->vendorPaymentTranstion ))
                                            @if($supplier->vendorPaymentTranstion->sum('pay_amount') > 0)
                                                @if( ($supplier->supplierPurchases->sum('purchase_total_amount') - $supplier->vendorPaymentTranstion->sum('pay_amount')) > 0)
                                                    <span class="badge"
                                                          style="background: orange">{{ $supplier->supplierPurchases->sum('purchase_total_amount') - $supplier->vendorPaymentTranstion->sum('pay_amount') }}</span>
                                                @else
                                                    <span class="badge" style="background: green">Success</span>
                                                @endif
                                            @else
                                                <span class="badge" style="background: red">Full Due</span>
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        @if(isset($supplier->vendorPaymentTranstion))
                                            @if($supplier->vendorPaymentTranstion->pluck('created_at')->last())
                                                <span class="badge"
                                                      style="background: black">{{ $supplier->vendorPaymentTranstion->pluck('created_at')->last()->isoFormat('MMM Do YY') }}</span>
                                            @else
                                                <span class="badge" style="background: red">N/A</span>
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.get.supplier.report.indetails', $supplier->id) }}"
                                           class="btn btn-primary btn-xs">InDetails</a>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{--                                {!! $suppliers->links() !!}--}}
                @else
                    <div class="alert alert-info text-center">No data found</div>
                @endif
            </div>
        </div>
    </div>


@endsection



@push('script')

@endpush
