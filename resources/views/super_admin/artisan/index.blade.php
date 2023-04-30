@extends('layouts.crud-master')
@section('title', 'Artisan')
@push('css')

@endpush
@section('main_content')

<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                @include('component.message')
                <div class="normal-table-list">
                    <div class="bsc-tbl">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="inputCommand"
                                                placeholder="Run Artisan Command Without 'php artisan' e.g: list">
                                        <div class="input-group-append">
                                            <button class="btn btn-success" id="btnRun">
                                                Run
                                            </button>
                                            <button class="btn btn-danger" id="btnClear">
                                                Clear
                                            </button>
                                        </div>
                                    </div>
                                    <span class="form-text text-muted">Run Artisan Command Without 'php artisan' e.g:list</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b max-h-400px" style="overflow-y:auto" id="">
            <div class="card-body">
                <div class="row">
                    <div class="col" id="msgPlaceholder"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).off("click", "#btnRun").on("click", "#btnRun", function (e) {
        let url = "{!! url('/') !!}" + '/superadmin/artisan';
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            data: {command: $("#inputCommand").val()},
            success: function(response) {
                $("#msgPlaceholder").append("<hr><strong>Command: " +$("#inputCommand").val() + "</strong></br>")
                if(!response.error){
                    $("#msgPlaceholder").append(response.data);
                } else {
                    errorsHtml = '<div class="text-danger">';
                    errorsHtml += response.msg;
                    errorsHtml += '</div>';
                    $("#msgPlaceholder").append(errorsHtml);
                }
            },
            error: function(response) {
                $("#msgPlaceholder").append("<hr><strong>Command: " +$("#inputCommand").val() + "</strong></br>")
                var errors = response.responseJSON;
                errorsHtml = '<div class="text-danger"><ul>';
                $.each(errors,function (k,v) {
                    if(k == 'trace') {
                        return true;
                    }
                    errorsHtml += '<li>'+ k +': '+ v + '</li>';
                });
                errorsHtml += '</ul></div>';
                $('#msgPlaceholder').append(errorsHtml);
            }
        });
    })
    $(document).off("click", "#btnClear").on("click", "#btnClear", function (e) {
        $("#inputCommand").val('');
        $("#msgPlaceholder").html('');
    })
</script>
@endpush
