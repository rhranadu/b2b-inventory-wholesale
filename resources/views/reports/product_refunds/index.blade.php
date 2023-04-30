@extends('layouts.app')

@section('title', 'Return Products')

@push('css')

@endpush



@section('main_content')

    <!-- Breadcomb area Start-->
    <div class="breadcomb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcomb-list">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="breadcomb-wp">
                                    <div class="breadcomb-icon">
                                        <i class="notika-icon notika-windows"></i>
                                    </div>
                                    <div class="breadcomb-ctn">
                                        <h2>Return Products</h2>
                                        <p>list of return products</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcomb area End-->


    <div class="normal-table-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            @include('component.message')
                            @if (isset($returns) && count($returns) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-condensed"
                                           id="data-table-basic">
                                        <thead>
                                        <tr>
                                            <th class="text-center">SI</th>
                                            <th class="text-center">Return Date</th>
                                            <th class="text-center">Product Name</th>
                                            <th class="text-center">Attribute</th>
                                            <th class="text-center">From Warehouse</th>
                                            <th class="text-center">Send Quantity</th>
                                            <th class="text-center">Receive Quantity</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($returns as $return){{--this product come from stock_detail--}}
                                            <tr class="text-center">
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $return->created_at->isoFormat('Do MMM YY') }}</td>
                                                <td>{{ $return->product->name }}</td>
                                                <td>{{ $return->productAttribute->name }}-{{ $return->productAttributeMap->value }}</td>
                                                <td>{{ $return->warehouse->name }}</td>
                                                <td>{{ $return->return_quantity }}</td>
                                                <td>{{ $return->receive_quantity }}</td>
                                                <td>
                                                    @if($return->status == 'full_receive')
                                                        <span class="badge" style="background-color: green">Full Receive</span>
                                                    @elseif($return->status == 'partial_receive')
                                                        <span class="badge" style="background-color: orange">Partial Receive</span>
                                                    @else
                                                        <span class="badge">Submitted</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center">No data found</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection



@push('script')

@endpush
