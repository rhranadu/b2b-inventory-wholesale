@extends('layouts.crud-master')
@section('title', 'Service Contracts Create')
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
                            <form method="POST" action="{{ route('admin.service_contracts.store') }}" accept-charset="UTF-8"
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
                                    <label for="icon">Icon </label>
                                    <input class="form-control" id="icon" value="{{ old('icon') }}" autocomplete="off"
                                           name="icon" type="text">
                                    @error('icon')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="position">Position <span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="position" value="{{ old('position') }}" autocomplete="off"
                                           name="position" type="text">
                                    @error('position')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="details">Details<span
                                            style="color: red; font-size: 20px;"><sup></sup></span></label>
                                    <textarea class="form-control" id="details" autocomplete="off" title="details" name="details" type="text">{{ old('details') }}</textarea>
                                    @error('details')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
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
    <script>
        // Jquery Slug from product name
        $("#title").keyup(function(){
            var nameText = $("#title").val();
            var trimmed = $.trim(nameText);
            var slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
            replace(/-+/g, '-').
            replace(/^-|-$/g, '');
            nameText = slug.toLowerCase();
            $("#slug").val(nameText);
        });
    </script>

@endpush
