@extends('layouts.crud-master')
@section('title', 'Supplier Edit')
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
                            <form
                                method="POST" action="{{ route('admin.supplier.update',$supplier->id) }}"
                                accept-charset="UTF-8"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Name<span
                                            style="color: red; font-size: 20px;">
                                            <sup>*</sup>
                                        </span></label>
                                    <input
                                        class="form-control" id="name" value="{{ $supplier->name }}" autocomplete="off"
                                        name="name" type="text">
                                    @error('name')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input
                                        class="form-control" id="email" value="{{ $supplier->email }}" autocomplete="off"
                                        name="email" type="email" pattern="{{config('constants.EMAIL_PATTERN')}}" >
                                    @error('email')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="mobile">Mobile No</label>
                                    <input
                                        class="form-control" id="mobile" value="{{ $supplier->mobile }}"
                                        autocomplete="off"
                                        name="mobile" type="text" minlength="{{config('constants.MOBILE_DIGIT_LIMIT')}}" maxlength="{{config('constants.MOBILE_DIGIT_LIMIT')}}" pattern="{{config('constants.MOBILE_DIGIT_PATTERN')}}" >
                                    @error('mobile')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>


                                <div class="form-group">
                                    <label for="type">Supplier Type<span
                                            style="color: red; font-size: 20px;">
                                            <sup>*</sup>
                                        </span></label>
                                    <select class="form-control" id="type" name="type">
                                        <option value="supplier">Supplier</option>
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea
                                        class="form-control date" id="address" name="address" cols="50"
                                        rows="10">{{ $supplier->address }}</textarea>
                                    @error('address')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>


                                <div class="form-group">
                                    <label for="details">Details</label>
                                    <textarea
                                        class="form-control date" id="details" name="details" cols="50"
                                        rows="10">{{  $supplier->details }}</textarea>

                                </div>

                                <div class="form-group">
                                    <label for="website">Website</label>
                                    <input
                                        class="form-control" id="website" value="{{  $supplier->website }}"
                                        autocomplete="off"
                                        name="website" type="text">

                                </div>

                                <div class="d-flex form-group">
                                    @if($supplier->image)
                                        <div class="image_square pop_img mr-5" data-img="{{ asset($supplier->image) }}">
                                            <img
                                                src="{{ asset($supplier->image) }}"
                                                alt="image">
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="img">Image (jpeg, png, jpg, gif, svg, jfif)</label>
                                        <input
                                            class="form-control" id="img" value="" autocomplete="off" name="img"
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
                                                value="1" {{ $supplier->status == 1 ? 'checked' : '' }} type="checkbox"
                                                id="addedItemCheckbox" name="status"
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
                                <button
                                    type="submit" style="background: #00c292; color: #f0f0f0" class="btn waves-effect">
                                    Update Data
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
    <script>

        $(document).ready(function () {
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
    </script>
@endpush
