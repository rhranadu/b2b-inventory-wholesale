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
                <h2>Supplier ledger Info</h2>
            </div>

            <br>
            <br>
            <br>
            <br>
            <br>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Supplier Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->mobile }}</td>
                        <td>{{ $supplier->address }}</td>
                    </tr>
                    <tbody>
                </table>
            </div>

                <br>
                <br>

                <div class="table-responsive-sm">
                    <table class="table invoice-table">
                        <thead  style="background: #F5F5F5;">
                        <tr>
                            <th>SI</th>
                            <th>Date</th>
                            <th>Particular</th>
                            <th class="text-center">Debit</th>
                            <th class="text-center">Credit</th>
                            <th class="text-center">Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $total_credit = 0;
                            $total_debit = 0;
                            $total_blance = 0;
                        @endphp
                        @foreach($supplier_payments_transactions as $payment_transaction)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $payment_transaction->transaction_date }}</td>
                                <td>{{ $payment_transaction->particulars }}</td>
                                <td class="text-right">{{ $payment_transaction->debit?? '0.00' }}</td>
                                <td class="text-right">{{ number_format( $payment_transaction->credit, 2) }}</td>
                                <td class="text-right">{{ number_format($payment_transaction->balance, 2)  }}  </td>
                            </tr>
                            @php
                                $total_credit += $payment_transaction->credit ?? 0;
                                $total_debit += $payment_transaction->debit ?? 0;
                            @endphp
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-right">Total</td>
                            <td colspan="" class="text-right">{{ number_format($total_debit, 2) }}</td>
                            <td colspan="" class="text-right">{{ number_format($total_credit,2) }}</td>
                            <td colspan="" class="text-right">{{ number_format($total_credit - $total_debit, 2) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>


{{--

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
            <div class="invoice">
                <div class="row"  style="float: left">
                    <div class="col-sm-6">
                        <h4>From:</h4>
                        <address>
                            <strong>Vendor Inc.</strong><br>
                            123 Vendor Ave. <br>
                            Toronto, Ontario - L2R 4U6<br>
                            P: (416) 123 - 4567 <br>
                            E: vendor@vendor.com
                        </address>
                    </div>
                </div>

                <div class="row">

                    <div class="col-sm-7" style="float: right;">
                        <h4>To:</h4>
                        <address>
                            <strong>Andre Madarang</strong><br>
                            <span>123 Cool St.</span><br>
                            <span>andre@andre.com</span>
                        </address>
                    </div>
                </div>

                <div class="col-sm-5 text-right">
                    <table class="w-full">
                        <tbody>
                        <tr>
                            <th>Invoice Num:</th>
                            <td>56</td>
                        </tr>
                        <tr>
                            <th> Invoice Date: </th>
                            <td>Jun 24, 2019</td>
                        </tr>
                        </tbody>
                    </table>

                    <div style="margin-bottom: 0px">&nbsp;</div>
                </div>

                <div class="table-responsive">
                    <table class="table invoice-table">
                        <thead style="background: #F5F5F5;">
                        <tr>
                            <th>Item List</th>
                            <th></th>
                            <th class="text-right">Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <strong>Service</strong>
                            </td>
                            <td></td>
                            <td class="text-right">$600</td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Service</strong>
                                <p>Description here. Lorem ipipisci dolorem nulla rerum voluptatibus.</p>
                            </td>
                            <td></td>
                            <td class="text-right">$600</td>
                        </tr>

                        </tbody>
                    </table>
                </div><!-- /table-responsive -->

                <table class="table invoice-total">
                    <tbody>
                    <tr>
                        <td class="text-right"><strong>Balance Due :</strong></td>
                        <td class="text-right small-width">$600</td>
                    </tr>
                    </tbody>
                </table>

                <hr>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="invbody-terms">
                            Thank you for your business. <br>
                            <br>
                            <h4>Payment Terms and Methods</h4>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium cumque neque velit tenetur pariatur perspiciatis dignissimos corporis laborum doloribus, inventore.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>



--}}

