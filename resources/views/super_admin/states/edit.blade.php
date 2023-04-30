@extends('layouts.app')
@section('title', 'State Edit')
@section('main_content')

    <div class="card card-custom min-h-500px" id="kt_card_1">

        <div class="card-body">

            @include('component.message')

            <div class="normal-table-list">
                <div class="bsc-tbl">
                    <form method="POST" action="{{ route('superadmin.state.update', $state->id) }}"
                          accept-charset="UTF-8" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">State/Division-Name <span
                                    style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                            <input class="form-control" id="name" value="{{ $state->name }}" autocomplete="off"
                                   name="name" type="text">
                            @error('name')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Country Name <span
                                    style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                            <select name="country_id" class="form-control">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option
                                        {{ $state->country_id == $country->id ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('country_id')
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
@endsection

@push('script')

@endpush
