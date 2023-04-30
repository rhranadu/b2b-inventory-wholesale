@extends('layouts.crud-master')
@section('title', 'Payment Method Create')
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
                                method="POST" action="{{ route('superadmin.payment_methods.store') }}" accept-charset="UTF-8"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="payment_type">Payment Type <span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                    <input class="form-control" id="payment_type"
                                           autocomplete="off"
                                           name="payment_type" type="text">
                                    @error('payment_type')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="descriptions">Descriptions</label>
                                    <textarea class="form-control" id="descriptions"
                                              autocomplete="off" name="descriptions" type="text">{{ old('descriptions') }}</textarea>
                                    @error('descriptions')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input
                                                value="1"
                                            type="checkbox"
                                                id="addedItemCheckbox" name="status"
                                                class="i-checks">
                                            <span></span>
                                            Status
                                        </label><span style="color: red; font-size: 18px"><sup></sup></span>
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
                                <button type="submit" style="background: #00c292; color: #f0f0f0"
                                        class="btn waves-effect">
                                    Submit Data
                                </button>
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
        $(document).ready(function () {
            filepond_single_image_configure();
        });
    </script>

@endpush
