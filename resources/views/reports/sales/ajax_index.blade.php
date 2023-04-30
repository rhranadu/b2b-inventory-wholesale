@extends('layouts.ajax')
@push('css')
{{-- .table th, .table td{vertical-align:inherit;} --}}
<style>
    input[type="date"]::-webkit-datetime-edit,
    input[type="date"]::-webkit-inner-spin-button,
    input[type="date"]::-webkit-clear-button {
        color: #fff;
        position: relative;
    }

    input[type="date"]::-webkit-datetime-edit-year-field {
        position: absolute !important;
        border-left: 1px solid #8c8c8c;
        padding: 2px;
        color: #000;
        left: 56px;
    }

    input[type="date"]::-webkit-datetime-edit-month-field {
        position: absolute !important;
        border-left: 1px solid #8c8c8c;
        padding: 2px;
        color: #000;
        left: 26px;
    }


    input[type="date"]::-webkit-datetime-edit-day-field {
        position: absolute !important;
        color: #000;
        padding: 2px;
        left: 4px;

    }

</style>
@endpush


@section('main_content')

@include('component.message')
<div class="normal-table-list">
    <div class="bsc-tbl">
        <div class="table-responsive">
            @if(!empty($sales) && count($sales) > 0)
            <table class="table table-hover table-bordered table-condensed" id="sale-report-table">
                <thead>
                    <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">Sale Warehouse</th>
                        <th class="text-center">POS Customer</th>
                        <th class="text-center">Invoice No</th>
                        <th class="text-center">Sub Total</th>
                        <th class="text-center">Tax</th>
                        <th class="text-center">Shipping Charge</th>
                        <th class="text-center">Discount</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Pay Amount</th>
                        <th class="text-center">Due Amount</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr>
                        <td class="text-center">{{ $sale->created_at->isoFormat('Do MMM YY')  }}</td>
                        <td class="text-center">{{ $sale->saleWarehouse->name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $sale->posCustomer->name }}</td>
                        <td class="text-center">{{ $sale->invoice_no }}</td>
                        <td class="text-center">{{ $sale->sub_total }}</td>
                        <td class="text-center">{{ $sale->tax }}</td>
                        <td class="text-center">{{ $sale->shipping_charge }}</td>
                        <td class="text-center">{{ $sale->discount }}</td>
                        <td class="text-center">{{ number_format($sale->final_total, 2) }}</td>
                        <td class="text-center">{{ $sale->payment->sum('pay_amount')  }}</td>
                        <td class="text-center">{{ $sale->payment->sum('due_amount')  }}</td>
                        <td class="text-center">
                            @if(isset($sale->payment->last()->status) and $sale->payment->last()->status == 'PP')
                            <span class="badge badge-warning">Partial Paid</span>
                            @elseif(isset($sale->payment->last()->status) and $sale->payment->last()->status == 'FP')
                            <span class="badge badge-success">Full Paid</span>
                            @else
                            <span class="badge badge-danger">Full Due</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
{{--            {!! $sales->links() !!}--}}
            @else
            <div class="alert alert-custom alert-outline-2x alert-outline-warning fade show mb-5">
                <div class="alert-icon">
                    <i class="flaticon-warning"></i>
                </div>
                <div class="alert-text h4 mb-0">No data found</div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
@push('script')
    <script>
        $(document).ready( function () {
            $('#sale-report-table').DataTable();
        } );
    </script>
@endpush
