@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Suppliers')

@push('css')

@endpush

@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px">
                <div class="card-body">

                    <!-- Data Table area Start-->
                    @include('component.message')
                        <div class="data-table-list">
                            <div class="row">
                                <div class="col">
                                <h2>
                                    Current Commission Rate <span class="badge badge-danger">{{ $vendorCommission }}%</span>
                                </h2>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="supplierDataTable" class="table table-striped supplierDataTable">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Total Sold</th>
                                        <th class="text-center">Commission (Accounts Payable )</th>
                                        <th class="text-center">Paid</th>
                                        <th class="text-center">Received by Marketplace</th>
                                        <th class="text-center notexport">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">{{ $totalSale }}</td>
                                            <th class="text-center">{{ $totalCommission }}</th>
                                            <th class="text-center">{{ $totalPaid }}</th>
                                            <th class="text-center">{{ $totalReceived }}</th>
                                            <th class="text-center notexport"><a href="/admin/vendor-commission/create" style="background: #00c292"  class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="auto" title="Submit a Payment" data-original-title="Submit a Payment"><i class="lab la-stripe-s"></i></a></th>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <!-- Data Table area End-->
                </div>
            </div>
        </div>
    </div>

@endsection