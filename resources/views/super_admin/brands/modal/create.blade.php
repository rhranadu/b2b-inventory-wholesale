<div class="modal fade" id="ParentBrandCreateModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Parent Brand Create</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive parentBrandTable">
                    <form method="POST" action="" accept-charset="UTF-8"
                          enctype="multipart/form-data" id="parentBrandFormSubmit">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name <span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                            <input class="form-control" id="name" value="{{ old('name') }}" autocomplete="off"
                                   name="name" type="text">
                            <span id="invalid-name" class="invalid-response text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug <span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                            <input class="form-control" id="slug" value="{{ old('slug') }}" autocomplete="off"
                                   name="slug" type="text">
                            <span id="invalid-slug" class="invalid-response text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="website">Website Link <span
                                    style="color: red; font-size: 18px"><sup></sup></span></label>
                            <input class="form-control" id="website" value="{{ old('website') }}" autocomplete="off"
                                   name="website" type="text">

                        </div>

                        <div class="form-group">
                            <label for="img">Image </label>
                            <input class="form-control" id="img" value="{{ old('img') }}" autocomplete="off" name="img"
                                   type="file">
                        </div>

                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-success">
                                    <input
                                        value="1" type="checkbox" id="addedItemCheckbox" name="status"
                                        class="i-checks">
                                    <span></span>
                                    Status
                                </label><span style="color: red; font-size: 18px"><sup></sup></span>
                            </div>
                        </div>
                        <button type="button" style="background: #00c292; color: #f0f0f0" class="btn waves-effect" id="parentBrandSubmit">Save
                            Data
                        </button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('script')

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

            $(document).off('click', '#parentBrandSubmit').on('click', '#parentBrandSubmit', function () {
                var formData = new FormData($("#parentBrandFormSubmit")[0]);
                $.ajax({
                    url: "{{ route('superadmin.product.brand.modal.store') }}",
                    type: 'post',
                    dataType: 'json',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data === 'true') {
                            $('.invalid-response').text('');
                            $('#ParentBrandCreateModal').modal('hide');
                            var parentBrandDataTable = $('#parentBrandDataTable').dataTable();
                            parentBrandDataTable.fnDraw(false);
                            toastr.success('Parent Brand Create Success');
                        }
                    },
                    error: function(data) {
                        console.log(data);
                        $('.invalid-response').text('');
                        var errors = data.responseJSON.errors;
                        var error = data.responseJSON.error;
                        if (error){
                            $('#invalid-slug').text(error);
                        }
                        $.each(errors, function(index, value) {
                            $('#invalid-' + index).text(value[0]);
                        });
                    }
                });
            });



        });


    </script>
@endpush
