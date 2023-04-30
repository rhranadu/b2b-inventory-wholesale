<!doctype html>
<html>
<head>
    <link rel="shortcut icon" href="/imgs/mingle-icon.png">
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="/assets/css/bootstrap.css"/>
    <title>Chalan Copy</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            /*border:1px solid #eee;*/
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(3), .invoice-box table tr td:nth-child(4) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            /*padding-bottom: 20px;*/
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            /*padding-bottom: 40px;*/
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(3), .invoice-box table tr.total td:nth-child(4) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                /*width: 100%;*/
                /*display: block;*/
                text-align: center;
            }
        }

        .rubber_stamp {
            font-family: 'Vollkorn', serif;
            font-size: 39px;
            line-height: 45px;
            text-transform: uppercase;
            font-weight: bold;
            color: red;
            border: 7px solid red;
            float: left;
            padding: 10px 7px;
            border-radius: 10px;

            /*opacity: 0.8;*/
            -webkit-transform: rotate(-10deg);
            -o-transform: rotate(-10deg);
            -moz-transform: rotate(-10deg);
            -ms-transform: rotate(-10deg);
            position: relative;
            top: 32%;

            background-color: white;
            margin-left: -30px;
            margin-top: -10px;
        }

        .rubber_stamp::after {
            position: absolute;
            content: " ";
            width: 100%;
            height: auto;
            min-height: 100%;
            top: -10px;
            left: -10px;
            padding: 10px;
            background: url(https://raw.github.com/domenicosolazzo/css3/master/img/noise.png) repeat;
        }

        .rubber_stamp_round {
            border: solid 5px red;
            border-radius: 250px;
            position: relative;
            width: 150px;
            height: 150px;
        }

        .attribute_table td, th {
            border: solid 1px #eeeeee;
        }

        .badge {
            border: solid 1px gray;
            color: black;
            text-align: left;
        }
        @media print {
            .no-print{
                display: none;
            }
        }
    </style>
</head>

<body>
<div class="invoice-box">
<a href="#" onclick="window.print();" class="btn btn-info btn-sm no-print" style="float: right">Print</a>
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="5">
                <table>
                    <tr>
                        <td style="text-align: center !important;">
                            <h1>Anwar Traders</h1>
                            <span><strong>138/6,Khan Market,Chawk Moghatuly,Dhaka-1211</strong></span>
                            <hr/>
                            <p>Mobile No : 067317279, +8801741-111643, +8801918687927</p>
                            <hr/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="information">
            <td colspan="5">
                <table>
                    <tr>
                        <td>
                            <span><strong>No :</strong> {!! $saleData->invoice_no !!}</span><br><br>
                        </td>
                        <td style="text-align: center !important;">
                            <span><strong>Email : @if($saleData->pos_customer_id > 0)
                                        {!! $saleData->posCustomer->email !!}
                                    @else
                                        {!! $saleData->marketplaceUser->email !!}
                                    @endif
                                </strong></span>
                        </td>
                        <td style="text-align: right !important;">
                            <span><strong>Date : {!! date('d M, Y', strtotime($saleData->created_at)) !!}</strong></span>
                        </td>
                    </tr>
                    @if($saleData->pos_customer_id > 0)
                        <tr>
                            <td colspan="4">
                                <span><strong>Name :</strong>{!! $saleData->posCustomer->name !!}</span><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <span><strong>Address :</strong> {!! $saleData->posCustomer->address !!}</span>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4">
                                <span><strong>Name :</strong>{!! $saleData->marketplaceUser->name !!}</span><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <span><strong>Address :</strong> {!! $saleData->marketplaceUser->mobile !!}</span>
                            </td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
        <tr class="heading table-bordered">
            <td class="text-center">
                Sl.No.
            </td>
            <td class="text-center">
                Code
            </td>
            <td class="text-center">
                Attributes
            </td>
            <td class="text-center">
                Product
            </td>
            <td class="text-center">
                Quantity
            </td>
        </tr>
        @php
            $total = 0;
        @endphp

        @foreach($saleData->saleDetails as $key => $saleDetails)
            @php $total += $saleDetails->quantity @endphp
            <tr class="details table-bordered">
                <td class="text-center">
                    {!! $loop->iteration !!}
                </td>
                <td class="text-center">
                    {!! $saleDetails->product->sku ?? 'N/A'!!}
                </td>
                <td class="text-center">
                    {!! $saleDetails->product_attributes_pair!!}
                </td>
                <td class="text-center">
                    {!! $saleDetails->product->name !!}
                </td>
                <td class="text-center">
                    {!! $saleDetails->quantity !!}
                </td>
            </tr>
        @endforeach

        <tr class="total table-bordered">
            <td colspan="2"><strong>Above goods received in good condition.</strong></td>
            <td colspan="2" class="text-center"><strong>Total</strong>:</td>
            <td class="text-center"><strong>{{$total ?? ''}}</strong></td>
        </tr>
        <tr>
            <td colspan="4">
                <br> <br> <br> <br>
                <span><strong>Signature of Receiver</strong></span>
            </td>
            <td style="text-align: right !important;">
                <br> <br> <br> <br>
                <span><strong>For Anwar Traders</strong></span>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="text-align:center;">
                <h5 style="border-top:double 3px black; border-bottom:double 3px black">Thank you for using Anwar
                    Traders</h5>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
