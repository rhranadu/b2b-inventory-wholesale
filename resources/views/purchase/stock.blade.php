@extends('layouts.crud-master')
@section('title', 'Product Stock')

@push('css')
@endpush

@section('main_content')

    <!--begin::Entry-->
    <div class="flex-column-fluid">
        <!--begin::Container-->

        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container-fluid">
                <div class="card card-custom min-h-500px" id="kt_card_1">
                    <div class="card-body">

                        @include('component.message')
                        <div id="ui-view" data-select2-id="ui-view">
                            <div>
                                <div class="card">
                                    <div class="card-header">Purchases Stock form
                                        {{-- <strong>#{{ $purchase->invoice_no }}</strong>
                                         <div>Date: {{ $purchase->date }}</div>--}}
                                        <div class="">
                                            {{-- <a class="btn btn-sm btn-secondary float-right mr-1 d-print-none" href="#" onclick="javascript:window.print();" data-abc="true">
                                                 <i class="fa fa-print"></i> Print</a>
                                             <a class="btn btn-sm btn-info float-right mr-1 d-print-none" href="#" data-abc="true">
                                                 <i class="fa fa-save"></i> Save</a>--}}
                                        </div>
                                        {{-- @if(isset($purchase->purchasePayment->last()->status))
                                             @if($purchase->purchasePayment->last()->status == 0)
                                                 <span class="badge" style="background: red">Have a Due ({{ $purchase->purchasePayment->last()->due_amount }})</span>
                                             @else
                                                 <span class="badge" style="background: #00c292">Paid</span>
                                             @endif
                                         @else
                                             <span class="badge" style="background:red">Full Due</span>
                                         @endif--}}
                                    </div>
                                    <div class="card-body" id="reloadWithAjax">
                                        <div class="row" style="padding: 20px;">
                                            <div class="col-sm-4">
                                                <input type="hidden" id="purchases_id" data-purchases_id="{{ $purchase->id }}">
                                                @php
                                                    $product_stock = \App\ProductStock::where('purchase_id', $purchase->id)->first();
                                                @endphp
                                                <h6 class="mb-3">Supplier Details:</h6>
                                                <div><h5>{{ $purchase->purchaseSupplier->name }},</h5></div>
                                                <div><b>Email:</b> {{ $purchase->purchaseSupplier->email }}</div>
                                                <div><b>Phone:</b> {{ $purchase->purchaseSupplier->mobile }}</div>
                                                <div><b>Address:</b> {{ $purchase->purchaseSupplier->details }}</div>
                                            </div>
                                            <div class="col-sm-4"></div>
                                            <div class="col-sm-4">
                                                <strong>Invoice No : #{{ $purchase->invoice_no }}</strong>
                                                <div>Date: {{ $purchase->date }}</div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="wantToStore">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" width="50">Product Name</th>
                                                    <th class="text-center" width="50">Attribute</th>
                                                    <th class="text-center" width="50">PQ</th>
                                                    <th class="text-center" width="50">Received</th>
                                                    <th class="text-center" width="50">Store Quantity</th>
                                                    <th class="text-center" width="50">Purchases Price</th>
                                                    <th class="text-center" width="50">Total</th>
                                                    <th class="text-center" width="50">Warehouse</th>
                                                    <th class="text-center" width="50">Barcode Generate</th>
                                                    <th class="text-center" width="50">Status</th>
                                                    <th class="text-center" width="50">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($purchase->purchaseDetail as $item)
                                                    <tr class="trParent">
                                                        <td class="text-left" width="50">
                                                            {{ $item->product['name'] }}
                                                        </td>
                                                        <td class="text-left" width="50">
                                                            @if ($item->purchaseAttributeDetails)
                                                                @foreach($item->purchaseAttributeDetails as $itemAttribute)
                                                                <ul>
                                                                    @if(isset($itemAttribute->attribute_name))
                                                                    <li>
                                                                        {{$itemAttribute->attribute_name . "-" . $itemAttribute->attribute_map_name}}
                                                                    </li>
                                                                    @endif
                                                                </ul>
                                                                @endforeach
                                                            @endif

                                                        </td>
                                                        <td class="text-center" width="50">{{ $item->quantity }}</td>
                                                        @php
                                                            $stored = \App\StockDetail::where(['product_stock_id' =>$product_stock->id ?? '','product_id' =>$item->product_id,'purchase_detail_id' =>$item->id])->get();
                                                        @endphp
                                                        <td class="text-center" width="50"><span
                                                                class="badge badge-success alreadyStored">{{ $stored->sum('quantity') }}</span>
                                                        </td>
                                                        @if($item->status != 'DC')
                                                            @if($item->status != 'FR')

                                                                <td class="text-center" width="50"><input class="form-control wantToQuantity"
                                                                                          type="text" value="@if ($stored->sum('quantity') > 0){{ $item->quantity - $stored->sum('quantity') }} @else {{$item->quantity}} @endif "></td>
                                                                <td class="text-center" width="50"><input class="form-control purchasesPrice"
                                                                                          type="text" value="{{ $item->price }}"></td>
                                                                <td class="text-center" width="50">
                                                                    @if(isset($item->quantity) && isset($item->price))
                                                                    <input readonly
                                                                                          class="form-control-sm total" type="text" value="@if ($stored->sum('quantity') > 0){{ ($item->quantity - $stored->sum('quantity'))*$item->price }} @else  {{$item->quantity*$item->price}} @endif">
                                                                    @else
                                                                        <input readonly
                                                                               class="form-control-sm total" type="text">
                                                                    @endif
                                                                </td>
                                                                <td class="text-center" width="50">
                                                                    <select id="warehouse_id" class="form-control warehouse_id">
                                                                        <option value="">Select</option>
                                                                        @foreach($warehouses as $warehouse)
                                                                            <option
                                                                                value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <span>
                                                                        <select id="" class="form-control warehouse_detail_id">
                                                                        <option value="">Select Section</option>

                                                                        </select>
                                                                    </span>
                                                                </td>
                                                                <td class="text-center" width="50">
                                                                    <button type="button" data-purchase_details_id="{{ $item->id }}"
                                                                            class="btn btn-primary btn-xs barcodeGenerate">Manual</button>
                                                                    <button type="button" data-purchase_details_id="{{ $item->id }}"
                                                                            class="btn btn-primary btn-xs barcodeGenerateAuto mt-1">Auto</button>
{{--                                                                    <input class="form-control barcode" name='barcode' type="text">--}}
{{--                                                                    <div id="barcodeGenerateStatus"></div>--}}
                                                                    <span class="badge badge-success barcodeGenerateStatus mt-1"
                                                                          title="Barcode Generate Type">Auto Generated</span>
                                                                </td>
                                                            @else
                                                                <td colspan="5" class="text-center"><span class="badge badge-success"
                                                                                                          >Full Received</span>
                                                                </td>
                                                            @endif
                                                        @else
                                                            @if($stored->sum('quantity') === 0)
                                                                <td colspan="5" class="text-center"><span class="badge badge-danger"
                                                                                                          >Finished</span>
                                                                </td>
                                                            @else
                                                                <td colspan="5" class="text-center"><span class="badge badge-warning"
                                                                                                          >Purtial Recived</span>
                                                                </td>
                                                            @endif
                                                        @endif
                                                        @if($item->status == 'NY')
                                                            <td class="center getStatusFromAjax"><span class="badge badge-danger"
                                                                                                       title="Not Yet">{{ ($item->status) }}</span>
                                                            </td>
                                                        @endif
                                                        @if($item->status == 'DC')
                                                            <td class="center getStatusFromAjax"><span class="badge badge-danger"
                                                                                                       title="Discard"> Discard </span>
                                                            </td>
                                                        @endif
                                                        @if($item->status == 'FR')
                                                            <td class="center getStatusFromAjax"><span class="badge badge-success"
                                                                                                       title="Full Received">{{ ($item->status) }}</span>
                                                            </td>
                                                        @endif
                                                        @if($item->status == 'PR')
                                                            <td class="center getStatusFromAjax"><span class="badge badge-warning"
                                                                                                       title="Partial Received">{{ ($item->status) }}</span>
                                                            </td>
                                                        @endif
                                                        <td class="text-center">
                                                            <div class="btn-group">
                                                                <button class="btn btn-primary btn-icon detailsStock"
                                                                        data-toggle="tooltip"
                                                                        data-placement="auto"
                                                                        data-original-title="Details"
                                                                        data-purchase_details_id="{{ $item->id }}"
                                                                        data-product_id="{{ $item->product_id }}"
                                                                        data-product_attribute_id="{{ $item->attribute_id }}"
                                                                        data-product_attribute_map_id="{{ $item->product_attribute_map_id }}">
                                                                        <i class="fa fa-info"></i></button>
                                                                <br>
                                                                @if($item->status != 'DC')
                                                                    @if($item->status != 'FR')
                                                                        <button class="btn btn-success btn-icon singleRowstoreBtn"
                                                                                data-item_id="{{ $item->id }}"
                                                                                data-toggle="tooltip"
                                                                                data-placement="auto"
                                                                                data-original-title="Store"><i class="fa fa-save"></i></button>
                                                                        <br>
                                                                        <a class="btn btn-danger btn-icon discard_status"
                                                                           href="{{ route('admin.purchase.discard_status', $item->id) }}"
                                                                           data-toggle="tooltip"
                                                                           data-placement="auto"
                                                                           data-original-title="Discard"><i class="fa fa-trash"></i></a>
                                                                        <br>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mt-5">
                                    <div class="card-header">Purchases Additional Expenses
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('admin.purchase.additional_expense') }}" accept-charset="UTF-8">
                                            @csrf
                                            <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                            <div class="row">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-highlight">
                                                    <thead>
                                                    <tr>
                                                        <th width="100">Particulars Name</th>
                                                        <th width="50">Amount</th>
                                                        <th width="50"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="appendNewItemSection">
                                                    <tr>
                                                        <td class="particulars_section">
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control particular_name" id="particular_name">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="additional_amount_section">
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control additional_amount" id="additional_amount" min="0">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" id="addnewItemBtn" class="btn btn-success font-weight-bold"><i
                                                                    class="fa fa-plus-circle"></i>Add Into List</button>
                                                        </td>

                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="row clearfix" style="margin-top:20px">
                                            <div class="pull-right col-12">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                    <tr>
                                                        <td class="text-center">
                                                            <button type="submit" class="btn"
                                                                    style="background: #00c292; color: #f0f0f0">Additional Expenses Create
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        </form>
                                        @if (isset($purchase_additional_expenses) && count($purchase_additional_expenses) > 0)
                                            <div class="table-responsive" id="refreshbody">
                                                <input type="hidden" id="unique_particular_name" value="">
                                                <input type="hidden" id="unique_amount" value="">


                                                <table class="table table-hover table-bordered table-condensed "
                                                       id="">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">SI</th>
                                                        <th class="text-center particular_name_view">Particular Name</th>
                                                        <th class="text-center particular_amount_view">Amount</th>
                                                        <th class="text-center">Created By</th>
                                                        <th class="text-center">Updated By</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($purchase_additional_expenses as $purchase_additional_expense)
                                                        <tr class="text-center" id="{{$purchase_additional_expense->id}}">
                                                            <td>{{ $loop->index + 1 }}</td>
                                                            <td class="editableColumnParticularName">{{ $purchase_additional_expense->particular }}</td>
                                                            <td class="editableColumnAmount">{{ $purchase_additional_expense->amount }}</td>
                                                            <td>{{ isset($purchase_additional_expense->createdBy->name) ? $purchase_additional_expense->createdBy->name : 'N/A' }}</td>
                                                            <td>{{ isset($purchase_additional_expense->updatedBy->name) ? $purchase_additional_expense->updatedBy->name : 'N/A' }}</td>

                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button"
                                                                            id="{{$purchase_additional_expense->id}}"
                                                                            class="btn btn-sm btn-success waves-effect btn-icon btnSaveExpense"
                                                                            data-toggle="tooltip" data-placement="auto" title=""
                                                                            data-original-title="Save"><i class="fas fa-check"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                            id="{{$purchase_additional_expense->id}}"
                                                                            class="btn btn-sm btn-warning waves-effect btn-icon btnEditExpense"
                                                                            data-toggle="tooltip" data-placement="auto" title=""
                                                                            data-original-title="EDIT"><i class="fas fa-pencil-alt"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                            id="{{$purchase_additional_expense->id}}"
                                                                            class="btn btn-sm btn-danger waves-effect btn-icon btnRemoveExpense"
                                                                            data-toggle="tooltip" data-placement="auto" title=""
                                                                            data-original-title="DELETE"><i class="fa fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="ModalBarcode" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"
         aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog  modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-link" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Barcode Generate</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="val_type_generate" class="k-checkbox" value="">
                    <input type="hidden" id="unique_purchase_detail_id" class="k-checkbox" value="">
                    <input type="hidden" id="unique_product_id" class="k-checkbox" value="">
                    <input type="hidden" id="unique_attribute_id" class="k-checkbox" value="">
                    <input type="hidden" id="unique_product_attribute_map_id" class="k-checkbox" value="">
                    <input type="hidden" id="unique_qty" class="k-checkbox" value="">
                    <br>
                    <table class="table table-light-info" >
                        <thead>
{{--                        <tr>--}}
{{--                            <th class="span6" scope="col"><input type="checkbox" name="check_manual_generate" id="check_manual_generate" class="k-checkbox"> Manual </th>--}}
{{--                            <th class="span4" scope="col"><input type="checkbox" name="check_auto_generate" id="check_auto_generate" class="k-checkbox"> Auto</th>--}}
{{--                            <th class="span4" scope="col"> </th>--}}
{{--                        </tr>--}}
                            <tr>
                                <th class="span6" scope="col" id="totalStoreQty">  </th>
                                <th class="span4" scope="col" id="givenBarcode"> </th>
                            </tr>
                        </thead>
                    </table>
                    <p id="validateMsg" style="color: #9d0006"><b></b></p>
                    <div class="appendBarcodeType"></div>
                    <div id="tag-list" style="display:none"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="save_barcode_data" onclick="" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default close-link" data-dismiss="modal">Close</button>
                </div>

        </div>
        </div>
    </div>
    <!-- Search Engine End-->
    <div class="modal fade" id="StockDetailsModel" role="dialog">
        <div class="modal-dialog modals-default">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h2 style="padding: 10px; border-bottom: 2px solid #dddd;">Stock Details</h2>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered table-condensed">
                            <tbody id="modelTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        var global_barcode_array = {};

        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".alert").delay(5000).slideUp(300);

            var count_item = '{{count($purchase->purchaseDetail)}}';

            $("#wantToStore tr").find('td').each(function() {
                var currentRow=$(this).closest("tr");
                var val = currentRow.find('.alreadyStored').html();
                if(val > 0){
                    currentRow.css('background-color','lightcyan');
                }
            });

            // start==> Additional Expense

            $('.btnSaveExpense').hide();

            $('.btnEditExpense').click(function () {
                var currentRow=$(this).closest("tr");
                currentRow.find('.btnEditExpense').hide();
                currentRow.find('.btnSaveExpense').show();
                $(this).parents('tr').find('td.editableColumnParticularName').each(function() {
                    var html = $(this).html();
                    var input = $('<input class="edit_particular_name form-control" type="text" />');
                    input.val(html);
                    $(this).html(input);
                });
                $(this).parents('tr').find('td.editableColumnAmount').each(function() {
                    var html = $(this).html();
                    var input = $('<input class="edit_particular_amount form-control" type="text" />');
                    input.val(html);
                    $(this).html(input);
                });

            });

            $('.btnSaveExpense').click(function () {
                var currentRow=$(this).closest("tr");
                var val_purchase_additional_id = $(this).attr('id');
                var val_particular_name = currentRow.find('.edit_particular_name').val();
                var val_particular_amount = currentRow.find('.edit_particular_amount').val();
                // $('#unique_particular_name').val(val_particular_name);
                // $('#unique_amount').val(val_particular_amount);
                $.post("{{ route('admin.purchase.additional_expense.update') }}", {
                    val_purchase_additional_id: val_purchase_additional_id,
                    val_particular_name: val_particular_name,
                    val_particular_amount: val_particular_amount,
                }, function (response) {
                    if (response.success == true){
                        // $("#refreshbody").load(location.href + " #refreshbody>*", "");
                        currentRow.find('.btnSaveExpense').hide();
                        currentRow.find('.btnEditExpense').show();
                        currentRow.find('.editableColumnParticularName').empty();
                        currentRow.find('.editableColumnAmount').empty();
                        currentRow.find('.editableColumnParticularName').html(val_particular_name);
                        currentRow.find('.editableColumnAmount').html(val_particular_amount);
                        toastr.success('Purchase Additional Expense Updated Sucessfully!');
                    }
                    // $('#unique_particular_name').val('');
                    // $('#unique_amount').val('');
                });

            });

            $('.btnRemoveExpense').click(function () {
                var currentRow=$(this).closest("tr");
                var val_purchase_additional_id = $(this).attr('id');
                $.post("{{ route('admin.purchase.additional_expense.delete') }}", {
                    val_purchase_additional_id: val_purchase_additional_id,
                }, function (response) {
                    if (response.success == true){
                        currentRow.remove();
                        toastr.success('Purchase Additional Expense Deleted Sucessfully!');
                    }
                });
            });
            // end==> Additional Expense


            // start===> finaly add new item in our collection
            $("#addnewItemBtn").click(function (e) {
                e.preventDefault();
                var particular_name = $('#particular_name').val();
                var additional_amount = $('#additional_amount').val();

                if (particular_name && additional_amount > 0 ) {
                    var tbl = '\n' +
                        '<tr id="removeThisItem" class="everyNewSingleItem">\n' +
                        '     <td>\n' +
                        '         <span for="">' + particular_name + '</span>\n' +
                        '         <input type="hidden" class="uniqueProduct_id" data-addedProduct_id="' + particular_name + '" name="store_particular_name[]" value="' + particular_name + '">\n' +
                        '     </td>\n' +
                        '     <td>\n' +
                        '         <span for="">' + additional_amount + '</span>\n' +
                        '          <input type="hidden" name="store_additional_amount[]" value="' + additional_amount + '">\n' +
                        '     </td>\n' +

                        '     <td style="padding-top: 9px;">\n' +
                        '         <a href="#0" id="removeThis" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>\n' +
                        '     </td>\n' +
                        '</tr>';
                        $("#appendNewItemSection").append(tbl);
                        $('.particulars_section').find('.remove_this_appended').remove();
                        $('.additional_amount_section').find('.remove_this_appended').remove();
                        $("#additional_amount").val('');
                        $("#particular_name").val('');

                } else {
                    toastr.error('Please Fill Up all field with valid value')
                }

            });


            // remove item with calculation
            $(document).on("click", "#removeThis", function () {
                $(this).parents('#removeThisItem').remove();
            });



                // $(".barcodeGenerate").prop("disabled",true);
                // $(".barcodeGenerateAuto").prop("disabled",true);


            // start==> calculation every single row with(price and quantity)
            $("table tbody .trParent").on('keyup', '.wantToQuantity, .purchasesPrice', function () {
                var parent = $(this).parents('.trParent');
                var qty = parent.find('.wantToQuantity').val();
                var price = parent.find('.purchasesPrice').val();
                if (qty && price) {
                    parent.find('.total').val(parseFloat(price) * parseInt(qty));
                }
            });

            //start===> generate dynamic barcode
            $("table tbody .trParent").on('keyup', '.wantToQuantity', function () {
                var parent = $(this).parents('.trParent');
                var qty = parent.find('.wantToQuantity').val();
                var barcodeGenerateManual = parent.find('.barcodeGenerate');
                var barcodeGenerateAuto = parent.find('.barcodeGenerateAuto');
                if (qty > 0) {
                    barcodeGenerateManual.prop("disabled",false);
                    barcodeGenerateAuto.prop("disabled",false);
                } else {
                    barcodeGenerateManual.prop("disabled",true);
                    barcodeGenerateAuto.prop("disabled",true);
                }

            });

            $("table tbody .trParent").on('change', '.warehouse_id', function () {
                var parent = $(this).parents('.trParent');
                var warehouse_id = parent.find('.warehouse_id').val();
                var selected_warehouse_detail_id = parent.find('.warehouse_detail_id');
                $.post("{{ route('admin.products.warehouse_type.check') }}", {
                    warehouse_id: warehouse_id,
                }, function (res) {
                    var parent_sections = res.parent_sections
                    selected_warehouse_detail_id.empty();
                    var output = '<option value="">Select Section</option>';
                    $.each(parent_sections, function (index, parent_section) {
                        var id = parent_section['id'];
                        var name = parent_section['section_name'];
                        output += '<option value="'+id+'">'+name+'</option>';
                    });
                    selected_warehouse_detail_id.append(output);
                });



            });

            function getDynamicBarcode(qty, parent) {
                var setInput = '';
                for (i = 2; i <= qty; i++) {
                    setInput += "<input type='text' name='barcode'  class='form-control barcode'>";
                }
                // parent.find('.appendBarcode').html(setInput);
                // if (qty === 0) {
                //     parent.find('.appendBarcode').append('<div class="input-group-addon reset"><input type="checkbox" class="i-checks addedItemCheckbox"> Reset</div>');
                // } else if (qty > 1) {
                //     parent.find('.appendBarcode').append('<div class="input-group-addon"><input type="checkbox" class="i-checks addedItemCheckbox">All Same</div>');
                // }

            }

            // start==> remove editable row barcode
            $("table tbody .trParent").on('click', '.addedItemCheckbox', function () {
                var parent = $(this).parents('.trParent');
                var reset = parent.find('.reset').text();
                if (reset) {
                    var setQty = parent.find('.wantToQuantity').val();
                } else {
                    setQty = 0;
                }
                getDynamicBarcode(setQty, parent)
            });
            // end==> remove editable row barcode
            //end===> generate dynamic barcode


            // end==> calculation every single row with(price and quantity)  // start==> calculation every single row with(price and quantity)
            $("table tbody .trParent").on('click', '.singleRowstoreBtn', function (e) {
                KTApp.blockPage({
                    overlayColor: '#000000',
                    state: 'danger',
                    message: 'Please wait some times, backend processing takes some time. ...'
                });
                var parent = $(this).parents('.trParent');
                var generateStatusType = parent.find('.barcodeGenerateStatus').html();
                var purchases_id = $("#purchases_id").data('purchases_id');
                var val_purchase_detail_id = $(this).data('item_id');


                var val_type_generate = $("#val_type_generate").val();
                var warehouse_id = parent.find('.warehouse_id').val();
                var warehouse_detail_id = parent.find('.warehouse_detail_id').val();
                var qty = parent.find('.wantToQuantity').val();
                var price = parent.find('.purchasesPrice').val();
                var total = parent.find('.total').val();
                var item_id = parent.find('.singleRowstoreBtn').data('item_id');
                if (global_barcode_array.hasOwnProperty(val_purchase_detail_id)){
                    if ( global_barcode_array[val_purchase_detail_id].length >0){
                     var filterBarcode = Object.assign({}, global_barcode_array[val_purchase_detail_id]);
                    }
                    else{
                        var filterBarcode = null;
                    }
                }
                else{
                      var filterBarcode = null;
                }
                if(generateStatusType == 'Manually Generated'){
                    if(!isEmpty(global_barcode_array[val_purchase_detail_id]) && global_barcode_array[val_purchase_detail_id].length < qty ){
                        KTApp.unblockPage();
                        let inserted_barcode = global_barcode_array[val_purchase_detail_id].length;
                        toastr.error('Please insert total number of barcodes currently inserted =' +inserted_barcode+ '!');
                        return false;
                    }
                }
                if (warehouse_id !== '' && warehouse_id !== '0' && qty !== '' && qty !== '0' && price !== '' && price !== '0' && total !== '' && total !== '0' && item_id !== '' && item_id !== '0' && filterBarcode !== '0' && filterBarcode !== '') {
                    if (total !== 'NaN') {
                        $.post("{{ route('admin.purchase.single_product.stock') }}", {
                            warehouse_id: warehouse_id,
                            warehouse_detail_id: warehouse_detail_id,
                            quantity: qty,
                            price: price,
                            total: total,
                            purchases_id: purchases_id,
                            val_type_generate: val_type_generate,
                            item_id: item_id,
                            filterBarcode: filterBarcode,
                        }, function (res) {
                            if (res.barcode_exist) {
                                KTApp.unblockPage();
                                toastr.error(" Barcode already exist");
                            }
                            if (res.true) {
                                if(!isEmpty(global_barcode_array[val_purchase_detail_id])){
                                    global_barcode_array[val_purchase_detail_id] = [];
                                }
                                if (res.true.poolFailed){
                                    KTApp.unblockPage();
                                    toastr.success('Stored success and product pool save failed');
                                }else{
                                    KTApp.unblockPage();
                                    toastr.success('Stored success');
                                }
                                parent.find('.warehouse_id').val('');
                                parent.find('.wantToQuantity').val('');
                                parent.find('.purchasesPrice').val('');
                                parent.find('.total').val('');
                                // parent.find('.appendBarcode').html('');
                                // parent.find("input[name='barcode']").val('');
                                parent.find('.alreadyStored').text(res.true);
                                if (res.status == 'FR') {
                                    parent.find('.getStatusFromAjax').html("<span class='badge badge-success'>FR</span>");
                                    parent.find('.wantToQuantity').hide();
                                    parent.find('.purchasesPrice').hide();
                                    parent.find('.total').hide();
                                    parent.find('.warehouse_id').hide();
                                    parent.find('.barcode').hide();
                                    parent.find('.singleRowstoreBtn').hide();
                                    parent.find('.discard_status').hide();
                                } else {
                                    parent.find('.getStatusFromAjax').html("<span class='badge badge-warning'>PR</span>");
                                    window.location.reload();
                                }
                                if(count_item == 1){
                                    var redirect_url = "{{route('admin.purchase.index')}}"
                                    window.location.replace(redirect_url);
                                }

                            } else if (res === 'input_quantity_up') {
                                KTApp.unblockPage();
                                toastr.error('Input quantity upper to purchases quantity')
                            } else if (res === 'quantity_up_to_stock') {
                                KTApp.unblockPage();
                                toastr.error('quantity upper')
                            } else if (res.NotAllowToStock) {
                                KTApp.unblockPage();
                                toastr.error('This item not allow to stock')
                            }
                        });
                    } else {
                        KTApp.unblockPage();
                        parent.find('.total').css('border', '1px solid red');
                        toastr.error('Total field\'s value is not valid ')
                    }
                }
                else {
                    KTApp.unblockPage();
                    toastr.error('Please fill-up required field with valid value');
                }
            });
            // end==> calculation every single row with(price and quantity)


            // show modal with details
            $("table tbody .trParent").on('click', '.detailsStock', function () {
                var parent = $(this).parents('.trParent');
                var product_id = parent.find('.detailsStock').data('product_id');
                var product_attribute_id = parent.find('.detailsStock').data('product_attribute_id');
                var product_attribute_map_id = parent.find('.detailsStock').data('product_attribute_map_id');
                var purchases_id = $("#purchases_id").data('purchases_id');

                $.get("{{ route('admin.single_product.stock_details_info') }}", {
                    product_id: product_id,
                    product_attribute_id: product_attribute_id,
                    product_attribute_map_id: product_attribute_map_id,
                    purchases_id: purchases_id,
                }, function (res) {
                    if (res.true) {
                        $("#modelTableBody").html(res.true);
                        $("#StockDetailsModel").modal('show');
                    } else {
                        toastr.error('There are no details')
                    }
                });
            });
            $("table tbody .trParent").off('click', '.barcodeGenerateAuto').on('click', '.barcodeGenerateAuto', function () {
                var parent = $(this).parents('.trParent');
                var unique_purchase_detail_id = $(this).data('purchase_details_id');
                global_barcode_array[unique_purchase_detail_id] = [];
                parent.find('.barcodeGenerateStatus').html('Auto Generated');
                $("#tag-list").html('');
            });
                $(document).off('click', '.close-link').on('click', '.close-link', function () {
                   var unique_purchase_detail_id = $('#unique_purchase_detail_id').val();
                    $('.barcodeGenerate').each(function() {
                        var purchase_details_id = $(this).data('purchase_details_id');
                        if(unique_purchase_detail_id == purchase_details_id){
                            if(isEmpty(global_barcode_array[unique_purchase_detail_id])){
                                var parent = $(this).parents('.trParent');
                                parent.find('.barcodeGenerateStatus').html('Auto Generated');
                            }
                        return;
                        }
                    });
                });
                // Start of Barcode Generate part
            $("table tbody .trParent").off('click', '.barcodeGenerate').on('click', '.barcodeGenerate', function () {

                var parent = $(this).parents('.trParent');
                var qty = parent.find('.wantToQuantity').val();
                var purchase_details_id = parent.find('.detailsStock').data('purchase_details_id');
                $('#unique_purchase_detail_id').val(purchase_details_id);

                var product_id = parent.find('.detailsStock').data('product_id');
                $('#unique_product_id').val(product_id);

                var product_attribute_id = parent.find('.detailsStock').data('product_attribute_id');
                $('#unique_attribute_id').val(product_attribute_id);

                var product_attribute_map_id = parent.find('.detailsStock').data('product_attribute_map_id');
                $('#unique_product_attribute_map_id').val(product_attribute_map_id);
                $('#unique_qty').val(qty);
                var unique_purchase_detail_id = $('#unique_purchase_detail_id').val();
                if(isEmpty(global_barcode_array[unique_purchase_detail_id])){
                global_barcode_array[unique_purchase_detail_id] = [];
                }
                if (qty > 0 ) {
                    // document.getElementById("check_manual_generate").checked = false;
                    // document.getElementById("check_auto_generate").checked = false;
                    $('.appendBarcodeType').html('');
                    $('#validateMsg').html('');
                    $('#givenBarcode').html('');
                    $('#ModalBarcode').modal('show');
                    $('#totalStoreQty').html('<p style="color: #AF0000">Total Store Quantity = '+qty+' </p>');
                    let inserted_barcode = global_barcode_array[unique_purchase_detail_id].length;
                    $('#givenBarcode').html('<p style="color: #AF0000">Total Barcode Inserted = '+inserted_barcode+' </p>');
                    parent.find('.barcodeGenerateStatus').html('Manually Generated');
                    manual_barcode_generate_type();
                }
                else {
                    alert ('Please Insert Store Quantity');
                }
            });

            $("#save_barcode_data").click(function () {
                var qty = $('#unique_qty').val();
                var unique_purchase_detail_id = $('#unique_purchase_detail_id').val();
                if ( global_barcode_array[unique_purchase_detail_id].length < qty){
                    var validateMsg = "Total store quantity is "+qty+" Please Insert total number of Barcodes currently inserted "+global_barcode_array[unique_purchase_detail_id].length+" Barcodes!";
                    $('#validateMsg').html(validateMsg);
                    document.getElementById("save_barcode_data").disabled = true;
                    return false;
                }else {
                    document.getElementById("save_barcode_data").disabled = false;
                    $('#ModalBarcode').modal('hide');
                }
            });


        });

        function manual_barcode_generate_type() {
            $('.appendBarcodeType').html('');
            var setInput = '';
            setInput += "<div class='col-xs-2'><input type='text' id='barcode_row_first' placeholder='Type your barcodes here and press enter' name='barcodeFirst' class='form-control barcodeFirst' ></div>";
            $('.appendBarcodeType').append(setInput);

            var input = document.getElementById("barcode_row_first");
            input.focus();

                // Execute a function when the user releases a key on the keyboard
                input.addEventListener("keyup", function(event) {
                    // Number 13 is the "Enter" key on the keyboard
                    if (event.keyCode === 13) {
                        // Cancel the default action, if needed
                        event.preventDefault();
                        manual_barcode_checking();
                    }
                });
                // Execute a function when the user blur the input field
                input.addEventListener("blur", function(event) {
                        event.preventDefault();
                        manual_barcode_checking();
                });
        }

        function manual_barcode_checking(){
            var input_barcode_val = document.getElementById("barcode_row_first").value;

            var product_id = document.getElementById("unique_product_id").value;


            if(input_barcode_val){
                $.post("{{ route('admin.products.barcode.stocks.check') }}", {
                    product_id: product_id,
                    input_barcode_val: input_barcode_val,
                }, function (res) {

                    var unique_purchase_detail_id = $('#unique_purchase_detail_id').val();
                    if (res.check_barcodes) {
                        var duplicateBarcodeMsg = "Duplicate Barcode found please enter new Barcode!";
                        $('#validateMsg').html(duplicateBarcodeMsg);
                        $("#barcode_row_first").addClass('is-invalid');
                        document.getElementById("save_barcode_data").disabled = true;
                    } else {
                        var qty = $('#unique_qty').val();
                        let unique_barcode_val = $("#barcode_row_first").val();
                        if ( global_barcode_array[unique_purchase_detail_id].length >= qty){
                            var barcodeLimitMsg = "Already inserted the total quantity of barcodes please save!";
                            $('#validateMsg').html(barcodeLimitMsg);
                            $('.appendBarcodeType').html('');
                            document.getElementById("save_barcode_data").disabled = false;
                            return false;
                        }else{
                            if($.inArray(unique_barcode_val,global_barcode_array[unique_purchase_detail_id]) != -1){
                                    var validateMsg = "This Barcode already added!";
                                    $('#validateMsg').html(validateMsg);
                                    $("#barcode_row_first").addClass('is-invalid');
                                    document.getElementById("save_barcode_data").disabled = true;
                                    return false;
                            }
                            $('#validateMsg').html('');
                            $("#barcode_row_first").removeClass('is-invalid');
                                if (unique_barcode_val !== '' ){
                                    global_barcode_array[unique_purchase_detail_id].push(unique_barcode_val);
                                    $("#barcode_row_first").val('');
                                    let inserted_barcode = global_barcode_array[unique_purchase_detail_id].length;
                                    $('#givenBarcode').html('<p style="color: #AF0000">Total Barcode Inserted = '+inserted_barcode+' </p>');
                                    $("#tag-list").show();
                                    var output = '<span class="badge badge-secondary mr-2" data-barcode_id="'+inserted_barcode+'" data-barcode_val="'+unique_barcode_val+'">' +unique_barcode_val+
                                        '&nbsp;<a class="btn-link mr-2 single_barcode_remove" href="#0" data-unique_purchase_detail_id="'+unique_purchase_detail_id+'" data-barcode_val="'+unique_barcode_val+'"> <i class="fa fa-times"></i></a>'+
                                        '</span>';
                                    $("#tag-list").append(output);
                                }
                            document.getElementById("save_barcode_data").disabled = false;
                        }
                    }
                });

            }
        }

        Array.prototype.remove = function() {
            var what, a = arguments, L = a.length, ax;
            while (L && this.length) {
                what = a[--L];
                while ((ax = this.indexOf(what)) !== -1) {
                    this.splice(ax, 1);
                }
            }
            return this;
        };
        $(document).off('click', '.single_barcode_remove').on('click', '.single_barcode_remove', function () {
            var barcode_val = $(this).data('barcode_val');
            var unique_purchase_detail_id = $(this).data('unique_purchase_detail_id');
            var ary = global_barcode_array[unique_purchase_detail_id];
            if($.inArray(barcode_val,global_barcode_array[unique_purchase_detail_id]) != -1){
                ary.remove(barcode_val);
            }
            $(this).parent().remove();
            let inserted_barcode = global_barcode_array[unique_purchase_detail_id].length;
            $('#givenBarcode').html('')
            if(inserted_barcode > 0){
                $('#givenBarcode').html('<p style="color: #AF0000">Total Barcode Inserted = '+inserted_barcode+' </p>');
            }
        });

    </script>

@endpush
