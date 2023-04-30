@if(!empty($return_array))
    <div class="w-100">
        <div class="accordion accordion-toggle" id="accordionExample">
            <div class="card">
                <div class="card-header">
                    <div class="card-title" >

{{--                        Invoice : {{ $invoice_product->invoice_no }}--}}
                        Exchanged Product Bar code
                    </div>
                </div>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">
{{--                        <input type="checkbox" class="exchange_checkbox" id="exchanged_invoice_no" name="invoice" value="1" checked> &nbsp;&nbsp;--}}
                    </th>
                    <th scope="col">Product</th>
                    <th scope="col">BarCode</th>
                </tr>
                </thead>
            @foreach($return_array as $key => $value)
                    <tr>
                        <th scope="row">
{{--                            <input type="checkbox" class="exchange_checkbox" id="exchange_product{{ $key }}" name="exchanged_product_id[]" value="{{ $key }}" onclick="toggleCheckBox(this.id)" checked>--}}
                        </th>
                        <td>{{$value['product_name']}}</td>
                        <td> </td>
                    </tr>
                @foreach($value['data'] as $k => $v)
                        <tr>
                            <th scope="row">
                                <span class="btn btn-sm btn-outline -success"><i class="fa fa-check"></i></span>
                                <input type="hidden" class="exchange_checkbox exchange_product{{ $key }}" id="barcode{{ $v['bar_code'] }}" name="exchanged_product_bar_code[]" value="{{ $v['bar_code']}}" checked>
                            </th>
                            <td>{{ $value['product_name'] }}</td>
                            <td> {{ $v['bar_code'] }} </td>
                        </tr>
                @endforeach
            @endforeach
            </table>
        </div>
    </div>
@endif

<script>
    $('#exchanged_invoice_no').on('change', function() {
        $('input[type=checkbox]').prop('checked', this.checked);
    });

        function toggleCheckBox(id) {
            $('#'+id+'').on('change', function() {
                $('.'+id+'').prop('checked', this.checked);
            });
        }

</script>
