@extends('layouts.app')
@section('title', 'Service Contracts Edit')

@section('main_content')

    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-body">

            @include('component.message')

            <div class="normal-table-list">
                <div class="bsc-tbl">
                    <form method="POST" action="{{ route('admin.service_contracts.update', $serviceContracts->id) }}"
                          accept-charset="UTF-8" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Title <span
                                    style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                            <input class="form-control" id="title" value="{{ $serviceContracts->title }}" autocomplete="off"
                                   name="title" type="text">
                            @error('title')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug <span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                            <input class="form-control" id="slug" value="{{ $serviceContracts->slug }}" autocomplete="off"
                                   name="slug" type="text">
                            @error('slug')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="icon">Icon </label>
                            <input class="form-control" id="icon" value="{{ $serviceContracts->icon }}" autocomplete="off"
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
                            <input class="form-control" id="position" value="{{ $serviceContracts->position }}" autocomplete="off"
                                   name="position" type="text">
                            @error('position')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="details">Details <span
                                    style="color: red; font-size: 20px;"><sup></sup></span></label>
                            <input class="form-control" id="details" value="{{ $serviceContracts->details }}" autocomplete="off"
                                   name="details" type="text">
                            @error('details')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success waves-effect">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
