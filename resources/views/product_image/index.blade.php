@extends('layouts.crud-master')
@section('title', 'Product Image Details')

@push('css')
{{--    <link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}">--}}

@endpush


@section('main_content')

    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div
            class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Product Image Details</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-muted font-weight-bold mr-4">List of Product Image details</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <!--begin::Actions-->
                <a
                    data-toggle="tooltip"
                    title="Add Product Image"
                    href="{{route('admin.product_image.create')}}"
                    class="btn btn-light-success btn-sm btn-clean font-weight-bold font-size-base mr-1">
                    <i class="fa fa-plus"></i>Add Product Image
                </a>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar-->
        </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">

                    @include('component.message')

                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form
                                method="POST" action="{{ route('admin.product_image.store') }}" accept-charset="UTF-8" id="product_image_upload"
                                enctype="multipart/form-data">
                                @csrf
                            <div class="form-row align-items-end">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Product Name</label>
                                        <input type="hidden" id="unique_product_id" value="">
                                        <select name="product_id" id="product_id" class="selectpicker form-control" data-live-search="true">
                                            <option value="">*Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select><br/>
                                        @error('product_id')
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
                                        <div class="custom-file" >
                                            <input id="gallery-photo-add"
                                                type="file" class="custom-file-input"
                                                value="{{ old('img') }}"
                                                autocomplete="off" name="img[]" multiple>
                                            <label class="custom-file-label" for="customFile"> Upload Image (1200*1200)
                                            </label>
                                        </div><br/>

                                        @error('img')
                                        <strong class="text-danger" role="alert">
                                            <span>{{ $message }}</span>
                                        </strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
{{--                                    <div class="gallery"></div>--}}

                                </div>
                            </div>
                            <button type="submit" class="btn btn-success waves-effect">Submit</button>
                            </form>

                            <br/>
                            <div id="reorder_msg"> </div>
                            <br/>

                            <div class="form-row row per_product_images align-items-end" id="productImageSort">

                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

    <script type="text/javascript" src="{{ asset('assets/plugins/sortable/js/Sortable.min.js') }}"></script>
{{--    <script type="text/javascript" src="{{ asset('assets/plugins/dropzone/dropzone.min.js') }}"></script>--}}
    <script>
        $().ready(function () {

            $(".alert").delay(5000).slideUp(300);


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Multiple images preview in browser
            // var imagesPreview = function(input, placeToInsertImagePreview) {
            //
            //     if (input.files) {
            //         var filesAmount = input.files.length;
            //
            //         for (i = 0; i < filesAmount; i++) {
            //             var reader = new FileReader();
            //
            //             reader.onload = function(event) {
            //                 $($.parseHTML('<img width="200px" height="150px">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
            //             }
            //
            //             reader.readAsDataURL(input.files[i]);
            //         }
            //     }
            //
            // };
            //
            // $('#gallery-photo-add').on('change', function() {
            //     imagesPreview(this, 'div.gallery');
            // });

            // $('#unique_product_id').val();
            let searchParams = new URLSearchParams(window.location.search)
            const val_product_id = searchParams.get('product_id');
            // const val_product_id = $('#unique_product_id').val();
            if (val_product_id){
            $('#product_id').val(val_product_id);
                setTimeout(function(){ $('#product_id').trigger('change'); }, 1000);
            }

            $("#product_id").change(function () {
                var id = $(this).val();
                if(id != ''){
                    var request = $.ajax({
                        url: 'product_image/get_image/'+id,
                        dataType: 'json',
                        type: 'GET',
                    });
                    request.done(function (response) {
                        $('div.per_product_images').html('');
                        $('#reorder_msg').html('');
                        var url = "{{URL::to('/')}}";
                        var product_images = response.product_images;
                        if (product_images.length > 0){
                            $('#reorder_msg').html('Please Drag to Reorder Images -');
                        }
                        var output = '';
                        $.each(product_images, function (index, product_image) {
                            var id = product_image['id'];
                            var path = product_image['x_100_path'];
                            output += '<div class="column pop_img" data-img=" ' + url + '/' + path+ ' " data-id="' + id +  '" id="' + id +  '" style="padding: 5px">' +
                                '<img src="' + url + '/' + path + '" alt="image"   id="per_image_id_' + id + '" >' +
                                '</br>  <a href="#" class="btnRemoveImage" data-image_id="' + id + '" style="color: #AF0000">Remove</a>'+
                                '</div>';
                        });
                        $("div.per_product_images").append(output);
                        $('#unique_product_id').val(id);
                    });
                }
            });

            $('body').on('click', '.btnRemoveImage', function () {

                var val_image_id = $(this).data('image_id');
                $(this).closest('div').remove();
                if(val_image_id != ''){
                    var request = $.ajax({
                        type: 'post',
                        url: 'product_image/destroy',
                        data: {
                            'val_image_id': val_image_id,
                        },
                        dataType: "json"
                    });
                    request.done(function (response) {
                        if (response.success == true){
                        toastr.success('Product Image deleted success');
                            var parentDiv = [];
                            $("#productImageSort > div").each((index, elem) => {
                                parentDiv.push(elem.id);
                            });
                            updateSort(parentDiv);
                        }
                    });
                }
            });


            var el = document.getElementById('productImageSort');
            Sortable.create(el, {
                animation: 150,
                dataIdAttr: 'data-id',
                onEnd: function () {
                    updateSort(this.toArray());
                },
            });

            function updateSort(ids) {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin.product_images_sort') }}",
                    data: { ids: ids }
                }).done(function(response) {
                    if (response.success == true){
                    toastr.success('Product images sorting updated!');
                    }
                });
            }




        });




    </script>




@endpush
