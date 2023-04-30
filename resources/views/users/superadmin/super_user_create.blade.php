@extends('layouts.app')
@section('title', 'User Registration')

@push('css')
    <style>
        input[type=file]{
            /*width:90px;*/
            /*color:transparent;*/
        }
    </style>
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap-select/bootstrap-select.css') }}">
    <!-- datapicker CSS
           ============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/datapicker/datepicker3.css') }}">

    {{--    FilePond Added--}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond/css/filepond.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond/css/filepond-plugin-image-preview.css') }}">
    <style>
        .filepond--item {
            width: calc(20% - .2em);
        }
        /*.filepond--root {*/
        /*    max-height: 10em;*/
        /*}*/
    </style>
@endpush
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

                        @php
                        $countries = App\Country::all();
                        $states = App\State::all();
                        $cities = App\City::all();
                        $roles = App\UserRole::where('user_type_id',1)->get();
                        @endphp

                        <form action="{{ route('superadmin.user.super.store') }}" method="post" enctype="multipart/form-data">
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
                                                        <input type="text" name="name" value="{{ old('name') }}"
                                                            class="form-control" placeholder="* Full Name" required>
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
                                                        <input type="email" name="email" value="{{ old('email') }}"
                                                            class="form-control" placeholder="* Email Address" pattern="{{config('constants.EMAIL_PATTERN')}}" required>
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
                                                        <input type="text" name="mobile" value="{{ old('mobile') }}"
                                                            class="form-control" placeholder="* Contact Number" minlength="{{config('constants.MOBILE_DIGIT_LIMIT')}}" maxlength="{{config('constants.MOBILE_DIGIT_LIMIT')}}" pattern="{{config('constants.MOBILE_DIGIT_PATTERN')}}" required>
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
                                                        <i class="notika-icon notika-phone"></i>
                                                    </div>
                                                    <div class="nk-int-st">
                                                        <select name="gender" class="form-control">
                                                            <option value="male">Male</option>
                                                            <option value="female">Female</option>
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
                                                        <i class="notika-icon notika-next"></i>
                                                    </div>
                                                    <div class="nk-int-st">
                                                        <input type="password" name="password"
                                                            value="{{ old('password') }}" class="form-control"
                                                            placeholder="* Enter Password" required>
                                                        @error('password')
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
                                                        <i class="notika-icon notika-next"></i>
                                                    </div>
                                                    <div class="nk-int-st">
                                                        <input type="text" name="post_code"
                                                            value="{{ old('post_code') }}" class="form-control"
                                                            placeholder="Postal Code">
                                                        @error('post_code')
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
                                                        <i class="notika-icon notika-next"></i>
                                                    </div>
                                                    <div class="nk-int-st">
                                                        <select name="country_id" id="country_id" class="form-control"
                                                            data-live-search="true">
                                                            <option value="">Select Country</option>
                                                            @foreach($countries as $country)
                                                            <option value="{{ $country->id }}">{{ $country->name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        @error('country_id')
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
                                                        <i class="notika-icon notika-next"></i>
                                                    </div>
                                                    <div class="nk-int-st">
                                                        <select name="state_id" id="state_id" class="form-control"
                                                            data-live-search="true">
                                                            <option value="">Select State</option>
                                                            @foreach($states as $state)
                                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('state_id')
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
                                                        <i class="notika-icon notika-next"></i>
                                                    </div>
                                                    <div class="nk-int-st">
                                                        <select name="city_id" id="city_id" class="form-control"
                                                            data-live-search="true">
                                                            <option value="">Select City</option>
                                                            @foreach($cities as $city)
                                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('city_id')
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
                                                <div class="form-group nk-datapk-ctm form-elet-mg" id="data_2">
                                                    <div class="input-group date nk-int-st">
                                                        <span class="input-group-addon"></span>
                                                        <input type="text" name="date_of_birth" class="form-control"
                                                            value="" placeholder="Date of Birth">
                                                        @error('date_of_birth')
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
                                                        <select
                                                            name="user_role_id" class="selectpicker form-control" data-live-search="true" required>
                                                            <option value="" selected>*Select User Access Role</option>
                                                            @foreach($roles as $role)
                                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                            @endforeach
                                                        </select> <br/>
                                                        @error('user_role_id')
                                                        <strong class="text-danger" role="alert">
                                                             <span>{{ $message }}</span>
                                                        </strong>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
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
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="image">User Image <span
                                                            style="color: red; font-size: 20px;"><sup></sup></span></label>
                                                    <input type="file"
                                                           class="filepond"
                                                           name="image"
                                                           {{--                                                   name="image[]"--}}
                                                           {{--                                                   multiple--}}
                                                           data-allow-reorder="true"
                                                           data-max-file-size="2MB"
                                                           data-max-files="10"
                                                           id="filepondImage">
                                                    {{--                                        @error('image')--}}
                                                    {{--                                        <strong class="text-danger" role="alert">--}}
                                                    {{--                                            <span>{{ $message }}</span>--}}
                                                    {{--                                        </strong>--}}
                                                    {{--                                        @enderror--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                <button type="submit"
                                    class="btn btn-success notika-btn-success waves-effect">Submit</button>
                            </div>
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

<script src="{{ asset('backend/js/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('backend/js/datapicker/datepicker-active.js') }}"></script>
<!-- bootstrap select JS
		============================================ -->
<script src="{{ asset('backend/js/bootstrap-select/bootstrap-select.js') }}"></script>
<!-- icheck JS
    ============================================ -->
{{-- <script src="{{ asset('backend/js/icheck/icheck.min.js') }}"></script>--}}
{{-- <script src="{{ asset('backend/js/icheck/icheck-active.js') }}"></script>--}}

<!-- include FilePond library -->
<script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-image-preview.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-file-validate-type.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-image-validate-size.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-image-exif-orientation.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-file-validate-size.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-file-encode.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond.jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/filepond_single_image_configure.js') }}"></script>
<script>
    $().ready(function () {

        // old value catch
        @if(old('country_id') > 0)
        $("#country_id").val("{!! old('country_id') !!}");
        $("#country_id").trigger('change'); // autometic run
        getdynamicValue($("#country_id"));
        @endif


        $("#country_id").change(function () {
            var country_id = $(this).val();
            if (country_id) {
                getdynamicValue(this, 'state')
            }
        });
        $("#state_id").change(function () {
            var state = $(this).val();
            if (state) {
                getdynamicValue(this, 'city')
            }
        });


        function getdynamicValue(element, type) {
            if (!$(element).val() == '') {
                var getId = $(element).val();
                $.ajax({
                    url: "{{ route('superadmin.user.dynamic.value') }}",
                    type: "get",
                    data: {
                        id: getId,
                        type: type,
                    },
                    success: function (res) {
                        if (res.state) {
                            $("#state_id").html(res.state);
                            $("#state_id").selectpicker('refresh');
                        }
                        if (res.city) {
                            $("#city_id").html(res.city);
                            $("#city_id").selectpicker('refresh');
                        }
                    }
                })
            }
        }

        filepond_single_image_configure();

        $("#country_id").selectpicker('refresh');
        $("#state_id").selectpicker('refresh');
        $("#city_id").selectpicker('refresh');
    });

</script>

@endpush
