@extends('layouts.app')
@section('title', 'Vendor Edit')
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

    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-body">


            @include('component.message')

            <div class="normal-table-list">
                <div class="bsc-tbl">
                    <form method="POST" action="{{ route('superadmin.vendor.update', $vendor->id) }}"
                          accept-charset="UTF-8" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name <span
                                    style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                            <input class="form-control" id="name" value="{{ $vendor->name }}" autocomplete="off"
                                   name="name" type="text">
                            @error('name')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug <span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                            <input class="form-control" id="slug" value="{{ $vendor->slug }}" autocomplete="off"
                                   name="slug" type="text">
                            @error('slug')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span
                                    style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                            <input class="form-control" id="email" value="{{ $vendor->email }}" autocomplete="off"
                                   name="email" type="email" pattern="{{config('constants.EMAIL_PATTERN')}}" required>
                            @error('email')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone <span
                                    style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                            <input class="form-control" id="phone" value="{{ $vendor->phone }}" autocomplete="off"
                                   name="phone" type="text" minlength="{{config('constants.MOBILE_DIGIT_LIMIT')}}" maxlength="{{config('constants.MOBILE_DIGIT_LIMIT')}}" pattern="{{config('constants.MOBILE_DIGIT_PATTERN')}}" required>
                            @error('phone')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control date" id="address" name="address" cols="50"
                                      rows="10">{{ $vendor->address }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="website">Website Link</label>
                            <input class="form-control" id="website" value="{{ $vendor->website }}" autocomplete="off"
                                   name="website" type="text">
                        </div>
                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-success">
                                    <input
                                        value="1" {{ $vendor->status == 1 ? 'checked' : '' }} type="checkbox"
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
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="image">Vendor Image <span
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
                        <button type="submit" class="btn btn-success waves-effect">Update</button>
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
        // Jquery Slug from product name
        $("#name").keyup(function(){
            var nameText = $("#name").val();
            var trimmed = $.trim(nameText);
            var slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
            replace(/-+/g, '-').
            replace(/^-|-$/g, '');
            nameText = slug.toLowerCase();
            $("#slug").val(nameText);
        });

        $(document).ready(function () {
            filepond_single_image_configure('{{ $vendor->logo_url }}');
        });
    </script>
@endpush
