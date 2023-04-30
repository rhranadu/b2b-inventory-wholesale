@extends('layouts.app')
@section('title', 'User Registration')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap-select/bootstrap-select.css') }}">
    <!-- datapicker CSS
       ============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/datapicker/datepicker3.css') }}">
@endpush
@section('main_content')
<!-- Form Element area Start-->
<div class="form-element-area">
    <div class="{{ Session('breadcomb_container') }}">

       @include('component.message')

        @php
            $countries = App\Country::all();
            $states = App\State::all();
            $cities = App\City::all();
            $user_types = App\UserType::all();
            $vendors = App\Vendor::all();
        @endphp

        <form action="{{ route('register') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-element-list">
                        <div class="cmp-tb-hd bcs-hd">
                            <h1>Create New User</h1>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-support"></i>
                                    </div>
                                    <div class="nk-int-st">
                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Full Name">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
                                        <input type="text" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email Address">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
                                        <input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control" placeholder="Contact Number">
                                        @error('mobile')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
                                            <option  value="male">Male</option>
                                            <option  value="female">Female</option>
                                        </select>
                                        @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
                                        <input type="password" name="password" value="{{ old('password') }}" class="form-control" placeholder="Enter Password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
                                        <input type="text" name="post_code" value="{{ old('post_code') }}" class="form-control" placeholder="Postal Code">
                                        @error('post_code')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
                                        <select name="country_id" id="country_id" class="form-control" data-live-search="true">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
                                        <select name="state_id" id="state_id"  class="form-control"  data-live-search="true">
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('state_id')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
                                        <select name="city_id" id="city_id" class="form-control" data-live-search="true">
                                            <option value="">Select City</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('city_id')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
                                        <select name="vendor_id" class="form-control" data-live-search="true">
                                            <option value="">Select Vendor </option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('vendor_id')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group nk-datapk-ctm form-elet-mg" id="data_2">
                                    <div class="input-group date nk-int-st">
                                        <span class="input-group-addon"></span>
                                        <input type="text" name="date_of_birth" class="form-control" value="" placeholder="Date of Birth">
                                        @error('date_of_birth')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group nk-datapk-ctm form-elet-mg">
                                    <div class="input-group-addon">
                                        <input type="file" name="img" class="form-control">
                                    </div>
                                    @error('img')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            @if(Session::get('template_name') == 'AdminLTE')
                                <ul class="todo-list" data-widget="todo-list">
                                    <li>
                                        <div  class="icheck-primary d-inline ml-2">
                                            <input type="checkbox" value="1" name="status" id="todoCheck3">
                                            <label for="todoCheck3"></label>
                                        </div>
                                        <span class="text">Status</span>
                                    </li>
                                </ul>
                                @elseif(Session::get('template_name') == 'Gentelella')
                                    <ul class="to_do">
                                        <li>
                                            <p><input type="checkbox" value="1" name="status" class="flat"> Active</p>
                                        </li>
                                    </ul>
                                @else
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group nk-datapk-ctm form-elet-mg">
                                        <label></label>
                                        <div class="input-group ">
                                            <div class="fm-checkbox">
                                                <label><input type="checkbox" class="i-checks" name="status" id="addedItemCheckbox"  value="1"> <i></i>Status</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                <button type="submit" class="btn btn-success notika-btn-success waves-effect">Submit</button>
            </div>

        </form>

    </div>
</div>


@endsection

@push('script')

    <script src="{{ asset('backend/js/datapicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('backend/js/datapicker/datepicker-active.js') }}"></script>
    <!-- bootstrap select JS
		============================================ -->
    <script src="{{ asset('backend/js/bootstrap-select/bootstrap-select.js') }}"></script>
    <!-- icheck JS
    ============================================ -->
{{--    <script src="{{ asset('backend/js/icheck/icheck.min.js') }}"></script>--}}
{{--    <script src="{{ asset('backend/js/icheck/icheck-active.js') }}"></script>--}}
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
        });
    </script>

@endpush


