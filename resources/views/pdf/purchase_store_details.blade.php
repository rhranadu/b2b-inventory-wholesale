<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - #0001</title>

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
    <div class="left" style="font-size: 30px">Purchases Store Info</div>
    <div class="right">
        <table>
            <thead>
            <tr>
                <th>Invoice : #{{ $purchase->invoice_no }}</th>
            </tr>
            <tr>
                <th>Date : {{ $purchase->date }}</th>
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
        <p><b>Name :</b> {{ $purchase->purchaseVendor->name }}</p>
        <p><b>Email :</b> {{ $purchase->purchaseVendor->email }}</p>
        <p><b>Phone :</b> {{ $purchase->purchaseVendor->phone }}</p>
        <p><b>Address :</b> {{ $purchase->purchaseVendor->address }}</p>
    </address>

    <address class="to" style="float:right; width: 200px; padding: 0px">
        <p><b>To</b></p>
        <p><b>Name :</b>{{ $purchase->purchaseSupplier->name }}</p>
        <p><b>Email :</b> {{ $purchase->purchaseSupplier->email }}</p>
        <p><b>Phone :</b> {{ $purchase->purchaseSupplier->mobile }}</p>
        <p><b>Address :</b> {{ $purchase->purchaseSupplier->address }}</p>
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
        <th>PQ</th>
        <th>RQ</th>
        <th>PP</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($purchase->purchaseDetail as $item)
        <tr>
            <td class="text-center">{{ $item->product->name }}<hr></td>
            <td class="text-left">
                <ul>
                    @foreach($item->purchaseAttributeDetails as $pad)
                    <li>{{ $pad->attribute_name }} - {{ $pad->attribute_map_name }}</li>
                    @endforeach
                </ul>
            </td>
            <td class="text-center">{{ $item->quantity }} <hr></td>
            <td class="text-center">{{ $item->stockWarehouse->sum('quantity') }}<hr></td>
            <td class="text-center">
                @if($item->stockWarehouse->count() > 0)
                    @foreach($item->stockWarehouse as $wareh)
                        <span class="badge">{{ $wareh->price }} </span>,
                    @endforeach
                @else
                    <span class="badge">No Recode</span>,
                 @endif

                <hr></td>
            <td class="text-center">{{ $item->stockWarehouse->sum('total_price') }}<hr></td>
        </tr>
    @endforeach
    </tbody>
{{--
    <tfoot>
    <tr>
        <td colspan="4"></td>
        <td align="right">Subtotal </td>
        <td align="center">6000 Tk.</td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td align="right">Shipping(+) </td>
        <td align="center">50 TK.</td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td align="right">Discount(-) </td>
        <td align="center">50 TK.</td>

    </tr>
    <tr>
        <td colspan="4"></td>
        <td align="right">Total </td>

        <td align="center" style="background-color: gray;">
            6000 TK.
        </td>
    </tr>
    </tfoot>--}}
</table>
</body>

</html>
