@extends('layouts.crud-master')
@section('title', 'Category Edit')
@push('css')
    <link href="{{ asset('backend/treeview/css/treeview.css') }}" rel="stylesheet">
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
                                  action="{{ route('admin.product_category.update',$productCategory->id) }}"
                                  accept-charset="UTF-8" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
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
                                <div class="d-flex form-group">
                                    @if($productCategory->image)
                                        <div class="image_square pop_img mr-5" data-img="{{ asset($productCategory->image) }}">
                                            <img
                                                src="{{ asset($productCategory->image) }}"
                                                alt="image">
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="img">Image (jpeg, png, jpg, gif, svg, jfif)</label>
                                        <input
                                            class="form-control" id="img" value="{{ old('img') }}" autocomplete="off" name="img"
                                            type="file">
                                        <p id="error1" style="display:none; color:#FF0000;">
                                            Invalid Image Format! Image Format Must Be JPG, JPEG, PNG, SVG, JFIF or GIF.
                                        </p>
                                        @error('img')
                                        <strong class="text-danger" role="alert">
                                            <span>{{ $message }}</span>
                                        </strong>
                                        @enderror
                                    </div>
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
    <script>
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
