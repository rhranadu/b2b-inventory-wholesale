@extends('layouts.crud-master')

@push('css')
    <style>
        .invoice {
            position: relative;
            background-color: #FFF;
            min-height: 680px;
            padding: 15px
        }

        .invoice header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #00c292;
        }

        .invoice .vendor-details {
            margin-right: 20px;
            text-align: right
        }

        .invoice .vendor-details .name {
            margin-top: 0;
            margin-bottom: 0;
        }

        .invoice .contacts {
            margin-bottom: 20px;
        }

        .invoice .invoice-to {
            text-align: left;
            margin-left: 20px;
        }

        .invoice .invoice-to .to {
            margin-top: 0;
            margin-bottom: 0
        }

        .invoice .invoice-details {
            text-align: right;
            margin-right: 20px;
        }

        .invoice .invoice-details .invoice-id {
            margin-top: 0;
            color: #00c292
        }

        .invoice main {
            padding-bottom: 50px
        }

        .invoice main .thanks {
            margin-top: -100px;
            font-size: 2em;
            margin-bottom: 50px
        }

        .invoice main .notices {
            padding-left: 6px;
            border-left: 6px solid #00c292
        }

        .invoice main .notices .notice {
            font-size: 1.2em
        }

        .invoice table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px
        }

        .invoice table td,.invoice table th {
            padding: 15px;
            background: #eee;
            border-bottom: 1px solid #fff
        }

        .invoice table th {
            white-space: nowrap;
            font-weight: 400;
            font-size: 16px
        }

        .invoice table td h3 {
            margin: 0;
            font-weight: 400;
            color: #00c292;
            font-size: 1.2em
        }

        .invoice table .qty,.invoice table .total,.invoice table .unit {
            text-align: right;
            font-size: 1.2em
        }

        .invoice table .no {
            color: #fff;
            font-size: 1.6em;
            background: #00c292
        }

        .invoice table .unit {
            background: #ddd
        }

        .invoice table .total {
            background: #00c292;
            color: #fff
        }

        .invoice table tbody tr:last-child td {
            border: none
        }

        .invoice table tfoot td {
            background: 0 0;
            border-bottom: none;
            white-space: nowrap;
            text-align: right;
            padding: 10px 20px;
            font-size: 1.2em;
            border-top: 1px solid #aaa
        }

        .invoice table tfoot tr:first-child td {
            border-top: none
        }

        .invoice table tfoot tr:last-child td {
            color: #00c292;
            font-size: 1.4em;
            border-top: 1px solid #00c292
        }

        .invoice table tfoot tr td:first-child {
            border: none
        }

        .invoice footer {
            width: 100%;
            text-align: center;
            color: #777;
            border-top: 1px solid #aaa;
            padding: 8px 0
        }

        @media print {
            .invoice {
                font-size: 11px!important;
                overflow: hidden!important
            }

            .invoice footer {
                position: absolute;
                bottom: 10px;
                page-break-after: always
            }

            .invoice>div:last-child {
                page-break-before: always
            }
        }
    </style>
@endpush

@section('main_content')
    <!-- Breadcomb area Start-->
    <div class="breadcomb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcomb-list">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="breadcomb-wp">
                                    <div class="breadcomb-icon">
                                        <i class="notika-icon notika-windows"></i>
                                    </div>
                                    <div class="breadcomb-ctn">
                                        <h2>View Order</h2>
                                        <p>View Order</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
                                <div class="breadcomb-report">
                                    <a href="{{ route('admin.order.index') }}" class="btn waves-effect" data-toggle="tooltip" data-placement="left" title="" data-original-title="Purchase List"><i class="fa fa-list"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcomb area End-->


    <div class="normal-table-area">
        <div class="container">
            <div id="">

                <div class="toolbar hidden-print">
                    <div class="text-right">
                        <button id="printInvoice" class="btn btn-info" style="background:#00c292;"><i class="fa fa-print"></i> Print</button>
                        <button class="btn btn-info" style="background:#00c292;"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
                    </div>
                    <hr>
                </div>
                <div class="invoice overflow-auto">
                    <div style="min-width: 600px">
                        <header>
                            <div class="row">
                                <div class="col">
                                    <a target="_blank" href="https://lobianijs.com">
                                        <img src="http://lobianijs.com/lobiadmin/version/1.0/ajax/img/logo/lobiadmin-logo-text-64.png" data-holder-rendered="true" />
                                    </a>
                                </div>
                                <div class="col vendor-details">
                                    <h2 class="name">
                                        <a target="_blank" href="" style="color:#000000;">
                                            Arboshiki
                                        </a>
                                    </h2>
                                    <div>455 Foggy Heights, AZ 85004, US</div>
                                    <div>(123) 456-789</div>
                                    <div>vendor@example.com</div>
                                </div>
                            </div>
                        </header>
                        <main>
                            <div class="row contacts">
                                <div class="col invoice-to">
                                    <div class="text-gray-light">INVOICE TO:</div>
                                    <h3 class="to">John Doe</h3>
                                    <div class="address">796 Silver Harbour, TX 79273, US</div>
                                    <div class="email">john@example.com</div>
                                </div>
                                <div class="col invoice-details">
                                    <h1 class="invoice-id">INVOICE 3-2-1</h1>
                                    <div class="date">Date of Invoice: 01/10/2018</div>
                                    <div class="date">Due Date: 30/10/2018</div>
                                </div>
                            </div>
                            <table border="0" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-left"><h4>Products List</h4></th>
                                    <th class="text-right"><h4>HOUR PRICE</h4></th>
                                    <th class="text-right"><h4>HOURS</h4></th>
                                    <th class="text-right"><h4>TOTAL</h4></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="no">04</td>
                                    <td class="text-left"><h3>
                                            Youtube channel
                                        </h3>
                                        to improve your Javascript skills. Subscribe and stay tuned :)

                                    </td>
                                    <td class="unit">$0.00</td>
                                    <td class="qty">100</td>
                                    <td class="total">$0.00</td>
                                </tr>
                                <tr>
                                    <td class="no">01</td>
                                    <td class="text-left"><h3>Website Design</h3>Creating a recognizable design solution based on the vendor's existing visual identity</td>
                                    <td class="unit">$40.00</td>
                                    <td class="qty">30</td>
                                    <td class="total">$1,200.00</td>
                                </tr>
                                <tr>
                                    <td class="no">02</td>
                                    <td class="text-left"><h3>Website Development</h3>Developing a Content Management System-based Website</td>
                                    <td class="unit">$40.00</td>
                                    <td class="qty">80</td>
                                    <td class="total">$3,200.00</td>
                                </tr>
                                <tr>
                                    <td class="no">03</td>
                                    <td class="text-left"><h3>Search Engines Optimization</h3>Optimize the site for search engines (SEO)</td>
                                    <td class="unit">$40.00</td>
                                    <td class="qty">20</td>
                                    <td class="total">$800.00</td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">SUBTOTAL</td>
                                    <td>$5,200.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">TAX 25%</td>
                                    <td>$1,300.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">GRAND TOTAL</td>
                                    <td>$6,500.00</td>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="thanks">Thank you!</div>
                            <div class="notices">
                                <div>NOTICE:</div>
                                <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
                            </div>
                        </main>
                        <footer>
                            Invoice was created on a computer and is valid without the signature and seal.
                        </footer>
                    </div>
                    <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                    <div></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        $('#printInvoice').click(function(){
            Popup($('.invoice')[0].outerHTML);
            function Popup(data)
            {
                window.print();
                return true;
            }
        });
    </script>
@endpush
