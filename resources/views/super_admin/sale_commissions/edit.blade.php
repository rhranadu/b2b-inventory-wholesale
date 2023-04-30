@extends('layouts.crud-master')
@section('title', 'Product Tax Edit')
@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    @include('component.message')
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form method="POST" action="{{ route('admin.tax.update', $tax->id) }}" accept-charset="UTF-8"
                                    enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Tax Name<span style="color: red; font-size: 18px">
                                                    <sup>*</sup>
                                                </span></label>
                                    <input class="form-control" id="name" value="{{ $tax->name }}" autocomplete="off"
                                            name="name" type="text">
                                    @error('name')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="percentage">Percentage (%)<span style="color: red; font-size: 18px">
                                                    <sup>*</sup>
                                                </span></label>
                                    <input class="form-control" id="percentage" value="{{ $tax->percentage }}"
                                            autocomplete="off" name="percentage" type="text">
                                    @error('percentage')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input
                                                value="1" {{ $tax->status == 1 ? 'checked' : '' }} type="checkbox"
                                                id="addedItemCheckbox" name="status"
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
                                <button type="submit" style="background: #00c292; color: #f0f0f0" class="btn waves-effect">
                                    Update Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->


@endsection

@push('script')

@endpush
