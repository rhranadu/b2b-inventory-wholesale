@extends('layouts.crud-master')
@section('title', 'Country Edit')
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
                            <form method="POST" action="{{ route('superadmin.country.update', $country->id) }}"
                                  accept-charset="UTF-8" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Name <span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="name" value="{{ $country->name }}" autocomplete="off"
                                           name="name" type="text">
                                    @error('name')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <button type="submit" style="background: #00c292; color: #f0f0f0" class="btn  waves-effect">
                                    Update
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
