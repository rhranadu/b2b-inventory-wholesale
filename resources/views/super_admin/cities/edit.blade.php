@extends('layouts.app')
@section('title', 'City Edit')
@section('main_content')
    <div class="card card-custom min-h-500px" id="kt_card_1">

        <div class="card-body">
            <div class="{{ Session('breadcomb_container') }}">

                @include('component.message')

                <div class="normal-table-list">
                    <div class="bsc-tbl">
                        <form method="POST" action="{{ route('superadmin.city.update', $city->id) }}"
                              accept-charset="UTF-8" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">City Name <span
                                        style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                <input class="form-control" id="name" value="{{ $city->name }}" autocomplete="off"
                                       name="name" type="text">
                                @error('name')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">State Name <span
                                        style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                <select name="state_id" class="form-control">
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option
                                            {{ $city->state_id == $state->id ? 'selected' : '' }} value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                                @error('state_id')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                            <button type="submit" style="background: #00c292; color: #f0f0f0" class="btn  waves-effect">
                                Create
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
