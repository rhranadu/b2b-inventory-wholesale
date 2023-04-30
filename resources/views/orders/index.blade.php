@extends('layouts.crud-master')
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
                                        <h2>Product Sell</h2>
                                        <p>List of product sell</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
                                <div class="breadcomb-report">
                                    <a href="{{ route('admin.order.create') }}" class="btn" data-toggle="tooltip" data-placement="left" title="Add New"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcomb area End-->

    <!-- Normal Table area Start-->
    <div class="normal-table-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-condensed">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SI</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Product Name</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Discount</th>
                                        <th class="text-center">Service charge</th>
                                        <th class="text-center">Paid Amount</th>
                                        <th class="text-center">Due Amount</th>
                                        <th class="text-center">Payment Status</th>
                                        <th class="text-center">Sell Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td>#</td>
                                            <td>aa</td>
                                            <td>bb</td>
                                            <td>cc</td>
                                            <td>dd</td>
                                            <td>ee</td>
                                            <td>ff</td>
                                            <td>gg</td>
                                            <td><span class="badge" style="background:red;">Unpaid</span></td>
                                            <td><span class="badge" style="background:#00c292;">complited</span></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-info waves-effect" data-toggle="tooltip" data-placement="auto" title="" data-original-title="VIEW"><i class="fa fa-eye"></i> </a>
                                                <a href="#" class="btn btn-sm btn-warning waves-effect btn-icon" data-toggle="tooltip" data-placement="auto" title="" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i> </a>
                                                <form method="POST" action="#" accept-charset="UTF-8" class="btn-group">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="submit" class="btn btn-sm btn-danger waves-effect btn-icon" data-toggle="tooltip" data-placement="auto" title="" onclick="return confirm('Are you sure to Delete this Recode ?');" data-original-title="DELETE"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
