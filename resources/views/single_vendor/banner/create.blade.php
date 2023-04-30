@extends('layouts.crud-master')
@section('title', 'Banner Create')
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
                                method="POST" action="{{ route('admin.banner.store') }}" accept-charset="UTF-8"
                                enctype="multipart/form-data">
                                @csrf

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
                                            <label for="type">Type
                                                <span
                                                    style="color: red; font-size: 18px">
                                                    <sup>*</sup>
                                                </span>
                                            </label>
                                            <select
                                                name="type" id="type" class="form-control"
                                                value="{{ old('type') }}">
                                                <option {{ old('type') == "" ? 'selected' : '' }} value="">Select Type</option>
                                                <option {{ old('type') == "banner" ? 'selected' : '' }} value="banner">Banner</option>
                                                <option {{ old('type') == "pop_up" ? 'selected' : '' }} value="pop_up">Pop-Up</option>
                                            </select>
                                            @error('type')
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
                                            <label for="url">Url
                                                <span style="color: red; font-size: 18px">
                                                    <sup></sup>
                                                </span></label>
                                            <input
                                                class="form-control" id="url" value="{{ old('url') }}"
                                                autocomplete="off"
                                                name="url" type="text">
                                            @error('url')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="meta_info">Meta Info</label>
                                            <input
                                                class="form-control" id="meta_info" value="{{ old('meta_info') }}"
                                                autocomplete="off"
                                                name="meta_info" type="text">
                                            @error('meta_info')
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
                                            <label for="alt_info">Alt Info</label>
                                            <input
                                                class="form-control" id="alt_info" value="{{ old('alt_info') }}"
                                                autocomplete="off"
                                                name="alt_info" type="text">
                                            @error('alt_info')
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
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="image">Image </label>
                                                {{--                                            <input type="file" class="form-control date" id="image" name="image">--}}
                                                <input type="file"
                                                       class="filepond"
                                                       name="image"
                                                       {{--                                                   multiple--}}
                                                       data-allow-reorder="true"
                                                       data-max-file-size="2MB"
                                                       data-max-files="10"
                                                       id="filepondImage">
                                                @error('image')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                <button type="submit" class="btn btn-success waves-effect">Create</button>
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
    <script type="text/javascript" src="{{ asset('assets/js/filepond_single_image_configure.js') }}"></script>

    <script>
        filepond_single_image_configure();
    </script>

@endpush
