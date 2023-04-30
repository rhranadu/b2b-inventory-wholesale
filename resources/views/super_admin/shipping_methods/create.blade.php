@extends('layouts.crud-master')
@section('title', 'Shipping Method Create')
@section('main_content')


    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px">
                <div class="card-body">

                    @include('component.message')

                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form
                                method="POST" action="{{ route('superadmin.shipping_methods.store') }}" accept-charset="UTF-8"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="form-row align-items-end">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Name<span style="color: red; font-size: 18px"><sup>*</sup></span>
                                            </label>
                                            <input
                                                class="form-control" id="name" value="{{ old('name') }}"
                                                autocomplete="off"
                                                name="name" type="text">
                                            @error('name')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="bootstrap-select ic-cmp-int">
                                                <label>Vendor Name<span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                                <select name="vendor_id" id="vendor_id" class="selectpicker form-control" data-live-search="true">
                                                    <option value="">*Select Vendor</option>
                                                    @foreach($vendors as $vendor)
                                                        <option value="{{ $vendor->id }}" >{{ $vendor->name }}</option>
                                                    @endforeach
                                                </select><br/>
                                                @error('vendor_id')
                                                <strong class="text-danger" >
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row align-items-end">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="charge">Charge  <span style="color: red; font-size: 18px"><sup>*</sup></span>
                                              </label>
                                            <input
                                                class="form-control" id="charge" value="{{ old('charge') }}"
                                                autocomplete="off"
                                                name="charge" type="number" min="0" >
                                            @error('charge')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="delivery_time">Delivery Time</label>
                                            <input
                                                class="form-control" id="delivery_time" value="{{ old('delivery_time') }}"
                                                autocomplete="off"
                                                name="delivery_time" type="text">
                                            @error('delivery_time')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <div class="form-row align-items-end">
                                    <div class="col">
                                        <div class="form-group">
                                            <div class="checkbox-inline">
                                                <label class="checkbox checkbox-outline checkbox-success">
                                                    <input
                                                        value="1" type="checkbox" id="addedItemCheckbox" name="status"
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
                                <button type="submit" class="btn btn-success waves-effect">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')


@endpush
