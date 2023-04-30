@extends('layouts.crud-master')
@section('title', 'Promotional Ads Create')
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
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">

                    @include('component.message')
                    <p> <span class="badge badge-info"> Position : </span> 1, 2 --> Right Side. <span class="badge badge-info"> Position : </span> 3, 4, 5  --> Middle.   <span class="badge badge-info"> Position : </span> 6, 7 --> Bottom.</p>

                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form method="POST" action="{{ route('superadmin.promotional_advertisement.store') }}" accept-charset="UTF-8"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="name">Name <span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="name" value="{{ old('name') }}" autocomplete="off"
                                           name="name" type="text">
                                    @error('name')
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
                                    <label for="link">Link </label>
                                    <input class="form-control" id="link" value="{{ old('link') }}" autocomplete="off"
                                           name="link" type="text">
                                    @error('link')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="position">Position <span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="position" value="{{ old('position') }}" autocomplete="off"
                                           name="position" type="number">
                                    @error('position')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
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
    </script>

@endpush
