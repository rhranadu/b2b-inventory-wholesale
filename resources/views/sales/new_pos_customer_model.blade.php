<div class="modals-default-cl">
    <div class="modal fade pos_customer_model" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                    <div class="modal-body pos_customer_creat_input_all">
                        <h1 class="text-center" style="margin-bottom: 30px;">New POS Customer Create</h1>
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input class="form-control name" value="{{ old('name') }}" autocomplete="off" name="name" type="text">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control email" id="" value="{{ old('email') }}" autocomplete="off" name="email" type="email">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input class="form-control phone" id="" value="{{ old('phone') }}" autocomplete="off" name="phone" type="text">
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea name="address" class="form-control address" id=""></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default create_cus_btn" >Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"> Close</button>
                    </div>
            </div>
        </div>
    </div>
</div>

@push('script')

    <script>

        $(".create_cus_btn").on('click', function (e) {
            e.preventDefault();
            var findActiveClasee = $(".nav-tabs").find('.active').find('a').attr('href');
            var name = $(".name").val();
            var email = $(".email").val();
            var phone = $(".phone").val();
            var address = $(".address").val();
            $.post("{{ route('admin.poscustomer.store.with_ajax') }}", {name: name, email:email, phone:phone, address:address}, function (res) {
                if (res=='success')
                {
                    $(".tab-content").find(findActiveClasee).find(".pos_customer_reload_with_ajax:visible").load(location.href + " .pos_customer_reload_with_ajax");
                    toastr.success('POS Customer created success');
                    $(".pos_customer_creat_input_all").find('input').val('');
                    $(".address").val('');
                  $(".pos_customer_model").modal('hide')
                }

                if (res.errors)
                {
                    for (var i = 0; i < res.errors.length; i++)
                    {
                        toastr.error(res.errors[i])
                    }
                }

            })
        })

    </script>


@endpush
