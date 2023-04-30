@extends('layouts.app')
@section('title', 'State Details')
@section('main_content')

    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header {{ Session('breadcomb_container') }}">
            <div class="card-title">
                <h3 class="card-label">View State <i
                        class="mr-2"></i><small>View State</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="{{route('admin.state.index')}}" class="btn btn-sm btn-light-success"
                   data-toggle="tooltip" data-placement="left"
                   title="State List">
                    <i class="fa fa-list"></i> State List
                </a>
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered table-condensed">
                    <tbody>
                    <tr>
                        <td>Name</td>
                        <td>{{ $state->name }}</td>
                    </tr>
                    <tr>
                        <td>Country Name</td>
                        <td>{{ $state->country->name }}</td>
                    </tr>
                    <tr>
                        <td>Created By</td>
                        <td>{{ $state->createdBy->name }}</td>
                    </tr>
                    <tr>
                        <td>Updated By</td>
                        <td>{{ $state->updatedBy->name }}</td>
                    </tr>
                    <tr>
                        <td>Created At</td>
                        <td>{{ $state->created_at->diffForHumans() }}</td>
                    </tr>
                    <tr>
                        <td>Updated At</td>
                        <td>{{ $state->updated_at->diffForHumans() }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
