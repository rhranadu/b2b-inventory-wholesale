@extends('layouts.crud-master')
@section('title', 'Email Template Registration')
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
                        <form action="{{ route('superadmin.email_registration_save') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea class="d-none" name="body_content" id="page_editor" rows="2">@if(!empty($info)){{ $info->body_content }}@endif</textarea>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <input class="btn btn-primary" type="submit" value="SAVE">
                                </div>
                            </div>
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
        $(function () {
            var message = '{{ session('message') }}';
            if (message != '')
                toastr.success(message);
        });
        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
        };
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.config.htmlEncodeOutput = false;
        CKEDITOR.config.basicEntities = false;
        CKEDITOR.replace('page_editor',options);

    </script>
@endpush

