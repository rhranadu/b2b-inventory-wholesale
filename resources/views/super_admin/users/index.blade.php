@extends('layouts.app')

@section('title', 'Dashboard')

@push('css')
@endpush



@section('main_content')

    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header {{ Session('breadcomb_container') }}">
            <div class="card-title">
                <h3 class="card-label">User Data Table <i
                        class="mr-2"></i><small> Create a new user for your site</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="{{route('superadmin.user.vendor.create')}}" class="btn btn-sm btn-light-success"
                   data-toggle="tooltip" data-placement="left"
                   title="Create User">
                    <i class="fa fa-plus"></i> Create User
                </a>
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table id="data-table-basic" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Country</th>
                        <th>State/Division</th>
                        <th>City</th>
                        <th>Vendor Name</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <img style="width: 64px; height: 64px;" src="{{ asset($user->image) }}" alt="">
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->country->name?? 'NA' }}</td>
                            <td>{{ $user->stateOrDivision->name?? 'NA' }}</td>
                            <td>{{ $user->city->name?? 'NA' }}</td>
                            <td>{{ $user->vendor->name ?? 'N/A' }}</td>
                            <td>
                                @if($user->status === 1)
                                    <a href="#0" style="background: #0B792F" class="badge badge-success">Active</a>
                                @else
                                    <a href="#0" style="background:#d43f3a" class="badge badge-danger">Unactive</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <!-- Data Table area End-->

@endsection

@push('script')

@endpush
