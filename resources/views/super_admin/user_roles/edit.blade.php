@extends('layouts.app')
@section('title', 'User Role Edit')
@section('main_content')
    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-body">
            <div class="{{ Session('breadcomb_container') }}">

                @include('component.message')

                <div class="normal-table-list">
                    <div class="bsc-tbl">
                        <form method="POST" action="{{ route('superadmin.user_role.update', $userRole->id) }}"
                              accept-charset="UTF-8" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Name<span
                                        style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                <input class="form-control" id="name" value="{{ $userRole->name }}" autocomplete="off"
                                       name="name" type="text">
                                @error('name')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control date" id="description"
                                          name="description">{{ $userRole->description }}</textarea>
                                @error('description')
                                <strong class="text-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </strong>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="user_type_id">User Type<span
                                        style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                <select name="user_type_id" class="form-control">
                                    <option value="">Select Type</option>
                                    @foreach($user_types as $user_type)
                                        <option
                                             value="{{ $user_type->id }}" {{ $user_type->id == $userRole->user_type_id ? 'selected' : '' }}>{{ $user_type->table_name }}</option>
                                    @endforeach
                                </select>
                                @error('user_type_id')
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
@endsection

@push('script')

@endpush
