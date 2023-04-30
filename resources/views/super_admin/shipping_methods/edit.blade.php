@extends('layouts.crud-master')
@section('title', 'Shipping Method Edit')
@section('main_content')

    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom min-h-500px">
                <div class="card-body">
                    @include('component.message')

                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form method="POST" action="{{ route('superadmin.shipping_methods.update', $shippingMethod->id) }}"
                                  accept-charset="UTF-8" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Name <span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                    <input class="form-control" id="name" value="{{ $shippingMethod->name }}"
                                           autocomplete="off"
                                           name="name" type="text">
                                    @error('name')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="bootstrap-select ic-cmp-int">
                                        <label>Vendor Name<span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                        <select name="vendor_id" id="vendor_id" class="selectpicker form-control" data-live-search="true">
                                            <option value="">*Select Vendor</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor->id }}" {{$shippingMethod->vendor_id == $vendor->id ? 'selected' : ''}} >{{ $vendor->name }}</option>
                                            @endforeach
                                        </select><br/>
                                        @error('vendor_id')
                                        <strong class="text-danger" >
                                            <span>{{ $message }}</span>
                                        </strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="charge">Charge</label>
                                    <input class="form-control" id="charge" value="{{ $shippingMethod->charge }}"
                                           autocomplete="off" name="charge" type="text">
                                    @error('charge')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="delivery_time">Delivery Time<span style="color: red; font-size: 18px"><sup></sup></span></label>
                                    <input class="form-control" id="delivery_time" value="{{ $shippingMethod->delivery_time }}"
                                           autocomplete="off" name="delivery_time" type="text">
                                    @error('delivery_time')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>




                                <div class="form-group">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input
                                                value="1"
                                                {{ $shippingMethod->status == 1 ? 'checked' : '' }} type="checkbox"
                                                id="addedItemCheckbox" name="status"
                                                class="i-checks">
                                            <span></span>
                                            Status
                                        </label><span style="color: red; font-size: 18px"><sup></sup></span>
                                    </div>

                                    @error('status')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <button type="submit" style="background: #00c292; color: #f0f0f0"
                                        class="btn waves-effect">
                                    Update Data
                                </button>
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
