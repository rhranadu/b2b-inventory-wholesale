@extends('layouts.crud-master')

@section('title', 'POS Customers Reports')

@push('css')
    <style>
        .dataTables_filter, .dataTables_length {
            padding: 20px 15px;
            padding-top: 0px;
            padding-bottom: 42px;!important;
        }
    </style>
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
                                        <h2>POS Customer Reports</h2>
                                        <p> POS customer reports</p>
                                    </div>
                                </div>
                            </div>
                            {{--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
                                <div class="breadcomb-report">
                                    <a href="{{ route('admin.poscustomer.create') }}" class="btn"> <i class="fa fa-plus-circle"></i> Create</a>
                                </div>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcomb area End-->


    <!-- Data Table area Start-->
    <div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="data-table-list">

                        <div class="table-responsive">
                            <table id="data-table-basic" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Si</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Buy</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($poscustomers as $poscustomer)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $poscustomer->name }}</td>
                                        <td>{{ $poscustomer->email }}</td>
                                        <td>{{ $poscustomer->phone }}</td>
                                        <td>{{ $poscustomer->address }}</td>
                                        <td> {{ $poscustomer->posCustomerSales->count() }} </td>
                                        <td> {{ number_format($poscustomer->posCustomerSales->sum('final_total'), 2) }} </td>

                                        <td>
                                            <a href="{{ route('admin.report.poscustomer.sale.detail', $poscustomer->id) }}" class="btn btn-sm btn-info"> <i class="fa fa-info"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Table area End-->


    <div class="modals-default-cl">
        <div class="modal fade" id="myModaltwo" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form method="POST" action="{{ route('admin.poscustomer.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                        <div class="modal-body">
                            <h1 class="text-center" style="margin-bottom: 30px;">New POS Customer Create</h1>
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="form-control" id="name" value="{{ old('name') }}" autocomplete="off" name="name" type="text">
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" id="email" value="{{ old('email') }}" autocomplete="off" name="email" type="email">
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input class="form-control" id="phone" value="{{ old('phone') }}" autocomplete="off" name="phone" type="text">
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea name="address" class="form-control" id="address"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default" >Save</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('script')

@endpush
