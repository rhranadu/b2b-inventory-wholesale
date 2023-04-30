@extends('layouts.crud-master')
@section('title', 'Manufacturer Create')
@push('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    {{--    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond/css/filepond.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond/css/filepond-plugin-image-preview.css') }}">--}}
    <style>
        .filepond--item {
            width: calc(20% - .2em);
        }
        /*.filepond--root {*/
        /*    max-height: 10em;*/
        /*}*/
        .ui-autocomplete { height: 200px; overflow-y: scroll; overflow-x: hidden;}
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
                                method="POST" action="{{ route('admin.manufacturer.store') }}" accept-charset="UTF-8"
                                enctype="multipart/form-data" id="manufacturer_image_upload">
                                @csrf
                                <input type="hidden" name="parenttype" >
                                <input type="hidden" name="parent_manufacturer_id" id="parent_manufacturer_id" value="">

                                <div class="form-row align-items-end">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Name
                                                <span style="color: red; font-size: 18px">
                                                    <sup>*</sup>
                                                </span>
                                            </label>
                                            <input
                                                class="form-control" id="name" value="{{ old('name') }}"
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
                                                class="form-control" id="email" value="{{ old('email') }}"
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
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="phone">Phone </label>
                                            <input
                                                class="form-control" id="phone" value="{{ old('phone') }}"
                                                autocomplete="off"
                                                name="phone" type="text" minlength='{{config('constants.MOBILE_DIGIT_LIMIT')}}' maxlength="{{config('constants.MOBILE_DIGIT_LIMIT')}}" pattern="{{config('constants.MOBILE_DIGIT_PATTERN')}}" required>
                                            @error('phone')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label for="country_name">Country name of Manufacturer
                                                <span
                                                    style="color: red; font-size: 18px">
                                                    <sup>*</sup>
                                                </span>
                                            </label>
                                            <select
                                                name="country_name" id="country_name" class="form-control"
                                                value="{{ old('country_name') }}">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option {{ old('country_name') ==  $country->name ? "selected" : "" }} value="{{ $country->name }}">{{ ucfirst($country->name) }}</option>
                                                @endforeach
                                            </select>
                                            @error('country_name')
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
                                        rows="10">{{ old('description') }}</textarea>
                                    @error('description')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>


                                <div class="form-row align-items-end">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="website">Website Link</label>
                                            <input
                                                class="form-control" id="website" value="{{ old('website') }}"
                                                autocomplete="off"
                                                name="website" type="text">
                                            @error('website')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                                <label >Image (jpeg, png, jpg, gif, svg)
                                                </label>
                                            <input type="file" class="form-control" name="image" id="img">
                                            <p id="error1" style="display:none; color:#FF0000;">
                                                Invalid Image Format! Image Format Must Be JPG, JPEG, PNG, SVG or GIF.
                                            </p>
{{--                                                <input type="file"--}}
{{--                                                       class="filepond"--}}
{{--                                                       name="image"--}}
{{--                                                       data-allow-reorder="true"--}}
{{--                                                       data-max-file-size="2MB"--}}
{{--                                                       data-max-files="1"--}}
{{--                                                       id="filepondImage">--}}

                                            @error('image')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-group">
                                            <div class="checkbox-inline">
                                                <label class="checkbox checkbox-outline checkbox-success">
                                                    <input
                                                        value="1" type="checkbox" id="addedItemCheckbox" name="status"
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
                                <button type="submit" class="btn btn-success waves-effect" id="manufacturerSubmit">Create</button>
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
{{--    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-image-preview.min.js') }}"></script>--}}
{{--    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-file-validate-type.js') }}"></script>--}}
{{--    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-image-validate-size.js') }}"></script>--}}
{{--    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-image-exif-orientation.js') }}"></script>--}}
{{--    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-file-validate-size.js') }}"></script>--}}
{{--    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond-plugin-file-encode.js') }}"></script>--}}
{{--    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond.min.js') }}"></script>--}}
{{--    <script type="text/javascript" src="{{ asset('assets/plugins/filepond/js/filepond.jquery.js') }}"></script>--}}
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
                if ($.inArray(ext, ['gif','png','jpg','jpeg','svg']) == -1){
                    $('#error1').slideDown("slow");
                    $('#img').val('');
                }else {
                    $('#error1').slideUp("slow");
                }
            });

            // Auto search for brand from parent table

            $( "#name" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        type: 'post',
                        url:'{{route('admin.manufacturer.liveSearch.ajax')}}',
                        dataType: "json",
                        data: {
                            search: request.term
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
                select: function (event, ui) {
                    $('#name').val(ui.item.label);
                    $('#parent_manufacturer_id').val(ui.item.manufacturer_id);
                    return false;
                }
            });


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
