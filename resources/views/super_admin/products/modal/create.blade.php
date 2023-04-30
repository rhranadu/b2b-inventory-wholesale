<div class="modal fade" id="parentProductCreateModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Parent Manufacturer Create</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive parentProductTable">
                    <form method="POST" action="" accept-charset="UTF-8"
                          enctype="multipart/form-data" id="parentManufacturerFormSubmit">
                        @csrf

                        <div class="form-row align-items-end">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Name
                                        <span style="color: red; font-size: 18px">
                                                    <sup>*</sup>
                                                </span>
                                    </label>
                                    <input
                                        class="form-control" id="name" value="{{ old('name') }}"
                                        autocomplete="off"
                                        name="name" type="text">
                                    <span id="invalid-name" class="invalid-response text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input
                                        class="form-control" id="email" value="{{ old('email') }}"
                                        autocomplete="off"
                                        name="email" type="email">
                                    <span id="invalid-email" class="invalid-response text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row align-items-end">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input
                                        class="form-control" id="phone" value="{{ old('phone') }}"
                                        autocomplete="off"
                                        name="phone" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label for="country_name">Country name of Manufacturer
                                        <span
                                            style="color: red; font-size: 18px">
                                                    <sup>*</sup>
                                                </span>
                                    </label>
                                    <select
                                        name="country_name" id="country_name" class="form-control"
                                        value="{{ old('country_name') }}">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->name }}">{{ ucfirst($country->name) }}</option>
                                        @endforeach
                                    </select>
                                    <span id="invalid-country_name" class="invalid-response text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Description
                                <span
                                    style="color: red; font-size: 18px">
                                            <sup></sup>
                                        </span>
                            </label>
                            <textarea
                                class="form-control date" id="description" name="description" cols="50"
                                rows="10">{{ old('description') }}</textarea>
                        </div>


                        <div class="form-row align-items-end">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="website">Website Link</label>
                                    <input
                                        class="form-control" id="website" value="{{ old('website') }}"
                                        autocomplete="off"
                                        name="website" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label >Manufacturer Image
                                    </label>
                                    <input type="file" class="form-control" name="image" id="">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input
                                                value="1" type="checkbox" id="addedItemCheckbox" name="status"
                                                class="i-checks">
                                            <span></span>
                                            Status
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <button type="button" class="btn btn-success waves-effect" id="parentManufacturerSubmit">Save</button>
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

            $(document).off('click', '#parentManufacturerSubmit').on('click', '#parentManufacturerSubmit', function () {
                var formData = new FormData($("#parentManufacturerFormSubmit")[0]);
                $.ajax({
                    url: "{{ route('superadmin.manufacturer.modal.store') }}",
                    type: 'post',
                    dataType: 'json',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data === 'true') {
                            $('.invalid-response').text('');
                            $('#parentProductCreateModal').modal('hide');
                            var parentProductDataTable = $('#parentProductDataTable').dataTable();
                            parentProductDataTable.fnDraw(false);
                            toastr.success('Manufacturer Create Success');
                        }
                    },
                    error: function(data) {
                        $('.invalid-response').text('');
                        var errors = data.responseJSON.errors;
                        var error = data.responseJSON.error;
                        if (error){
                            $('#invalid-email').text(error);
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
