<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale invoice</title>

    <style>
        * {
            font-family: 'Playfair Display', serif;
        }

        header {
            background-color: silver;
            padding: 5px;
            border-radius: 5px;
            height: 50px;
        }

        header h3 {
            color: black;
        }

        div {
            display: flex;
            flex-direction: row;
        }

        div .to {
            margin-left: auto;
        }
        .left{
            float: left;
        }
        .right{
            float: right;
        }
    </style>
</head>

<body>
<header>
    <div class="left" style="font-size: 30px">Sale Info</div>
    <div class="right">
        <table>
            <thead>
            <tr>
                <th>Invoice : #{{ $sale_pdf->invoice_no }}</th>
            </tr>
            <tr>
                <th>Date : {{ $sale_pdf->created_at }}</th>
            </tr>
            </thead>
        </table>
    </div>
</header>


<br>
<br>
<br>

<div>
    <address style="float: left; width: 300px">
        <p><b>From</b></p>
        <p><b>Name :</b> {{ $sale_pdf->vendor->name }}</p>
        <p><b>Email :</b> {{ $sale_pdf->vendor->email }}</p>
        <p><b>Phone :</b> {{ $sale_pdf->vendor->phone }}</p>
        <p><b>Address :</b> {{ $sale_pdf->vendor->address }}</p>
    </address>

    <address class="to" style="float:right; width: 200px; padding: 0px">
        <p><b>To</b></p>
        <p><b>Name :</b>{{ $sale_pdf->customer->name }}</p>
        <p><b>Email :</b> {{ $sale_pdf->customer->email }}</p>
        <p><b>Phone :</b> {{ $sale_pdf->customer->mobile }}</p>
        <p><b>Address :</b> {{ $sale_pdf->customer->address }}</p>
    </address>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<table width="100%">
    <thead style="background-color: lightgray;">
    <tr>
        <th>Product Name</th>
        <th>Attribute</th>
        <th>Pay Status</th>
        <th>Sale Qty</th>
        <th>Price</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sale_pdf->saleDetails as $item)
        <tr>
            <td align="center">{{ $item->product->name }}<hr></td>
            <td align="center">{{ $item->attribute->name }} - {{ $item->attributeMap->value }}<hr></td>
            <td align="center">
                @if(isset($sale_pdf->payment->last()->status) and $sale_pdf->payment->last()->status == 'FP')
                    <span>Full Paid</span>
                @elseif(isset($sale_pdf->payment->last()->status) and $sale_pdf->payment->last()->status == 'PP')
                    <span>Partial Paid</span>
                @else
                    <span>Not Paid</span>
                @endif
                <hr>
            </td>
            <td align="center">{{ $item->quantity }} <hr></td>
            <td align="center">{{ $item->per_price }} <hr></td>
            <td align="center">{{ $item->total }} <hr></td>

        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="4"></td>
        <td align="right">Subtotal </td>
        <td align="center">{{ $sale_pdf->sub_total }}</td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td align="right">Tax(+) </td>
        <td align="center">{{ $sale_pdf->tax }}</td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td align="right">Shipping(+) </td>
        <td align="center">{{ $sale_pdf->shipping_charge }}</td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td align="right">Discount(-) </td>
        <td align="center">{{ $sale_pdf->discount }}</td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td align="right">Total </td>

        <td align="center" style="background-color: gray;">
           {{ $sale_pdf->final_total }}
        </td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td align="right">Pay Amount </td>

        <td align="center">
           - {{ $sale_pdf->payment->sum('pay_amount')  }}
        </td>
    </tr>
    </tfoot>
</table>
<hr>
@if($sale_pdf->payment->sum('pay_amount') > $sale_pdf->final_total)
    <p class="right"> Give back {{ $sale_pdf->payment->sum('pay_amount') -  $sale_pdf->final_total  }}</p>
@elseif($sale_pdf->payment->sum('pay_amount') == $sale_pdf->final_total)
    <p class="right">Successfully paid</p>
@elseif($sale_pdf->payment->sum('pay_amount') < $sale_pdf->final_total)
    <p class="right"> Due amount {{ $sale_pdf->final_total- $sale_pdf->payment->sum('pay_amount')  }}</p>
@endif


</body>

</html>
