@extends('layouts.crud-master')
@section('title', 'Manufacturer Edit')
@push('css')
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
                            <form
                                method="POST" action="{{ route('admin.manufacturer.update', $manufacturer->id) }}"
                                accept-charset="UTF-8" enctype="multipart/form-data" id="manufacturer_image_upload">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="parenttype" >

                                <div class="form-row align-items-end">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Name
                                                <span style="color: red; font-size: 18px">
                                                    <sup>*</sup>
                                                </span>
                                            </label>
                                            <input
                                                class="form-control" id="name" value="{{ $manufacturer->name }}"
                                                autocomplete="off"
                                                name="name" type="text">
                                            @error('name')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label for="email">Email </label>
                                            <input
                                                class="form-control" id="email" value="{{ $manufacturer->email }}"
                                                autocomplete="off"
                                                name="email" type="email" pattern="{{config('constants.EMAIL_PATTERN')}}" required>
                                            @error('email')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row align-items-end">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="phone">Phone </label>
                                            <input
                                                class="form-control" id="phone" value="{{ $manufacturer->phone }}"
                                                autocomplete="off"
                                                name="phone" type="text" minlength="{{config('constants.MOBILE_DIGIT_LIMIT')}}" maxlength="{{config('constants.MOBILE_DIGIT_LIMIT')}}" pattern="{{config('constants.MOBILE_DIGIT_PATTERN')}}" required>
                                            @error('phone')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="country_name">Country name of Manufacturer
                                                <span
                                                    style="color: red; font-size: 18px">
                                                    <sup>*</sup>
                                                </span>
                                            </label>
                                            <select
                                                name="country_name" id="country_name" class="form-control"
                                                value="{{ $manufacturer->country_name }}">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option
                                                        {{ $country->name == $manufacturer->country_name ? 'selected' : '' }} value="{{ $country->name }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('country_name')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="website">Website Link</label>
                                            <input
                                                class="form-control" id="website" value="{{ $manufacturer->website }}"
                                                autocomplete="off" name="website" type="text">
                                            @error('website')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address">Description
                                        <span
                                            style="color: red; font-size: 18px">
                                            <sup></sup>
                                        </span>
                                    </label>
                                    <textarea
                                        class="form-control date" id="description" name="description" cols="50"
                                        rows="10">{{ $manufacturer->description }}</textarea>
                                    @error('description')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="d-flex form-group">
                                    @if($manufacturer->image)
                                        <div class="image_square pop_img mr-5" data-img="{{ asset($manufacturer->image) }}">
                                            <img
                                                src="{{ asset($manufacturer->image) }}"
                                                alt="image">
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="image">Image (jpeg, png, jpg, gif, svg, jfif)</label>
                                        <input
                                            class="form-control" id="img" value="" autocomplete="off" name="image"
                                            type="file">
                                        <p id="error1" style="display:none; color:#FF0000;">
                                            Invalid Image Format! Image Format Must Be JPG, JPEG, PNG, SVG, JFIF or GIF.
                                        </p>
                                        @error('image')
                                        <strong class="text-danger" role="alert">
                                            <span>{{ $message }}</span>
                                        </strong>
                                        @enderror
                                    </div>
                                </div>
{{--                                <div class="form-group">--}}
{{--                                    <input type="hidden" id="manufacturer_img" value="{{ asset($manufacturer->image) }}">--}}
{{--                                        <label for="img">Image</label>--}}
{{--                                        <input type="file"--}}
{{--                                               class="filepond"--}}
{{--                                               name="image"--}}
{{--                                               multiple--}}
{{--                                               data-allow-reorder="true"--}}
{{--                                               data-max-file-size="2MB"--}}
{{--                                               data-max-files="1"--}}
{{--                                               id="filepondImage">--}}
{{--                                        @error('img')--}}
{{--                                        <strong class="text-danger" role="alert">--}}
{{--                                            <span>{{ $message }}</span>--}}
{{--                                        </strong>--}}
{{--                                        @enderror--}}
{{--                                </div>--}}
                                <div class="form-group">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input
                                                value="1"
                                                {{ $manufacturer->status == 1 ? 'checked' : '' }} type="checkbox"
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
                                <div class="mt-5">
                                    <button type="submit" class="btn btn-success waves-effect" id="manufacturerSubmit">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //binds to onchange event of your input field
            $('#img').bind('change', function() {
                var ext = $('#img').val().split('.').pop().toLowerCase();
                if ($.inArray(ext, ['gif','png','jpg','jpeg','svg','jfif']) == -1){
                    $('#error1').slideDown("slow");
                    $('#img').val('');
                }else {
                    $('#error1').slideUp("slow");
                }
            });
            // var val_img = $("#manufacturer_img").val();
            // var imageArr = [{source: val_img}];
            //
            // FilePond.registerPlugin(
            //     FilePondPluginImagePreview,
            //     FilePondPluginImageExifOrientation,
            //     FilePondPluginFileValidateSize,
            //     FilePondPluginFileValidateType,
            //     FilePondPluginImageValidateSize,
            //     FilePondPluginFileEncode
            // );
            //
            //
            // const inputElement = document.querySelector('input[type="file"]');
            // const pond = FilePond.create( inputElement , {
            //     allowFileEncode: true,
            //     acceptedFileTypes: ['image/jpeg','image/jpg','image/png','image/bmp'],
            //     allowFileSizeValidation: true,
            //     maxFileSize: '2MB',
            //     maxFiles: 1,
            //     files:imageArr
            // });
            // pond.on('warning', (error, file) => {
            //     if(error.body === "Max files"){
            //         Swal.fire({
            //             text: "Sorry, You have uploaded more than 1 files",
            //             icon: "error",
            //             confirmButtonText: "Ok, got it!",
            //         })
            //     }
            // });

            // $( "#manufacturerSubmit" ).click(function( event ) {
            //     $('#manufacturer_image_upload').submit();
            // });

        });
    </script>
@endpush
