

<div class="modals-default-cl">
<div class="modal fade partial_payment_modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="appendModelContent">
                    <h4 class="p_c_name"></h4>
                    <p class="sale_pro_item"></p>
                    <div class="row">
                        <div class="col-md-4"><h1>Total: <span class="sale_total"></span></h1>
                        </div>
                        <div class="col-md-4"><h1>Paid: <span class="paid_amount"></span></h1>
                        </div>
                    </div>

                        <div class="form-group">
                            <label for="formGroupExampleInput">Payment Type:</label>
                            <select class="form-group form-control float-left payment_type">
                                <option selected value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="check">Check</option>
                            </select>
                        </div>
                
                    <input type="hidden" class="last_sale_id">
                
                        <div class="appendCardInput" style="display: none">
                
                        </div>
                
                        <div class="appendCheckInput" style="display: none">
                
                        </div>
                
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Pay Amount</label>
                        <input type='text'  class='form-control pay_input_field'>
                    </div>
                
                    <div class="appendGivBackInput">
                        <div class="form-group">
                            <div class="existing-due-amount" style="display:none">0</div>
                            <div class="existing-advance-amount" style="display:none">0</div>
                            <label for="formGroupExampleInput2" class="give_back">Due: 0 <input type="hidden" value="0"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary payment_submit">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</div>

@push('script')
    <script>
        $(".payment_type").on('change', function () {
           var value = $(this).val();

            if (value == 'cash')
            {
                $(".appendCardInput").hide();
                $(".appendCheckInput").hide();
            }else if (value == 'card')
            {
                var html = `<div class="form-group">
                                <label for="formGroupExampleInput2">Card Name</label>
                                <input type='text'  class='form-control card_name'>
                            </div>
                            <div class="form-group">
                                <label for="formGroupExampleInput2">Card Number</label>
                                <input type='text' class='form-control card_number'>
                            </div>`;

                $(".appendCheckInput").hide();
                $(".appendCardInput").show().html(html);
            }else if (value == 'check')
            {
                var html = ` <div class="form-group">
                                <label for="formGroupExampleInput2">Check No</label>
                                <input type='text' class='form-control check_no'>
                            </div>`;

                $(".appendCheckInput").show().html(html);
                $(".appendCardInput").hide();
            }

        });

        $(".pay_input_field").on('change keyup', function () {
            var val = $(this).val();
            if(isNaN(parseFloat(val))){
                val = 0;
            }
            var existing_due = $(".existing-due-amount").text();
            if (parseFloat(val) > parseFloat(existing_due))
            {
                $(".give_back").html(`Give Back: `+(parseFloat(val) - parseFloat(existing_due))+` <input type="hidden" class="back" value="`+(parseFloat(val) - parseFloat(existing_due))+`">`);
            }else{
                $(".give_back").html(`Due: `+(parseFloat(existing_due) - parseFloat(val))+` <input type="hidden" class="due" value="`+(parseFloat(existing_due) - parseFloat(val))+`">`);
            }
        });

        // payment modal
        $(".payment_submit").on('click', function () {
            var payment_type = $(".payment_type :selected").val();
                if (payment_type == 'cash')
                {
                   var card_name = null;
                   var card_number = null;
                   var check_no = null;
                }else if(payment_type == 'card')
                {
                    var card_name = $(".card_name").val();
                    var card_number = $(".card_number").val();
                    var check_no = null;
                }else if(payment_type == 'check')
                {
                    var card_name = null;
                    var card_number = null;
                    var check_no = $(".check_no").val();
                }
                var pos_customer_id = $(".p_pos_customer_id").val();
                var last_sale_id = $(".last_sale_id").val();
                var payment_amount = $(".pay_input_field").val();
                var existing_due = $(".existing-due-amount").text();
                var total = $(".sale_total").text();
                var due = 0;
                if ( (parseFloat(payment_amount) > parseFloat(existing_due)))
                {
                    var due = 0;
                    var back = $(".back").val();
                    var status = "FP";
                }else if( parseFloat(payment_amount) == parseFloat(existing_due) ){
                    var due = 0;
                    var back = 0;
                    var status = 'FP'
                }else{
                    var due = $(".due").val();
                    var back = 0;
                    var status = 'PP'
                }
                if (payment_type == null)
                {
                    toastr.error('Please select Payment Type');
                    return false;
                }
                // console.log({
                //         last_sale_id: last_sale_id,
                //         pos_customer_id: pos_customer_id,
                //         card_name: card_name,
                //         card_number: card_number,
                //         check_no: check_no,
                //         payment_amount: payment_amount,
                //         payment_type: payment_type,
                //         due: due,
                //         back: back,
                //         status: status,
                //         final_total: total,
                //     });
                //     return false;
                $.post("{{ route('admin.sale.payment.store.with_ajax') }}",
                    {
                        last_sale_id: last_sale_id,
                        pos_customer_id: pos_customer_id,
                        card_name: card_name,
                        card_number: card_number,
                        check_no: check_no,
                        payment_amount: payment_amount,
                        payment_type: payment_type,
                        due: due,
                        back: back,
                        status: status,
                        final_total: total,
                    },
                    function (res) {
                    if (res == 'payment_success')
                    {
                        toastr.success('Payment Success !');
                        $(".payment_type").val('');
                        $(".p_pos_customer_id").val('');
                        $(".last_sale_id").val('');
                        $(".pay_input_field").val('');
                        $(".give_back").html('');
                        $(".sale_total").text('');
                        $(".appendCardInput").hide();
                        $(".appendCheckInput").hide();
                        $(".partial_payment_modal").modal('hide');
                        location.reload();
                    }
                });
        })

    </script>
@endpush

