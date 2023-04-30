@extends('layouts.app')
@section('title', 'City Details')
@section('main_content')
    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header {{ Session('breadcomb_container') }}">
            <div class="card-title">
                <h3 class="card-label">View City <i
                        class="mr-2"></i><small>View City</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="{{route('superadmin.city.index')}}" class="btn btn-sm btn-light-success"
                   data-toggle="tooltip" data-placement="left"
                   title="City List">
                    <i class="fa fa-list"></i> City List
                </a>
            </div>
        </div>
        <div class="card-body">

            <div class="{{ Session('breadcomb_container') }}">
                <div class="normal-table-list">
                    <div class="bsc-tbl">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered table-condensed">
                                <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td>{{ $city->name }}</td>
                                </tr>
                                <tr>
                                    <td>State Name</td>
                                    <td>{{ $city->state->name }}</td>
                                </tr>
                                <tr>
                                    <td>Created By</td>
                                    <td>{{ $city->createdBy->name }}</td>
                                </tr>
                                <tr>
                                    <td>Updated By</td>
                                    <td>{{ $city->updatedBy->name }}</td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>{{ $city->created_at->diffForHumans() }}</td>
                                </tr>
                                <tr>
                                    <td>Updated At</td>
                                    <td>{{ $city->updated_at->diffForHumans() }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
