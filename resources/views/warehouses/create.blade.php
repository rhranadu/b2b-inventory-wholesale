@extends('layouts.crud-master')
@section('title', 'Warehouse Create')
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
                                method="POST" action="{{ route('admin.warehouse.store') }}" accept-charset="UTF-8"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-9">
                                        <label for="name">Name
                                            <span
                                                style="color: red; font-size: 20px;">
                                                <sup>*</sup>
                                            </span>
                                        </label>
                                        <input
                                            class="form-control" id="name" value="{{ old('name') }}" autocomplete="off"
                                            name="name" type="text">
                                        @error('name')
                                        <strong class="text-danger" role="alert">
                                            <span>{{ $message }}</span>
                                        </strong>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="type">Type
                                            <span
                                                style="color: red; font-size: 20px;">
                                                <sup>*</sup>
                                            </span>
                                        </label>
                                        <select
                                            class="form-control" id="type" name="type" autocomplete="off"
                                            value="{{ old('type') }}">
                                            <option value="">-- Select Type --</option>
                                            <option value="godown">Godown</option>
                                            <option value="showroom">Showroom</option>
                                        </select>
                                        @error('type')
                                        <strong class="text-danger" role="alert">
                                            <span>{{ $message }}</span>
                                        </strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address
                                        <span
                                            style="color: red; font-size: 20px;">
                                            <sup></sup>
                                        </span>
                                    </label>
                                    <textarea
                                        class="form-control date" id="address" name="address" cols="50"
                                        rows="10">{{ old('address') }}</textarea>
                                    @error('address')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
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
