<style>
    #print_pos td, #print_pos th{
        padding: 2px 4px;
    }
    #print_pos * {
        font-family: monospace;
    }
    #print_pos * {
        font-weight: normal;
        font-size: 10px;
    }
</style>
<div id="print_pos" name="print_pos">
    <div style="width:auto; padding-left:5px;">
        <table class="table">
            <thead>
            <tr>
                <td colspan="5" style="text-align:center;padding:2px;border-bottom: dashed 1px black;">
                    <h3 style="margin-bottom:0;">
                        @if(auth()->user()->vendor->logo)
                            <img src="{{ asset(auth()->user()->vendor->logo) }}" alt="avatar" style="width:100px;">
                        @else
                            <img src="/images/t2v-logo.png" alt="avatar" style="width:100px;">
                        @endif

                        <br>
                        {!! $saleData->vendor->name !!}<br>
                    </h3>
                    <small>{{ $saleData->vendor->address }}</small>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align:left">
                    <table style="width: 100%; text-align:right;font-size:10px;">
                        <tbody><tr>
                            <td style="">Invoice No :</td>
                            <td style="text-align:left">{!! $saleData->invoice_no !!}</td>
                        </tr>
                        <tr>
                            <td style="">Date :</td>
                            <td style="text-align:left">{!! date('d M, Y', strtotime($saleData->created_at)) !!}</td>
                        </tr>
                        <tr>
                            <td style="">Time :</td>
                            <td style="text-align:left">{!! date('h:i:s A', strtotime($saleData->created_at)) !!}</td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </thead>
            <tbody style="font-size:10px;">
            <tr>
                <th style="border-bottom: dashed 1px black;border-top: dashed 1px black;">#</th>
                <th style="border-bottom: dashed 1px black;border-top: dashed 1px black;">Item Name</th>
                <th style="border-bottom: dashed 1px black;border-top: dashed 1px black;">Qty</th>
                <th style="border-bottom: dashed 1px black;border-top: dashed 1px black;">U. Price</th>
                <th style="border-bottom: dashed 1px black;border-top: dashed 1px black;">T. Price</th>
            </tr>
            @php
            $subtotal = $vat = 0;
            @endphp
            @foreach($saleData->saleDetails as $key => $saleDetails)
                @php($subtotal += $saleDetails->quantity * $saleDetails->per_price)
            <tr>
                <td style="border:none;">1</td>
                <td style="border:none;">{!! $saleDetails->product->name !!}</td>
                <td style="border:none;text-align:center;">{!! $saleDetails->quantity !!}</td>
                <td style="border:none;text-align:right;">{!! $saleDetails->per_price !!}</td>
                <td style="border:none;text-align:right;">{!! $saleDetails->quantity * $saleDetails->per_price !!}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="4" style="border-top: dashed 1px black;text-align:right;">Subtotal</th>
                <th style="border-top: dashed 1px black;text-align:right;">{!! $subtotal !!}</th>
            </tr>
            <tr>
                <th colspan="4" style="border: none;text-align:right;">Vat</th>
                <th style="border: none;text-align:right;">{!! $vat !!}</th>
            </tr>
            <tr>
                <th colspan="4" style="border-top: none;border-bottom: dashed 1px black;text-align:right;">Discount</th>
                <th style="border-top: none;border-bottom: dashed 1px black;text-align:right;">{!! $saleData->discount !!}</th>
            </tr>
            <tr>
                <th colspan="4" style="border-top: dashed 1px black;border-bottom: dashed 1px black;text-align:right;">Grand Total</th>
                <th style="border-top: dashed 1px black;border-bottom: dashed 1px black;text-align:right;">{!! $subtotal + $vat - $saleData->discount !!}</th>
            </tr>
            <tr>
                <th colspan="4" style="border-top: dashed 1px black;text-align:right;">Paid</th>
                <th style="border-top: dashed 1px black;text-align:right;">{!! $saleData->total_payment !!}</th>
            </tr>
            <tr>
                <th colspan="4" style="border-top: dashed 1px black;text-align:right;">Due</th>
                <th style="border-top: dashed 1px black;text-align:right;">{!! $saleData->due_payment !!}</th>
            </tr>
            <tr style="display:none">
                <td colspan="5" style="border:none;"><i>In Word: One Hundred And Fifty</i></td>
            </tr>
            </tbody>
        </table>
        <div style="text-align:center;"><b>Thank You For Your Business</b><br>
            <small style="border: dashed 1px black;display: block;">Powered By Tech2View Limited<br>www.t2v.com.bd</small>
        </div>
    </div>
</div>
