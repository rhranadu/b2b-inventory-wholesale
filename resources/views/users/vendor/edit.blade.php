@extends('layouts.app')

@section('title', 'User Edit')

@push('css')

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
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    @include('component.message')
                    <form
                        action="{{ route('admin.add.user.update', $user->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-element-list">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group ic-cmp-int">
                                        <div class="form-ic-cmp">
                                            <i class="notika-icon notika-map"></i>
                                        </div>
                                        <div class="bootstrap-select ic-cmp-int">
                                            <select name="warehouse_id" id="warehouse_id" class="selectpicker form-control" data-live-search="true">
                                                <option value="">*Select warehouse</option>
                                                @foreach($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}" data-type="{{ $warehouse->type }}"
                                                    @if($user->warehouse_id == $warehouse->id) selected @endif>{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('warehouse_id')
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
                                            <i class="notika-icon notika-support"></i>
                                        </div>
                                        <div class="nk-int-st">
                                            <input type="text" name="warehouse_type_name" id="warehouse_type_name" value="{{ old('name') }}" class="form-control" placeholder="Warehouse Type Name" readonly>
                                            @error('warehouse_type_name')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                <hr>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group ic-cmp-int">
                                        <div class="form-ic-cmp">
                                            <i class="notika-icon notika-support"></i>
                                        </div>
                                        <div class="nk-int-st">
                                            <input
                                                type="text" name="name" value="{{ $user->name }}" class="form-control"
                                                placeholder="*Full Name" >
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
                                                type="email" name="email" value="{{ $user->email }}" class="form-control"
                                                placeholder="*Email Address" pattern="{{config('constants.EMAIL_PATTERN')}}" required>
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
                                                class="form-control"
                                                placeholder="*Contact Number" minlength="{{config('constants.MOBILE_DIGIT_LIMIT')}}" maxlength="{{config('constants.MOBILE_DIGIT_LIMIT')}}" pattern="{{config('constants.MOBILE_DIGIT_PATTERN')}}" required>
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
                                            <select name="gender" class="selectpicker form-control" data-live-search="true">
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
                                        <div class="nk-int-st">
                                            <input
                                                type="password" name="password" value="{{ old('password') }}"
                                                class="form-control" placeholder="*Enter Password" >
                                            @error('password')
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
                                                    <option @if (isset($user->user_role->id))
                                                            {{  $user->user_role->id == $role->id ? 'selected' : '' }}
                                                            value="{{ $role->id }}">{{ $role->name }}
                                                        @endif</option>
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

                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group ic-cmp-int">
                                        <div class="form-ic-cmp">
                                            <i class="notika-icon notika-ip-locator"></i>
                                        </div>
                                        <div class="nk-int-st">
                                            <input
                                                type="text" name="details" value="{{ $user->details }}"
                                                class="form-control"
                                                placeholder="*Details" >
                                            @error('details')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-outline checkbox-success">
                                                <input value="1" type="checkbox" id="addedItemCheckbox" name="status"
                                                       {{ $user->status == 1 ? 'checked' : '' }}
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
        $("#warehouse_id").change(function () {
            var id = $(this).val();
            if(id != ''){
                $.ajax({
                    url: 'warehouse_type/'+id,
                    dataType: 'html',
                    type: 'GET',
                    success: function (res) {
                        $("#warehouse_type_name").val(res)
                    }
                });
            }
        });
        //binds to onchange event of your input field
        // $('#img').bind('change', function() {
        //     var ext = $('#img').val().split('.').pop().toLowerCase();
        //     if ($.inArray(ext, ['gif','png','jpg','jpeg','svg','jfif']) == -1){
        //         $('#error1').slideDown("slow");
        //         $('#img').val('');
        //     }else {
        //         $('#error1').slideUp("slow");
        //     }
        // });

        filepond_single_image_configure('{{ $user->image_url }}');
    </script>

    <script src="{{ asset('backend/js/datapicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('backend/js/datapicker/datepicker-active.js') }}"></script>
    <!-- bootstrap select JS
        ============================================ -->
    <script src="{{ asset('backend/js/bootstrap-select/bootstrap-select.js') }}"></script>

@endpush


