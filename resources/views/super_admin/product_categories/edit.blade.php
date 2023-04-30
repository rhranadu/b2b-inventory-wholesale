@extends('layouts.crud-master')
@section('title', 'Category Edit')
@push('css')
    <link href="{{ asset('backend/treeview/css/treeview.css') }}" rel="stylesheet">
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
                            <form method="POST"
                                  action="{{ route('superadmin.product_category.update',$productCategory->id) }}"
                                  accept-charset="UTF-8" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name <span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                    <input class="form-control" id="name" value="{{ $productCategory->name }}"
                                           autocomplete="off" name="name" type="text">
                                    @error('name')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                    </div>
                                    <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug">Slug <span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                    <input class="form-control" id="slug" value="{{ $productCategory->slug }}"
                                           autocomplete="off" name="slug" type="text">
                                    @error('slug')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                    </div>
                                    <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parent_category_id">Parent Category </label>
                                    <div class="input-group">
                                        <select class="form-control appendOption" id="parent_category_id" name="parent_category_id">
                                            {{-- <option value="">Please select Parent Category</option>
                                            @foreach($parents_categories as $parents_category)
                                                <option value="{{ $parents_category->id }}" {{$productCategory->parent_category_id == $parents_category->id ? 'selected' : ''}}>{{ $parents_category->name }}</option>
                                                @if($parents_category->childes && $parents_category->childes->count() > 0)
                                                    @foreach($parents_category->childes as $sub)
                                                        <option value="{{ $sub->id }}" {{$productCategory->parent_category_id == $sub->id ? 'selected' : ''}}>&nbsp;--{{ $sub->name }}</option>
                                                    @endforeach
                                                @endif
                                            @endforeach --}}
                                           <option value="{{ $productCategory->parent->id ?? '' }}">{{ $productCategory->parent->name ?? 'No Parent'}}</option>
                                        </select>
                                        @error('parent_category_id')
                                        <strong class="text-danger" role="alert">
                                            <span>{{ $message }}</span>
                                        </strong>
                                        @enderror
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" onclick="showCategoryModal(this)" type="button">Add category
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="position">Position <span style="color: red; font-size: 18px"><sup></sup></span></label>
                                            <input class="form-control" id="position" value="{{ $productCategory->position }}"
                                                   autocomplete="off" name="position" type="number">
                                            @error('position')
                                            <strong class="text-danger" role="alert">
                                                <span>{{ $message }}</span>
                                            </strong>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control date" id="description"
                                              name="description">{{ $productCategory->description }}</textarea>
                                    @error('description')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input
                                                value="1" {{ $productCategory->status == 1 ? 'checked' : '' }} type="checkbox"
                                                id="addedItemCheckbox" name="status"
                                                class="i-checks">
                                            <span></span>
                                            Status
                                        </label> <span style="color: red; font-size: 18px"><sup></sup></span>
                                    </div>

                                    @error('status')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input
                                                value="1" {{ $productCategory->is_homepage == 1 ? 'checked' : '' }} type="checkbox"
                                                id="is_homepage" name="is_homepage"
                                                class="i-checks">
                                            <span></span>
                                            Is Homepage
                                        </label> <span style="color: red; font-size: 18px"><sup></sup></span>
                                    </div>

                                    @error('is_homepage')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="image">ProductCategory Image <span
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

                                <button type="submit" style="background: #00c292; color: #f0f0f0"
                                        class="btn waves-effect">Update Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('product_categories.modal.category')
@endsection

@push('script')
    <script src="{{ asset('backend/treeview/js/treeview.js') }}"></script>
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
        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
        };
        $(document).ready(function () {

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

            filepond_single_image_configure('{{ $productCategory->image_url }}');

            //binds to onchange event of your input field
            // $('#img').bind('change', function() {
            //     var ext = $('#img').val().split('.').pop().toLowerCase();
            //     if ($.inArray(ext, ['gif','png','jpg','jpeg','svg']) == -1){
            //         $('#error1').slideDown("slow");
            //         $('#img').val('');
            //     }else {
            //         $('#error1').slideUp("slow");
            //     }
            // });
        });
        $(document).off("click", ".select-category").on("click", ".select-category", function () {
            selectCategory(this);
        })
        function showCategoryModal() {
            $("#CategoryModal").modal('show');
        }

        function selectCategory(para) {
            var parent_category_id = $(para).data('id')
            if(parent_category_id == undefined || parent_category_id == ''){
                parent_category_id = '';
            }
            var parent_text = $(para).text()
            $('.appendOption').html(`<option value="` + parent_category_id + `">` + parent_text + `</option>`)
        }
    </script>
@endpush
