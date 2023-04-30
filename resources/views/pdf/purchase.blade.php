<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        .page-break {
            page-break-after: always;
        }
        .bg-grey {
            background: #F3F3F3;
        }
        .text-right {
            text-align: right;
        }

        .w-full {
            width: 100%;
        }

        .small-width {
            width: 15%;
        }
        .invoice {
            background: white;
            border: 1px solid #CCC;
            font-size: 14px;
            padding: 48px;
            margin: 20px 0;
        }
    </style>
</head>
<body class="bg-grey">

<div class="container container-smaller">
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div style="float: left">
                <h2>Purchases Info</h2>
            </div>
            <div class="col-sm-5 text-right" style="float: right; text-align: center; padding-top: 10px">
                <table>
                    <tbody>
                    <tr>
                        <th>Invoice Num:</th>
                        <td>#{{ $purchase->invoice_no }}</td>
                    </tr>
                    <tr>
                        <th> Invoice Date: </th>
                        <td>{{ $purchase->date }}</td>
                    </tr>
                    </tbody>
                </table>
                <div style="margin-bottom: 0px">&nbsp;</div>
            </div>
            <br>
            <br>
            <br>

            <div class="invoice">
                <div class="row"  style="float: left">
                    <div class="col-sm-6">
                        <h4>From:</h4>
                        <address>
                            <strong>{{ $purchase->purchaseVendor->name }}</strong><br>
                            {{ $purchase->purchaseVendor->email }}<br>
                            {{ $purchase->purchaseVendor->phone }}<br>
                            {{ $purchase->purchaseVendor->address }}<br>
                        </address>
                    </div>
                </div>

                <div class="row">

                    <div class="col-sm-7" style="float: right;">
                        <h4>To:</h4>
                        <address>
                            <strong>{{ $purchase->purchaseSupplier->name }}</strong><br>
                            <span>{{ $purchase->purchaseSupplier->email }}</span><br>
                            <span>{{ $purchase->purchaseSupplier->mobile }}</span><br>
                            <span>{{ $purchase->purchaseSupplier->address }}</span><br>
                        </address>
                    </div>
                </div>

                <br>
                <br>

                <div class="table-responsive-sm">
                    <table class="table invoice-table">
                        <thead  style="background: #F5F5F5;">
                        <tr>
                            <th>SI</th>
                            <th>Product Name</th>
                            <th>Attribute</th>
                            <th>PQ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($purchase->purchaseDetail as $item)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td class="text-left" width="200px">{{ $item->product->name }}</td>
                                <td class="text-left">
                                    <ul>
                                        @foreach($item->purchaseAttributeDetails as $pad)
                                        <li>{{ $pad->attribute_name }} - {{ $pad->attribute_map_name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>