@extends('layouts.app')
@section('main_content')

    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header {{ Session('breadcomb_container') }}">
            <div class="card-title">
                <h3 class="card-label">View Vendor <i
                        class="mr-2"></i><small>View Vendor</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="{{route('superadmin.vendor.index')}}" class="btn btn-sm btn-light-success"
                   data-toggle="tooltip" data-placement="left"
                   title="Vendor List">
                    <i class="fa fa-list"></i> Vendor List
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="normal-table-list">
                <div class="bsc-tbl">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered table-condensed">
                            <tbody>
                            <tr>
                                <td>Name</td>
                                <td>{{ $vendor->name }}</td>
                            </tr>
                            <tr>
                                <td>Image</td>
                                <td>
                                    <img width="150" height="150"
                                         src="{{ asset($vendor->logo) }}" alt="">
                                </td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $vendor->email }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ $vendor->phone }}</td>
                            </tr>
                            <tr>
                                <td>website</td>
                                <td>{{ $vendor->website }}</td>
                            </tr>


                            <tr>
                                <td>Address</td>
                                <td>{{ $vendor->address }}</td>
                            </tr>

                            <tr>
                                <td>Created At</td>
                                <td>{{ $vendor->created_at->diffForHumans() }}</td>
                            </tr>
                            <tr>
                                <td>Updated At</td>
                                <td>{{ $vendor->updated_at->diffForHumans() }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
