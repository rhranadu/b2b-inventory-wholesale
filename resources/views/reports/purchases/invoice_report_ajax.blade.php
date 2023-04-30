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
            @if(!empty($purchases) && count($purchases) > 0)
            <table class="table table-hover table-bordered table-condensed" id="purchase-report-table">
                <thead>
                <tr>
                    <th class="text-center">SI</th>
                    <th class="text-center" width="100">Date</th>
                    <th class="text-center">Invoice No</th>
                    <th class="text-center" width="200">Supplier Name</th>
                    <th class="text-center" width="50">Total Item</th>
                    @if(Auth::user()->user_type_id == 2)
                        <th class="text-center">Created By</th>
                        <th class="text-center">Updated By</th>
                    @endif
                    <th class="text-center" width="200">Status</th>
{{--                    <th class="text-center" width="200px">Action</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($purchases as $purchase)
                    <tr class="text-center">
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $purchase->date }}</td>
                        <td>#{{ $purchase->invoice_no }}</td>
                        <td>{{ $purchase->purchaseSupplier->name }}</td>
                        <td>{{ $purchase->purchaseDetail->count() }}</td>
                        @if(Auth::user()->user_type_id == 2)
                            <td>{{ $purchase->createdBy->name }}</td>
                            <td>{{ $purchase->updatedBy->name }}</td>
                        @endif
                        <td>
                            @php
                                $status = \App\Purchase::getPurchasesFRStatus($purchase);
                            @endphp
                            @if ($status == 'draft')
                                <span class="badge badge-danger" >Draft</span>
                            @elseif ($status == 'posted')
                                <span class="badge badge-primary">Posted</span>
                            @elseif ($status == 'FR')
                                <span class="badge badge-success" >Full Recived</span>
                            @elseif ($status == 'NY')
                                <span class="badge badge-danger" >Not Yet</span>
                            @else
                                <span class="badge badge-warning" >Partial Recived</span>
                            @endif
                        </td>
{{--                        <td>--}}
{{--                            <div class="btn-group">--}}
{{--                                <a href="{{ route('admin.purchase.show', $purchase->id) }}"--}}
{{--                                data-id = "{{ route('admin.purchase.show', $purchase->id) }}"--}}
{{--                                class="btn btn-sm btn-info waves-effect" id="invoice-detail-btn" data-toggle="tooltip"--}}
{{--                                data-placement="auto" title="" data-original-title="VIEW"><i--}}
{{--                                        class="fa fa-eye"></i> </a>--}}
{{--                            </div>--}}
{{--                        </td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>
{{--            {!! $purchases->links() !!}--}}
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
        $('#purchase-report-table').DataTable();
    } );
</script>
@endpush
