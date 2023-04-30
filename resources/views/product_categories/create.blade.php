@extends('layouts.crud-master')
@section('title', 'Category Create')
@push('css')
    <style>
        .ui-autocomplete { height: 200px; overflow-y: scroll; overflow-x: hidden;}
    </style>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
                            <form method="POST" action="{{ route('admin.product_category.store') }}" accept-charset="UTF-8"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name <span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                    <input class="form-control" id="name" value="{{ old('name') }}" autocomplete="off"
                                           name="name" type="text">
                                    @error('name')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="slug">Slug <span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                    <input class="form-control" id="slug" value="{{ old('slug') }}" autocomplete="off"
                                           name="slug" type="text">
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
                                            <option value="">Please Select (optional)</option>
                                            @foreach($parents_categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @if($category->childrenRecursive && $category->childrenRecursive->count() > 0)
                                                    @foreach($category->childrenRecursive as $sub)
                                                        <option value="{{ $sub->id }}">&nbsp;--{{ $sub->name }}</option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" onclick="showCategoryModal(this)" type="button">Add category
                                            </button>
                                        </div>
                                    </div>
                                    @error('parent_category_id')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                    
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control date" id="description"
                                              name="description">{{ old('description') }}</textarea>
                                    @error('description')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="img">Image (jpeg, png, jpg, gif, svg, jfif)</label>
                                    <input class="form-control" id="img" value="{{ old('img') }}" autocomplete="off" name="img"
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
                                {{-- <div class="form-group">
                                    <label for="img">Image <span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                    <input class="form-control" id="img" value="{{ old('img') }}" autocomplete="off" name="img"
                                           type="file">
                                    @error('img')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div> --}}

                                <div class="form-group">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input value="1" type="checkbox" id="addedItemCheckbox" name="status"
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
                                <button type="submit" style="background: #00c292; color: #f0f0f0" class="btn waves-effect">Save
                                    Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
    @include('product_categories.modal.category')
@endsection


@push('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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

            // Auto search for brand from parent table

            $( "#name" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        type: 'post',
                        url:'{{route('admin.product_category.liveSearch.ajax')}}',
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
                    var nameText = $("#name").val();
                    var trimmed = $.trim(nameText);
                    var slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
                    replace(/-+/g, '-').
                    replace(/^-|-$/g, '');
                    nameText = slug.toLowerCase();
                    $("#slug").val(nameText);
                    return false;
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
            console.log('para: ', para);
            var parent_category_id = $(para).data('id')
            if(parent_category_id == undefined || parent_category_id == ''){
                parent_category_id = '';
            }
            var parent_text = $(para).text()
            $('.appendOption').html(`<option value="` + parent_category_id + `">` + parent_text + `</option>`)
        }


    </script>
@endpush
