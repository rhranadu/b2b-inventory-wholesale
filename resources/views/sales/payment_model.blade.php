

<div class="modals-default-cl">
<div class="modal fade payment_model" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #81abe0 !important;">
                <h5 class="modal-title" style="color:white;">Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                    <i aria-hidden="true" class="fa fa-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="appendModelContent">
                    <h4 class="p_c_name"></h4>
                    <p class="sale_pro_item"></p>
                    <h1>Total: <span class="sale_total"></span></h1>
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
                            <label for="formGroupExampleInput2" class="give_back">Give Back: 0 <input type="hidden" value="0"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary payment_submit">Received</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</div>



