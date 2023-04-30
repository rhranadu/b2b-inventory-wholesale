@if(!empty($invoice_product))
    <div class="w-100">
        <div class="accordion accordion-toggle" id="accordionExample{{ $invoice_product->id }}">
            <div class="card">
                <div class="card-header">
                    <div class="card-title" >

                        Invoice : {{ $invoice_product->invoice_no }}
                    </div>
                </div>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col"> <input type="checkbox" class="return_checkbox" id="invoice_no" name="invoice" value="1"> &nbsp;&nbsp;</th>
                    <th scope="col">Product</th>
                    <th scope="col">BarCode</th>
                </tr>
                </thead>
            @foreach($invoice_product->saleDetails as $saleDetail)
                    <tr>
                        <th scope="row">
                            <input type="checkbox" class="return_checkbox" id="product{{ $saleDetail->product->id }}" name="return_product_id[]" value="{{ $saleDetail->product->id }}" onclick="toggleCheckBox(this.id)" >
                        </th>
                        <td>{{$saleDetail->product->name}}</td>
                        <td> </td>
                    </tr>
                @foreach($saleDetail->soldBarcode as $s_b)
                        <tr>
                            <th scope="row" class="return_bar_code">
                                <input type="checkbox" class="return_checkbox product{{ $saleDetail->product->id }}" id="barcode{{ $s_b->bar_code }}" name="return_product_bar_code[]" value="{{ $s_b->bar_code }}">
                            </th>
                            <td>{{$saleDetail->product->name}}</td>
                            <td> {{ $s_b->bar_code }} </td>
                        </tr>
                @endforeach
            @endforeach
            </table>
        </div>
    </div>
@endif

<script>
    $('#invoice_no').on('change', function() {
        $('input[type=checkbox]').prop('checked', this.checked);
        $('#instant_exchange_checkbox').prop('checked', false);
    });

        function toggleCheckBox(id) {
            $('#'+id+'').on('change', function() {
                $('.'+id+'').prop('checked', this.checked);
            });
        }

</script>
