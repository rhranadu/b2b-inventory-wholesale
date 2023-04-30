@extends('layouts.crud-master')
@section('title', 'Tax Create')
@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom" id="kt_card_1">
                <div class="card-body">
                    @include('component.message')
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form method="POST" id="globalForm" action="{{ route('superadmin.commission.global.ajax') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <label  class="col-3 col-form-label">Global Sale Commission (%)</label>
                                    <div class="col-2">
                                        <div class="input-group">
                                            <input class="form-control" id="global" type="text" value="{{ $global->value ?: 0 }}"/>
                                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <button type="button" data-id="{{ $global->id }}" id="globalFormSubmit" class="btn btn-success">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->

@endsection

@push('script')
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
    });
    $(document).off('click', '#globalFormSubmit').on('click', '#globalFormSubmit', function() {
        $.ajax({
            url: "{{ route('superadmin.commission.global.ajax') }}",
            type: "post",
            data: {commission: $("#global").val(), id: $(this).data('id')},
            success: function (res) {
                if (res === 'true') {
                    toastr.success('Global sale commission updated');
                } else {
                    toastr.error('Failed to update');
                }
            }
        })
    })
</script>
@endpush
