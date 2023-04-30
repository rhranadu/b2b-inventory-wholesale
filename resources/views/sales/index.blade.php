@extends('layouts.crud-master')
@section('title', 'Sales')
@push('css')
    .table th, .table td{vertical-align:inherit;}
@endpush
@section('main_content')
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div
            class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Product Sale</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-muted font-weight-bold mr-4">List of product Sale</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <!--begin::Actions-->
                <a
                    data-toggle="tooltip"
                    title="Add Sale"
                    href="{{route('admin.sale.create')}}"
                    class="btn btn-light-success btn-sm btn-clean font-weight-bold font-size-base mr-1">
                    <i class="fa fa-plus"></i>Add Sale
                </a>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar-->
        </div>
    </div>
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">

                <div class="card-body">

                    @include('component.message')
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <div class="table-responsive">
                                @if(!empty($sales) && count($sales) > 0)
                                    <table class="table table-hover table-bordered table-condensed">
                                        <thead>
                                        <tr>
                                            <th class="text-center">SI</th>
                                            <th class="text-center">Invoice No</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">POS Customer Name</th>
                                            <th class="text-center">Items</th>
                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">Paid Amount</th>
                                            <th class="text-center">Due Amount</th>
                                            <th class="text-center">Need To Return</th>
                                            @if(Auth::user()->user_type_id == 2)
                                                <th class="text-center">Created By</th>
                                                <th class="text-center">Updated By</th>
                                            @endif
                                            <th class="text-center">Pay Status</th>
                                            {{-- <th class="text-center">Action</th> --}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($sales as $sale)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $sale->invoice_no }}</td>
                                                <td>{{ $sale->created_at }}</td>
                                                <td>{{ $sale->posCustomer->name }}</td>
                                                <td>{{ $sale->items }}</td>
                                                <td class="text-right">{{ $sale->final_total }}</td>
                                                <td class="text-right">{{ $sale->payment->sum('pay_amount') }}</td>
                                                <td class="text-right">{{ $sale->payment->last()['due_amount'] }}</td>
                                                <td class="text-right">{{ $sale->payment->last()['give_back'] }}</td>
                                                @if(Auth::user()->user_type_id == 2)
                                                    <td>{{ $sale->createdBy->name }}</td>
                                                    <td>{{ $sale->updatedBy->name }}</td>
                                                @endif
                                                <td class="text-center">
                                                    @if(isset($sale->payment->last()->status) and $sale->payment->last()->status == 'FP')
                                                        <span class="badge badge-success"
                                                              >Full Paid</span>
                                                    @elseif(isset($sale->payment->last()->status) and $sale->payment->last()->status == 'PP')
                                                        <span
                                                        data-cid={{ $sale->posCustomer->id }}
                                                        data-cname={{ $sale->posCustomer->name }}
                                                        data-length={{ $sale->items }}
                                                        data-total={{ $sale->final_total }}
                                                        data-due={{ $sale->payment->last()->due_amount }}
                                                        data-paid={{ $sale->payment->sum('pay_amount') }}
                                                        data-advance={{ $sale->payment->last()->give_back }}
                                                        data-sid={{ $sale->id }}
                                                        class="badge badge-warning partial-paid"
                                                              >Partial Paid</span>
                                                    @else
                                                        <span
                                                        data-cid={{ $sale->posCustomer->id }}
                                                        data-cname={{ $sale->posCustomer->name }}
                                                        data-length={{ $sale->items }}
                                                        data-total={{ $sale->final_total }}
                                                        data-due={{ $sale->payment->last()['due_amount'] }}
                                                        data-paid={{ $sale->payment->last()['pay_amount'] }}
                                                        data-advance={{ $sale->payment->last()['give_back'] }}
                                                        data-sid={{ $sale->id }}
                                                        class="badge badge-danger"
                                                              >Not Paid</span>
                                                    @endif
                                                </td>
                                                {{-- <td class="text-center">
                                                    <div class="btn-group">
                                                        <a class="btn btn-sm btn-success btn-icon" data-toggle="tooltip"
                                                        data-placement="auto"
                                                        data-original-title="Payment"
                                                           href="{{ route('admin.sale.payment', $sale->id) }}"><i
                                                                class="fa fa-money-bill"></i></a>
                                                        <a class="btn btn-sm btn-primary btn-icon" data-toggle="tooltip"
                                                        data-placement="auto"
                                                        data-original-title="Details"
                                                           href="{{ route('admin.sale.detail', $sale->id) }}"><i
                                                                class="fa fa-info"></i></a>
                                                        <a class="btn btn-sm btn-secondary btn-icon" style="background-color: #ffb9bb" title="Pdf"
                                                           href="#"><i
                                                                class="fa fa-file-pdf-o"></i></a>
                                                    </div>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div
                                        class="alert alert-custom alert-outline-2x alert-outline-warning fade show mb-5">
                                        <div class="alert-icon">
                                            <i class="flaticon-warning"></i>
                                        </div>
                                        <div class="alert-text h4 mb-0">No data found</div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('sales.partial_payment_modal')
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        })

        $(document).on('click', '.partial-paid', function (e) {
            var pos_customer_name = $(this).data('cname');
            var pos_customer_id = $(this).data('cid');
            var length = $(this).data('length');
            var final_total = $(this).data('total');
            var paid = $(this).data('paid');
            var due = $(this).data('due');
            var advance = $(this).data('advance');
            var sid = $(this).data('sid');

            $(".partial_payment_modal").modal('show');

            $(".p_c_name").html(pos_customer_name + `<input type="hidden" class="p_pos_customer_id" value="` + pos_customer_id + `">`);
            $(".sale_pro_item").text("Items:" + length);
            $(".sale_total").text(final_total);
            $(".paid_amount").text(paid);
            $(".pay_input_field").val(due);
            $(".existing-due-amount").text(due);
            $(".existing-advance-amount").text(advance);
            $(".last_sale_id").val(sid);
            // $(".give_back").html(`Due: `+(parseFloat(due))+` <input type="hidden" class="back" value="`+(parseFloat(due))+`">`);
        });
    </script>
@endpush
