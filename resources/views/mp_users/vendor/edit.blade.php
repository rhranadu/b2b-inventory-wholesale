@extends('layouts.app')

@section('title', 'Retail User Edit')

@push('css')

    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap-select/bootstrap-select.css') }}">
    <!-- datapicker CSS
       ============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/datapicker/datepicker3.css') }}">
@endpush

@section('main_content')


    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    @include('component.message')
                    <form
                        action="{{ route('admin.add.mp_user.update', $user->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-element-list">
                                    <div class="row">

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <input
                                                        type="text" name="name" value="{{ $user->name }}"
                                                        class="form-control" placeholder="Full Name">
                                                    @error('name')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-mail"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <input
                                                        type="email" name="email" value="{{ $user->email }}"
                                                        class="form-control" placeholder="Email Address" pattern="{{config('constants.EMAIL_PATTERN')}}" required>
                                                    @error('email')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-phone"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <input
                                                        type="text" name="mobile" value="{{ $user->mobile }}"
                                                        class="form-control" placeholder="Contact Number" minlength="{{config('constants.MOBILE_DIGIT_LIMIT')}}" maxlength="{{config('constants.MOBILE_DIGIT_LIMIT')}}" pattern="{{config('constants.MOBILE_DIGIT_PATTERN')}}" required>
                                                    @error('mobile')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-map"></i>
                                                </div>
                                                <div class="bootstrap-select ic-cmp-int">
                                                    <select name="gender" class="selectpicker" data-live-search="true">
                                                        <option
                                                            {{ $user->gender == 'male' ? 'selected' : '' }}  value="male">
                                                            Male
                                                        </option>
                                                        <option
                                                            {{ $user->gender == 'female' ? 'selected' : '' }} value="female">
                                                            Female
                                                        </option>
                                                    </select>
                                                    @error('gender')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-ip-locator"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <input
                                                        type="text" name="details" value="{{ $user->details }}"
                                                        class="form-control" placeholder="Details">
                                                    @error('details')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group nk-datapk-ctm form-elet-mg">
                                                <div class="input-group-addon">
                                                    <input type="file" name="img" id="img" class="form-control" placeholder="">
                                                    <p id="error1" style="display:none; color:#FF0000;">
                                                        Invalid Image Format! Image Format Must Be JPG, JPEG, PNG, SVG or GIF.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-ip-locator"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <div class="pop_img" data-img="{{ asset($user->image) }}">
                                                        <img width="150"
                                                             src="{{ asset($user->image) }}"
                                                             alt="image">
                                                    </div>
                                                    @error('image')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <div class="checkbox-inline">
                                                    <label class="checkbox checkbox-outline checkbox-success">
                                                        <input
                                                            value="1" {{ $user->status == 1 ? 'checked' : '' }} type="checkbox"
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                            <button type="submit" class="btn btn-success notika-btn-success waves-effect">Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')

    <script src="{{ asset('backend/js/datapicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('backend/js/datapicker/datepicker-active.js') }}"></script>
    <!-- bootstrap select JS
        ============================================ -->
    <script src="{{ asset('backend/js/bootstrap-select/bootstrap-select.js') }}"></script>

@endpush


