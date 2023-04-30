@extends('layouts.app')

@section('title', 'Dashboard')

@push('css')

@endpush



@section('main_content')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Create POS Customer</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-muted font-weight-bold mr-4">Create a POS Customer for your Vendor</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <!--begin::Actions-->
            <a href="{{route('admin.poscustomer.create')}}" class="btn btn-sm btn-light-success"
                data-toggle="tooltip" data-placement="left"
                title="POS Customer List">
                <i class="fa fa-plus"></i> Create POS Customer
            </a>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar-->
    </div>
</div>
<!--end::Subheader-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">

        <div class="card card-custom min-h-500px" id="kt_card_1">
            <div class="card-body">

                @include('component.message')

                <div class="normal-table-list">
                    <div class="bsc-tbl">
                        <form method="POST" action="{{ route('admin.poscustomer.store') }}" accept-charset="UTF-8"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input class="form-control" id="name" value="{{ old('name') }}"
                                            autocomplete="off" name="name" type="text">
                                        @error('name')
                                        <strong class="text-danger" role="alert">
                                            <span>{{ $message }}</span>
                                        </strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input class="form-control" id="email" value="{{ old('email') }}"
                                            autocomplete="off" name="email" type="email">
                                        @error('email')
                                        <strong class="text-danger" role="alert">
                                            <span>{{ $message }}</span>
                                        </strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input class="form-control" id="phone" value="{{ old('phone') }}"
                                            autocomplete="off" name="phone" type="text">
                                        @error('phone')
                                        <strong class="text-danger" role="alert">
                                            <span>{{ $message }}</span>
                                        </strong>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea name="address" class="form-control" id="address"></textarea>
                                        @error('address')
                                        <strong class="text-danger" role="alert">
                                            <span>{{ $message }}</span>
                                        </strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4 align-self-center">
                                    <div class="form-group">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-outline checkbox-success">
                                                <input value="1" type="checkbox" id="addedItemCheckbox" name="status"
                                                    class="i-checks">
                                                <span></span>
                                                Status
                                            </label>
                                        </div>
                                        @error('status')
                                        <strong class="text-danger" role="alert">
                                            <span>{{ $message }}</span>
                                        </strong>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    @if(auth()->user()->user_type_id == 1)
                                    <div class="form-group">
                                        <label for="vendor_id">Vendor</label>
                                        <select class="form-control" id="vendor_id" name="vendor_id">
                                            <option value="">-- Please Select --</option>
                                            @foreach($vendors as $vendor)
                                            <option value="{{$vendor->id}}"
                                                {{ old("vendor_id") == $vendor->id ? "selected" : "" }}>
                                                {{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('vendor_id')
                                        <strong class="text-danger" role="alert">
                                            <span>{{ $message }}</span>
                                        </strong>
                                        @enderror
                                    </div>
                                    @else
                                    <input type="hidden" id="vendor_id" value="{{ auth()->user()->vendor_id }}"
                                        name="vendor_id">
                                    @endif
                                </div>
                            </div>
                            <button type="submit" style="background: #00c292; color: #f0f0f0"
                                class="btn  waves-effect">Save
                                Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
