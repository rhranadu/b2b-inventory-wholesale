@extends('layouts.crud-master')
@section('title', 'External Page Create')
@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">

                    @include('component.message')

                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form method="POST" action="{{ route('superadmin.external_pages.store') }}" accept-charset="UTF-8"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="title">Title<span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="title" value="{{ old('title') }}" autocomplete="off"
                                           name="title" type="text">
                                    @error('title')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="slug">Slug <span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="slug" value="{{ old('slug') }}" autocomplete="off"
                                           name="slug" type="text">
                                    @error('slug')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="descriptions">Description<span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <textarea class="d-none" id="descriptions"  name="descriptions" >{{ old('descriptions') }}</textarea>
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
                                                value="1" type="checkbox" id="status" name="status"
                                                class="i-checks social_links">
                                            <span></span>
                                            Status
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" style="background: #00c292; color: #f0f0f0" class="btn  waves-effect">
                                    Create
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Container-->
    </div>
    <!--begin::Entry-->

@endsection

@push('script')
    <script src="//cdn.ckeditor.com/4.15.1/full/ckeditor.js"></script>
    <script>
        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
        };
    CKEDITOR.config.allowedContent = true;
    CKEDITOR.config.htmlEncodeOutput = false;
    CKEDITOR.config.basicEntities = false;
    CKEDITOR.replace('descriptions',options);

    </script>

@endpush
