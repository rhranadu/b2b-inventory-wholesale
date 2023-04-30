<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
    <!--end::Fonts-->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="{!! asset('/pos_assets/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{!! asset('/assets/css/style.bundle.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('/assets/css/perfect-scrollbar.css') !!}">
    <link rel="stylesheet" href="{!! asset('/pos_assets/css/toastr.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('/pos_assets/css/custom.css') !!}">
    {{-- datatable --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/autoFill.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/colReorder.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/fixedColumns.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/fixedHeader.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/keyTable.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/rowGroup.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/rowReorder.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/scroller.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/searchBuilder.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/searchPanes.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/css/select.dataTables.min.css') }}">
    {{-- datatable end --}}
    @if(auth()->user()->vendor->logo)
        <link rel="shortcut icon" href="{{ asset(auth()->user()->vendor->favicon)}}"/>
    @endif
        @if(isset(auth()->user()->vendor->name))
            <title>Retail Sell - {!! auth()->user()->vendor->name !!}</title>
        @endif
    @stack('css')
    <style>
        @font-face {
            font-family: "Ki";
            src: url({!! asset('/assets/plugins/global/fonts/Ki.eot') !!});
            src: url({!! asset('/assets/plugins/global/fonts/Ki.eot?#iefix') !!}) format("embedded-opentype"),
            url({!! asset('/assets/plugins/global/fonts/Ki.woff') !!}) format("woff"),
            url({!! asset('/assets/plugins/global/fonts/Ki.ttf') !!}) format("truetype"),
            url({!! asset('/assets/plugins/global/fonts/Ki.svg#Ki') !!}) format("svg");
            font-weight: normal;
            font-style: normal;
        }

        .ki {
            font-size: 1rem;
        }

        .ki:before {
            font-family: "Ki";
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            line-height: 1;
            text-decoration: inherit;
            text-rendering: optimizeLegibility;
            text-transform: none;
            -moz-osx-font-smoothing: grayscale;
            -webkit-font-smoothing: antialiased;
            font-smoothing: antialiased;
        }

        .ki-double-arrow-next:before { content: "\f100"; }
        .ki-double-arrow-back:before { content: "\f101"; }
        .ki-double-arrow-down:before { content: "\f102"; }
        .ki-double-arrow-up:before { content: "\f103"; }
        .ki-long-arrow-back:before { content: "\f104"; }
        .ki-arrow-next:before { content: "\f105"; }
        .ki-arrow-back:before { content: "\f106"; }
        .ki-long-arrow-next:before { content: "\f107"; }
        .ki-check:before { content: "\f108"; }
        .ki-arrow-down:before { content: "\f109"; }
        .ki-minus:before { content: "\f10a"; }
        .ki-long-arrow-down:before { content: "\f10b"; }
        .ki-long-arrow-up:before { content: "\f10c"; }
        .ki-plus:before { content: "\f10d"; }
        .ki-arrow-up:before { content: "\f10e"; }
        .ki-round:before { content: "\f10f"; }
        .ki-reload:before { content: "\f110"; }
        .ki-refresh:before { content: "\f111"; }
        .ki-solid-plus:before { content: "\f112"; }
        .ki-bold-close:before { content: "\f113"; }
        .ki-solid-minus:before { content: "\f114"; }
        .ki-hide:before { content: "\f115"; }
        .ki-code:before { content: "\f116"; }
        .ki-copy:before { content: "\f117"; }
        .ki-up-and-down:before { content: "\f118"; }
        .ki-left-and-right:before { content: "\f119"; }
        .ki-bold-triangle-bottom:before { content: "\f11a"; }
        .ki-bold-triangle-right:before { content: "\f11b"; }
        .ki-bold-triangle-top:before { content: "\f11c"; }
        .ki-bold-triangle-left:before { content: "\f11d"; }
        .ki-bold-double-arrow-up:before { content: "\f11e"; }
        .ki-bold-double-arrow-next:before { content: "\f11f"; }
        .ki-bold-double-arrow-back:before { content: "\f120"; }
        .ki-bold-double-arrow-down:before { content: "\f121"; }
        .ki-bold-arrow-down:before { content: "\f122"; }
        .ki-bold-arrow-next:before { content: "\f123"; }
        .ki-bold-arrow-back:before { content: "\f124"; }
        .ki-bold-arrow-up:before { content: "\f125"; }
        .ki-bold-check:before { content: "\f126"; }
        .ki-bold-wide-arrow-down:before { content: "\f127"; }
        .ki-bold-wide-arrow-up:before { content: "\f128"; }
        .ki-bold-wide-arrow-next:before { content: "\f129"; }
        .ki-bold-wide-arrow-back:before { content: "\f12a"; }
        .ki-bold-long-arrow-up:before { content: "\f12b"; }
        .ki-bold-long-arrow-down:before { content: "\f12c"; }
        .ki-bold-long-arrow-back:before { content: "\f12d"; }
        .ki-bold-long-arrow-next:before { content: "\f12e"; }
        .ki-bold-check-1:before { content: "\f12f"; }
        .ki-close:before { content: "\f130"; }
        .ki-more-ver:before { content: "\f131"; }
        .ki-bold-more-ver:before { content: "\f132"; }
        .ki-more-hor:before { content: "\f133"; }
        .ki-bold-more-hor:before { content: "\f134"; }
        .ki-bold-menu:before { content: "\f135"; }
        .ki-drag:before { content: "\f136"; }
        .ki-bold-sort:before { content: "\f137"; }
        .ki-eye:before { content: "\f138"; }
        .ki-outline-info:before { content: "\f139"; }
        .ki-menu:before { content: "\f13a"; }
        .ki-menu-grid:before { content: "\f13b"; }
        .ki-wrench:before { content: "\f13c"; }
        .ki-gear:before { content: "\f13d"; }
        .ki-info:before { content: "\f13e"; }

        div, input, select, .btn, .select2-selection {
            border-radius: 0 !important;
        }
        * {
            font-family:Calibri, serif;
        }


        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body class="h-100">
<div class="d-flex h-100 flex-column">

    <header
        class="main-header d-flex justify-content-between align-items-center bg-white px-5 py-0 border-bottom border-warning shadow-sm">
        <a href="<?= url('/pos')?>" class="logo" style="color: #086964;font-weight: bold;font-size: 30px;padding: 0 10px;">
            @if(auth()->user()->vendor->logo)
                <img alt="Logo" src="{{ asset(auth()->user()->vendor->logo)}}" width="30" height="30"/>
            @endif
            @if(auth()->user()->vendor->name)
                <span class="logo-mini">{!! auth()->user()->vendor->name !!}</span>
            @endif
            <span class="logo-lg"> - <b>Retail Sell</b></span>

        </a>
        <div class="topbar">
            <div class="topbar-item mr-3">
                <span class="text-dark-50 font-size-h5">{!! date('dS F Y h:i A') !!}</span>
            </div>
{{--            <div class="topbar-item mr-1">--}}
{{--                <div class="btn btn-clean btn-lg disabled" id="menuHeldOrders">--}}
{{--                    <a href="#">Held Orders</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="topbar-item mr-1">--}}
{{--                <div class="btn btn-clean btn-lg" id="menuDueOrders">--}}
{{--                    <a href="#">Due Orders</a>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="topbar-item mr-1">
                <div class="btn btn-clean btn-lg" id="menuTodaySales">
                    <a href="#">Today's Sale</a>
                </div>
            </div>
            <div class="topbar-item mr-1">
                <div class="dropdown">
                    <div class="btn btn-clean btn-lg dropdown-toggle" id="returnRequestDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><a href="#">Return Request</a></div>
                    <div class="dropdown-menu" aria-labelledby="returnRequestDropdown">
                      <a class="dropdown-item" id="pendingRequestModalButton" href="#">Pending Request</a>
                      <a class="dropdown-item" id="addRequestModalButton" href="#">Add Request</a>
                    </div>
                  </div>

            </div>
            <!--begin::User-->
            <div class="dropdown">
                <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                    <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2"
                         id="kt_quick_user_toggle">
                        <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                        <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{!! auth()->user()->name !!}</span>
                        <span class="symbol symbol-20">
                            <img src="{!! asset('/pos_assets/images/male.png') !!}" alt="">
                        </span>
                    </div>
                </div>
                <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right" style="width:300px;">
                    <!--begin::Nav-->
                    <ul class="navi navi-hover py-0">
                        <!--begin::Item-->
                        <li class="navi-item">
                            <span class="navi-link">
                                <span class="symbol symbol-20 mr-3">
                                    <img src="{!! asset('/pos_assets/images/male.png') !!}" alt="">
                                </span>
                                <span class="navi-text">{!! auth()->user()->name !!}<br><small>{!! auth()->user()->email !!}</small></span>
                            </span>
                        </li>
                        <li class="user-footer btn-group justify-content-between d-flex">
                            <a href="#" class="btn btn-default btn-flat flex-fill">
                                <i class="fa fa-user"></i> Profile
                            </a>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('form-logout').submit()" class="btn btn-light-danger btn-flat sign_out flex-fill">
                                <i class="fa fa-sign-out"></i> Log Out
                                <form style="display: none" action="{{ route('logout') }}" id="form-logout" method="post">
                                    @csrf
                                    <button type="submit"></button>
                                </form>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!--end::User-->
        </div>

    </header>
    <main class="flex-fill overflow-hidden">
        <div class="h-100 p-5">
            <div class="row h-100">
                <div class="col-xxl-9 col-xl-8 col-lg-7 col-md-7 h-100" style="padding-right:5px !important;">
                    <div class="card h-100 bg-transparent">
                        <div class="card-body p-0 bg-transparent" style="padding:0 !important;">
                            <div class="card h-100 border-0 bg-transparent">
                                <div class="card-header d-flex justify-content-between align-items-center py-5" style="background: #81abe0 !important;">
                                    <div class="w-50">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="searchOrBarcode" placeholder="Input barcode or Search items here ">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-icon" type="button">
                                                    <i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-25" style="margin: 10px;">
                                        <div class="input-group">
                                            <div class="input-group-prepend flex-fill">
                                                <select name="category" id="category" class="form-control" data-live-search="true"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-25" style="margin: 10px;">
                                        <div class="input-group">
                                            <div class="input-group-prepend flex-fill">
                                                <select class="form-control product-brand" id="productBrand" name="param"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body overflow-hidden" style="background: #d3e2f5 !important;" data-scroll="true"
                                     data-wheel-propagation="true">
                                    <div class="row" id="randomProducts">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-4 col-lg-5 col-md-5 h-100" style="padding-left:0!important;">
                    <div class="card h-100 bg-white">
                        <div class="card-header p-3">
                            <div class="input-group">
                                <div class="input-group-prepend flex-fill">
                                    <select class="form-control customer" id="Customer" name="param"></select>
                                </div>
                                <div class="input-group-append">
{{--                                    <button class="btn btn-light-primary text-nowrap" id="newCustomer"><i class="fa fa-plus"></i>Pos User</button>--}}
                                    <a href="{{route('admin.add.mp_user')}}" class="btn btn-light-primary text-nowrap" id="newMpCustomer" target="_blank"><i class="fa fa-plus"></i>Mp User</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="card border-0 h-100">
                                <div class="card-body p-0" style="padding: 0 !important;">
                                    <div id="cartList" class="floatThead h-100 overflow-hidden" data-scroll="true">
                                        <table class="table table-hover mb-0 table-sm">
                                            <thead>
                                            <tr style="background: #81abe0 !important;color:white;">
                                                <th scope="col" width="10" class="text-center">#</th>
                                                <th scope="col">NAME</th>
                                                <th scope="col" width="100" class="text-center">QTY</th>
                                                <th scope="col" width="100" class="text-center">U.PRICE</th>
                                                <th scope="col" class="text-right pr-5">T.PRICE</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer p-0" style="padding: 0 !important;">
                                    <div class="px-3" style="background: rgb(211, 226, 245) !important;">
                                        <table class="table table-borderless mb-0 table-sm">
                                            <tbody>
                                            <tr class="align-middle">
                                                <td class="text-left">Sub Total</td>
                                                <td class="text-right">৳ <span id="subTotal">0.00</span></td>
                                            </tr>
                                            <tr class="align-middle">
                                                <td class="text-left">Discount Amount</td>
                                                <td class="text-right">
                                                    <input type="number" id="customDiscount" style="width: 100px" class="ml-auto border border-primary">
                                                </td>
                                            </tr>
                                            <tr class="align-middle">
                                                <td class="text-left">Discount Percentage</td>
                                                <td class="text-right">
                                                    <input type="number" id="customDiscountPercentage" style="width: 100px" class="ml-auto border border-primary">
                                                </td>
                                            </tr>
                                            <tr class="align-middle">
                                                <td class="text-left">Vat</td>
                                                <td class="text-right">৳ <span id="vatTotal">0.00</span></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer px-0 pt-5 pb-0 border-0 bg-transparent d-flex justify-content-between">
                            <div class="w-50 btn-group-vertical">
                                <button class="btn btn-block btn-outline-danger btn-lg" onclick="cancelOrder(this)"><i class="fa fa-times"></i> Cancel</button>
{{--                                <button class="btn btn-block btn-outline-warning btn-lg" disabled><i class="fa fa-hand-stop-o"></i> Hold Order</button>--}}
                            </div>
                            <button class="w-50 btn btn-primary btn-lg ml-1 PayButton">Receive<br>৳ <span id="grandTotal" style="font-size: 25px;">0</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@include('component.dataTable_resource')
@include('component.message')
{{--<div class="modal fade in" id="newCustomerModal" role="dialog" aria-hidden="true" style="display: none; padding-right: 15px;">--}}
{{--    <div class="modal-dialog modal-dialog-centered modal-md" role="document">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header" style="background: #81abe0 !important;">--}}
{{--                <h5 class="modal-title" style="color:white;">New Customer</h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                    <i aria-hidden="true" class="ki ki-close"></i>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--            <div class="modal-body" id="newCustomerModalBody">--}}
{{--                <div class="normal-table-list">--}}
{{--                    <div class="bsc-tbl">--}}
{{--                        <form method="POST" id="newCustomerForm" action="{{ route('admin.poscustomer.store') }}" accept-charset="UTF-8"--}}
{{--                            enctype="multipart/form-data">--}}
{{--                            @csrf--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-12 col-sm-12">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="name">Name <span style="color: red; font-size: 20px;"><sub>*</sub></span></label>--}}
{{--                                        <input class="form-control" id="name" value="{{ old('name') }}"--}}
{{--                                            autocomplete="off" name="name" type="text">--}}
{{--                                        @error('name')--}}
{{--                                        <strong class="text-danger" role="alert">--}}
{{--                                            <span>{{ $message }}</span>--}}
{{--                                        </strong>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-12 col-sm-12">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="email">Email</label>--}}
{{--                                        <input class="form-control" id="email" value="{{ old('email') }}"--}}
{{--                                            autocomplete="off" name="email" type="email">--}}
{{--                                        @error('email')--}}
{{--                                        <strong class="text-danger" role="alert">--}}
{{--                                            <span>{{ $message }}</span>--}}
{{--                                        </strong>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-12 col-sm-12">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="phone">Phone</label>--}}
{{--                                        <input class="form-control" id="phone" value="{{ old('phone') }}"--}}
{{--                                            autocomplete="off" name="phone" type="text">--}}
{{--                                        @error('phone')--}}
{{--                                        <strong class="text-danger" role="alert">--}}
{{--                                            <span>{{ $message }}</span>--}}
{{--                                        </strong>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-12 col-sm-12">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="address">Address</label>--}}
{{--                                        <textarea name="address" class="form-control" id="address"></textarea>--}}
{{--                                        @error('address')--}}
{{--                                        <strong class="text-danger" role="alert">--}}
{{--                                            <span>{{ $message }}</span>--}}
{{--                                        </strong>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-12 align-self-center">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <div class="checkbox-inline">--}}
{{--                                            <label class="checkbox checkbox-outline checkbox-success">--}}
{{--                                                <input value="1" type="checkbox" checked id="addedItemCheckbox" name="status"--}}
{{--                                                    class="i-checks">--}}
{{--                                                <span></span>--}}
{{--                                                Status--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                        @error('status')--}}
{{--                                        <strong class="text-danger" role="alert">--}}
{{--                                            <span>{{ $message }}</span>--}}
{{--                                        </strong>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <input type="hidden" id="vendorId" value="{{ auth()->user()->vendor_id }}" name="vendor_id">--}}
{{--                            <button type="button" id="saveNewCustomer" class="btn btn-primary">Save Data</button>--}}
{{--                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- /.modal-dialog -->--}}
{{--</div>--}}
<div class="modal fade" id="sale_invoice_receipt" role="dialog" style="overflow-y: scroll">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background: #81abe0 !important;">
                <h5 class="modal-title" style="color:white;">Sale Receipt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                    <i aria-hidden="true" class="fa fa-close"></i>
                </button>
            </div>

            <div class="modal-body">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="printElement($('#sale_invoice_receipt').find('.modal-body'))"><i class="fa fa-print"></i> Print</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="held_orders" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background: #81abe0 !important;">
                <h5 class="modal-title" style="color:white;">Held Orders</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                    <i aria-hidden="true" class="fa fa-close"></i>
                </button>
            </div>

            <div class="modal-body">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="due_orders" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background: #81abe0 !important;">
                <h5 class="modal-title" style="color:white;">Due Orders</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                    <i aria-hidden="true" class="fa fa-close"></i>
                </button>
            </div>

            <div class="modal-body">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="today_sales" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background: #81abe0 !important;">
                <h5 class="modal-title" style="color:white;">Today Sales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                    <i aria-hidden="true" class="fa fa-close"></i>
                </button>
            </div>

            <div class="modal-body">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pending_return_request_modal" role="dialog">
    <div class="modal-dialog modal-xl"  style="width:1250px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background: #81abe0 !important;">
                <h5 class="modal-title" style="color:white;">Pending Return Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                    <i aria-hidden="true" class="fa fa-close"></i>
                </button>
            </div>

            <div class="modal-body">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="returnRequestModal" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background: #81abe0 !important;">
                <h5 class="modal-title" style="color:white;">Product Return Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                    <i aria-hidden="true" class="fa fa-close"></i>
                </button>
            </div>

            <div class="modal-body">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exchangeProductModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background: #81abe0 !important;">
                <h5 class="modal-title" style="color:white;">Exchanging Product Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                    <i aria-hidden="true" class="fa fa-close"></i>
                </button>
            </div>

            <div class="modal-body">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@include('sales.payment_model')

@include('component.metronic.foot')
<script src="{{asset('js/jquery.floatThead.js')}}"></script>
<script src="{!! asset('/pos_assets/js/toastr.min.js') !!}"></script>
{{-- datatable --}}
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/dataTables.autoFill.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/dataTables.colReorder.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/dataTables.fixedColumns.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/dataTables.fixedHeader.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/dataTables.keyTable.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/dataTables.responsive.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/dataTables.rowGroup.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/dataTables.rowReorder.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/dataTables.scroller.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/dataTables.searchBuilder.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/dataTables.searchPanes.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/dataTables.select.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/buttons.flash.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/buttons.html5.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/buttons.print.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/dataTables/js/buttons.colVis.min.js') }}"></script>{{-- datatable --}}
<script>
    $(document).ready(function () {
        calc_total();
        loadRandomProducts();
        checkReturnRequestValidation();
    });

    function loadRandomProducts(searchKey = '', withoutBarcode = '',category_id = 0,brand_id = 0) {
        $.ajax({
            url: 'retail_sell/products',
            method: 'get',
            data: {search_key:searchKey, without_barcode:withoutBarcode, category_id:category_id ,brand_id:brand_id},
            success: function(response) {
                $("#randomProducts").html(response);
            }
        });
    }

    $.ajaxSetup({
        headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
    });
    const POS = {
        saveNewCustomer: function () {
            if(isEmpty($("#newCustomerForm").find('#name').val()) ){
                toastr.error('Name is empty');
                $("#newCustomerForm").find('#name').focus();
                return false;
            }
            var formData = new FormData($("#newCustomerForm")[0]);
            $.ajax({
                url: "{{ route('admin.poscustomer.store') }}",
                type: 'post',
                dataType: 'json',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $.blockUI();
                },
                complete: function(){
                    $.unblockUI();
                },
                success: function(data) {
                    toastr.success('Customer Saved');
                    $("#newCustomerModal").modal('hide');
                    $("#posCustomer").empty().append('<option value="'+data+'">'+$("#newCustomerForm input[name=name]").val()+'</option>').val(data).trigger('change');
                },
                error: function(data) {
                    var errors = data.responseJSON.errors;
                    let toast = '<ul>';
                    $.each(errors, function(index, value) {
                        toast += '<li>'+ value[0] +'</li>'
                    });
                    toast += '</ul>'
                    toastr.error(toast);
                }
            });
        }
    }

    $("#category").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.report.category.Search.ajax") }}",
            dataType: 'json',
            type: 'POST',
            data: function (params) {
                return {search: params.term};
            },
            processResults: function (data, params) {
                return {
                    results: $.map(data, function (item,i) {
                        return {id: i, text: item}
                    }),

                };
            },
            cache: true
        },
        placeholder: 'Search for a Category',
    });

    $(".product-brand").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.report.brand.Search.ajax") }}",
            dataType: 'json',
            type: 'POST',
            data: function (params) {
                return {search: params.term};
            },
            processResults: function (data, params) {
                return {
                    results: $.map(data, function (item,i) {
                        return {id: i, text: item}
                    }),

                };
            },
            cache: true
        },
        placeholder: 'Search for a brand',
    });

    // $(document).off('click', '#newCustomer').on('click', '#newCustomer', function (event) {
    //     $("#newCustomerModal").modal('show');
    // });
    $(document).off('click', '#saveNewCustomer').on('click', '#saveNewCustomer', function (event) {
        POS.saveNewCustomer();
    });
    $(document).off('click', '#menuTodaySales').on('click', '#menuTodaySales', function (event) {
        $("#today_sales .modal-body").load('{!! url('/admin/retail_sell/today_sales/') !!}',function(){
            $("#today_sales").modal({show:true});
        });
    });
    $(document).off('click', '#menuDueOrders').on('click', '#menuDueOrders', function (event) {
        $("#due_orders .modal-body").load('{!! url('/admin/retail_sell/due_orders/') !!}',function(){
            $("#due_orders").modal({show:true});
        });
    });
    $(document).off('click', '#pendingRequestModalButton').on('click', '#pendingRequestModalButton', function (event) {
        $("#pending_return_request_modal .modal-body").load('{!! url('/admin/retail_sell/pending-request') !!}',function(){
            $("#pending_return_request_modal").modal({show:true});
        });
    });
    $(document).off('click', '#addRequestModalButton').on('click', '#addRequestModalButton', function (event) {
        $("#returnRequestModal .modal-body").load('{!! url('/admin/retail_sell/return-request') !!}',function(){
            $("#returnRequestModal").modal({show:true});
            checkReturnRequestValidation();
        });
    });
    $(document).off('click', '#backFromExchangeDetail, #backFromReturnedProductDetail').on('click', '#backFromExchangeDetail, #backFromReturnedProductDetail', function (event) {
        $("#returnRequestModal .modal-body").load('{!! url('/admin/retail_sell/return-request') !!}',function(){
            checkReturnRequestValidation();
        });
    });
    $(document).off('click', '#backFromRequestDetail, #backFromEditRequest').on('click', '#backFromRequestDetail, #backFromEditRequest', function (event) {
        $("#pending_return_request_modal .modal-body").load('{!! url('/admin/retail_sell/pending-request') !!}');
    });
    $(document).off('click', '.edit-return-request').on('click', '.edit-return-request', function (event) {
        $("#pending_return_request_modal .modal-body").load('{!! url('/admin/retail_sell/edit-return-request/') !!}'+$(this).data('key'),function(){
            checkReturnRequestValidation();
            $("#return_product_search_field").focus();
            $("#return_product_search_field").trigger('keyup');
        });
    });

    $(document).off('click', '.exchanged-product-show').on('click', '.exchanged-product-show', function (event) {
        $.ajax({
            url: "{!! url('/admin/retail_sell/exchange-product-detail')  !!}",
            type: 'get',
            data: {barcode: $("#exchanged_product_barcode").val()},
            dataType: 'html',
            beforeSend: function(){
                $.blockUI();
            },
            complete: function(){
                $.unblockUI();
            },
            success: function(data) {
                $("#returnRequestModal .modal-body").html(data);
            },
            error: function(data) {

            }
        });
    });
    var rtn_bar_check = false;
    var ex_bar_check = false;
    $(".instant_exchange_element").hide();
    $("#return_product_search_field").focus();
    function checkReturnRequestValidation() {
        if($("#return_product_search_field").val() == '') rtn_bar_check = false;
        if($("#exchanged_product_barcode").val() == '') ex_bar_check = false;
        $("#submit_request_btn").prop("disabled", true);
        if($("#request_type :selected").val() != '' && $("#request_type :selected").val() != undefined){
            if($("#instant_exchange_checkbox").is(':checked')){
                if(rtn_bar_check && ex_bar_check){
                    $("#submit_request_btn").prop("disabled", false);
                }
            } else {
                if(rtn_bar_check) $("#submit_request_btn").prop("disabled", false);
            }
        }
    }
    $(document).off('click', '.product-show').on('click', '.product-show', function (event) {
        $.ajax({
            url: "{{ url('admin/retail_sell/return-request-detail') }}",
            type: 'get',
            data: {return_product_id: '', return_product_barcode: $("#return_product_search_field").val()},
            dataType: 'html',
            beforeSend: function(){
                $.blockUI();
            },
            complete: function(){
                $.unblockUI();
            },
            success: function(data) {
                $("#returnRequestModal .modal-body").html(data);
            },
            error: function(data) {

            }
        });
    });
    $(document).off('click', '.return-detail').on('click', '.return-detail', function () {
            var data = {return_product_id: $(this).data('key'), return_product_barcode: $(this).data('rtnBarcode')}
            $.ajax({
                url: "{{ url('admin/retail_sell/return-request-detail') }}",
                type: 'get',
                data: data,
                dataType: 'html',
                beforeSend: function(){
                    $.blockUI();
                },
                complete: function(){
                    $.unblockUI();
                },
                success: function(data) {
                    $("#pending_return_request_modal .modal-body").html(data);
                },
                error: function(data) {


                }
            });
        })
    $(document).off('change', '#request_type').on('change', '#request_type', function () {
        if ($(this).val() == 'exchange') {
            $(".instant_exchange_element").show();
            $("#exchanged_product_barcode_elem").toggle($("#instant_exchange_checkbox").is(':checked'));
        } else {
            $("#instant_exchange_checkbox").prop( "checked", false );
            $(".instant_exchange_element").hide();
        }
        checkReturnRequestValidation();
    })
    $(document).off('click', '#instant_exchange_checkbox').on('click', '#instant_exchange_checkbox', function () {
        $("#exchanged_product_barcode_elem").toggle($(this).is(':checked'));
        checkReturnRequestValidation()
    })


    $(document).off('click', '#submit_request_btn').on('click', '#submit_request_btn', function (e) {
        if($("#reason").val() == ''){
            toastr.error("Reason field is mandatory");
            $("#reason").focus();
            return false;
        }
        var formData = $('#product_return_form').serialize();
        $.ajax({
            url: "{{ url('admin/retail_sell/return-request-submit') }}",
            type: 'post',
            data: formData,
            dataType: 'json',
            processData: false,
            beforeSend: function(){
                $.blockUI();
            },
            complete: function(){
                $.unblockUI();
            },
            success: function(data) {
                if(data.code == 1){
                    toastr.success(data.msg)
                    $("#returnRequestModal").modal('hide');
                    $('#pendingRequestModalButton').trigger('click');
                } else {
                    toastr.error(data.msg)
                }
            },
            error: function(data) {

            }
        });
    })
    $(document).off('keyup', '#return_product_search_field').on('keyup', '#return_product_search_field', function (e) {
        var elem = $("#return_product_search_field");
        var text = elem.val();

        var id = $("#product_return_form").find("input[name='id']").val();
        if(text == 0 || text == ''){
            elem.siblings(".product-not-found-alert").hide();
            elem.siblings(".product-found-alert").hide();
            elem.siblings(".product-quantity-text-alert").hide();
            elem.siblings(".product-quantity-alert").hide();
            elem.siblings(".product-show").hide();
            checkReturnRequestValidation()
        }
        if (text) {
            $.post("{{ route('admin.return.product.info.with.ajax') }}", {
                text: text,
                id: id
            }, function (res) {
                if (res.code == 0) {
                    rtn_bar_check = false;
                    elem.siblings(".product-not-found-alert").text(res.error);
                    elem.siblings(".product-not-found-alert").show();
                    elem.siblings(".product-found-alert").hide();
                    elem.siblings(".product-quantity-text-alert").hide();
                    elem.siblings(".product-quantity-alert").hide();
                    elem.siblings(".product-show").hide();
                } else {
                    rtn_bar_check = true;
                    elem.siblings(".product-not-found-alert").hide();
                    elem.siblings(".product-show").show();
                    elem.siblings(".product-found-alert").show();
                    elem.siblings(".product-quantity-text-alert").show();
                    elem.siblings(".product-quantity-text-alert").find('.product-quantity-alert').text(res.available_qty);
                    elem.siblings(".product-quantity-text-alert").find('.product-quantity-alert').show();
                }
                checkReturnRequestValidation()
            });
        }

    })
    $(document).off('keyup', '#exchanged_product_barcode').on('keyup', '#exchanged_product_barcode', function (e) {
        var elem = $("#exchanged_product_barcode");
        var text = elem.val();
        if(text == 0 || text == ''){
            elem.siblings(".product-not-found-alert").hide();
            elem.siblings(".product-found-alert").hide();
            elem.siblings(".product-quantity-text-alert").hide();
            elem.siblings(".product-quantity-alert").hide();
            elem.siblings(".exchanged-product-show").hide();
            checkReturnRequestValidation()
        }
        if (text) {
            $.post("{{ route('admin.exchanged.product.info.with.ajax') }}", {text: text}, function (res) {
                if (res.code == 0) {
                    ex_bar_check = false;
                    elem.siblings(".product-not-found-alert").text(res.error);
                    elem.siblings(".product-not-found-alert").show();
                    elem.siblings(".product-found-alert").hide();
                    elem.siblings(".product-quantity-text-alert").hide();
                    elem.siblings(".product-quantity-alert").hide();
                    elem.siblings(".exchanged-product-show").hide();
                } else {
                    ex_bar_check = true;
                    elem.siblings(".product-not-found-alert").hide();
                    elem.siblings(".exchanged-product-show").show();
                    elem.siblings(".product-found-alert").show();
                    elem.siblings(".product-quantity-text-alert").show();
                    elem.siblings(".product-quantity-text-alert").find('.product-quantity-alert').text(res.available_qty);
                    elem.siblings(".product-quantity-text-alert").find('.product-quantity-alert').show();
                }
                checkReturnRequestValidation();
            });
        }

    })

    $("#return_product_search_field").trigger('keyup');

    $(document).off('click', '.delete-return-request').on('click', '.delete-return-request', function () {
        var url = "{{url('admin/retail_sell/destroy-return-request')}}/"+$(this).data('key');
        if(confirm("Confirm to delete")){
            $.ajax({
                url: url,
                type: 'delete',
                dataType: 'json',
                beforeSend: function(){
                    $.blockUI();
                },
                complete: function(){
                    $.unblockUI();
                },
                success: function(data) {
                    $("#pendingRequestModalButton").trigger('click');
                    toastr.success(data.msg);
                },
                error: function(data) {
                    toastr.error('Failed to delete');
                }
            });
        };
    })
    //existing
    $('table.table').floatThead({
        position: 'fixed',
        scrollContainer: true
    });

    $(".customer").select2({
        width: '100%',
        allowClear: true,
        ajax: {
            url: "{{ route("admin.mp_customer.list") }}",
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {search: params.term};
            },
            processResults: function (data, params) {
                return {
                    results: $.map(data, function (item,i) {
                        return {id: i, text: item}
                    }),

                };
            },
            cache: true
        },
        placeholder: 'Search for a customer',
    });

    function everySingleProduct(element, id) {

        var every_prduct = $(".every_single_product" + id);

        var product_id = every_prduct.find('.product_id').text();
        var product_name = every_prduct.find('.product_name').text();

        var attribute_id = every_prduct.find('.attribute_id').text();
        var attribute_name = every_prduct.find('.attribute_name').text();

        var attribute_map_id = every_prduct.find('.attribute_map_id').text();
        var attribute_map_name = every_prduct.find('.attribute_map_name').text();

        var warehouse_id = every_prduct.find('.warehouse_id').text();
        var per_price = every_prduct.find('.per_price').text();
        var min_price = every_prduct.find('.min_price').text();
        var vat = every_prduct.find('.vat').text();
        var stock_detail_id = every_prduct.find('.stock_detail_id').text();
        var stocked_product_barcode_id = every_prduct.find('.stocked_product_barcode_id').text();
        var available_quantity = every_prduct.find('.available_quantity').text();
        var html = `<tr class="align-middle parentTR everyTr` + stock_detail_id + `">
                        <td width="10" scope="row">
                            <a class="btn btn-icon btn-sm btn-hover-light-danger btn-circle btn-circle"
                               href="#" onclick="everyTrRemove(this, ` + stock_detail_id + `)">
                                <i class="fa fa-trash"></i></a>
                        </td>
                        <td>`+product_name+`</td>
                        <input type="hidden" class="get_stock_detail_id" name="stock_detail_id[]" value="` + stock_detail_id + `">
                        <input type="hidden" class="get_stocked_product_barcode_id" name="stocked_product_barcode_id[]" value="` + stocked_product_barcode_id + `">
                        <input type="hidden" class="product_id" name="product_id[]" value="` + product_id + `">
                        <input type="hidden" class="warehouse_id" name="warehouse_id[]" value="` + warehouse_id + `">
                        <input type="hidden" class="attribute_id" name="attribute_id[]" value="` + attribute_id + `">
                        <input type="hidden" class="attribute_map_id" name="attribute_map_id[]" value="` + attribute_map_id + `">
                        <input type="hidden" class="min_price" name="min_price[]" value="` + min_price + `">
                        <input type="hidden" class="total_min_price" name="total_min_price[]" value="` + (min_price * 1) + `">
                        <input type="hidden" class="total" name="total[]" value="` + (per_price * 1) + `">
                        <input type="hidden" class="vat" name="vat[]" value="` + (vat * 1) + `">
                        <td class="text-center">
                            <div class="d-flex w-100 justify-content-center" style="width:100px !important;">
                                <button
                                    class="btn btn-icon btn-light-primary btn-circle btn-sm btnMinus"><i
                                        class="fa fa-minus"></i></button>
                                <input type="number" value="1" min="1" max="`+ available_quantity +`" name="quantity[]"
                                       class="w-50px text-center no-arrow border-0 bg-transparent listed-item-qty">
                                <button
                                    class="btn btn-icon btn-light-primary btn-circle btn-sm btnPlus"><i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </td>
                         <td class="text-right pr-5">
                            <input type="number" value="`+ (per_price) +`" name="u_price[]" class="form-control u_price">
                        </td>
                        <td class="text-right pr-5">
                            <span class="amount">`+ (per_price * 1) +`</span>
                        </td>
                    </tr>`;

        //end==> check current tab
        if (available_quantity > 0) {
            var cartList = $("#cartList");
            var findTr = cartList.find(".everyTr" + stock_detail_id + "");
            if (findTr.length > 0) {
                findTr.find('.get_stocked_product_barcode_id').val(findTr.find('.get_stocked_product_barcode_id').val() + ',' +stocked_product_barcode_id);
                findTr.find('.btnPlus').trigger('click');
                var category_id = $('#category :selected').val();
                var brand_id = $('#productBrand :selected').val();
                loadRandomProducts($('#searchOrBarcode').val(), findTr.find('.get_stocked_product_barcode_id').val(),category_id,brand_id);
            } else {
                cartList.find('tbody').append(html);
                findTr = cartList.find(".everyTr" + stock_detail_id + "");
                var category_id = $('#category :selected').val();
                var brand_id = $('#productBrand :selected').val();
                loadRandomProducts($('#searchOrBarcode').val(), findTr.find('.get_stocked_product_barcode_id').val(),category_id,brand_id);
                calc_total();
            }
        } else {
            toastr.error('Quantity is not available')
        }

    }
    function calc_total(total = 0) {
        total = vat = grandTotal = 0;
        $('input.total').each(function (k, v) {
            total += parseFloat($(v).val());
        });
        $('input.vat').each(function (k, v) {
            vat += parseFloat($(v).val());
        });
        var customDiscount = parseFloat($("#customDiscount").val()) || 0;
        if (customDiscount > 0) {
            var total_min_price = 0;
            $('input.total_min_price').each(function (k, v) {
                total_min_price += parseFloat($(v).val());
            });

            if ((total - total_min_price) < customDiscount) {
                toastr.error('You cannot give discount more than ' + (total - total_min_price));
                $("#customDiscount").val(0);
                return calc_total();
            }
        } else if (customDiscount < 0) {
            $("#customDiscount").val(0);
            return calc_total();
        }

        //Discount percentage
        var customDiscountPercentage = parseFloat($("#customDiscountPercentage").val()) || 0;
        if (customDiscountPercentage > 0) {
            var total_min_price = 0;
            $('input.total_min_price').each(function (k, v) {
                total_min_price += parseFloat($(v).val());
            });
            if (total == 0) {
                toastr.error('You cannot give discount more than 0 percentage');
                $("#customDiscountPercentage").val(0);
                return calc_total();
            }
            if ((total - total_min_price) < total /100 * customDiscountPercentage) {
                toastr.error('You cannot give discount more than ' + (total - total_min_price));
                $("#customDiscountPercentage").val(0);
                return calc_total();
            }
        } else if (customDiscountPercentage < 0) {
            $("#customDiscountPercentage").val(0);
            return calc_total();
        }
        if (customDiscountPercentage == 0){
            grandTotal = (total + vat) - customDiscount;
        }else if (customDiscount == 0){
            var divide = 100-customDiscountPercentage;
            grandTotal = (total + vat) /100*divide;
        }

        $("#subTotal").html(total);
        $("#vatTotal").html(vat);
        $("#grandTotal").html(grandTotal.toFixed(2));

        if (grandTotal <= 0) {
            $("#grandTotal").closest('.btn').prop('disabled', true);
        } else {
            $("#grandTotal").closest('.btn').prop('disabled', false);
        }
    }
    function everyTrRemove(element, id) {
        $(element).closest('tbody').find('.everyTr' + id).remove();
        calc_total();
    }
    $(document).on('click', '.btnMinus', function () {
        $(this).parent().find('input').get(0).value--;
        $(this).parent().find('input').trigger('change');
    });
    $(document).on('click', '.btnPlus', function () {
        $(this).parent().find('input').get(0).value++;
        $(this).parent().find('input').trigger('change');
    });
    $(document).on('keyup', '#customDiscount', function (e) {
        calc_total();
        if (e.keyCode === 13) {
            $('.PayButton').focus();
        }
    });
    $(document).on('keyup', '#customDiscountPercentage', function (e) {
        calc_total();
        if (e.keyCode === 13) {
            $('.PayButton').focus();
        }
    });
    $(document).on('change', '.listed-item-qty:visible', function () {
        var parent = $(this).parents(".parentTR");
        var qty = parent.find('.listed-item-qty').val();
        var max_qty = parent.find('.listed-item-qty').attr('max');
        var unit_price = parent.find('.u_price').val();

        if (parseInt(max_qty) >= parseInt(qty) && parseInt(qty) > 0) {
            var per_price = parent.find('.per_price').val();
            var total_min_price = parent.find('.min_price').val();
            parent.find('.amount').html((parseFloat(qty) * parseFloat(unit_price)));
            parent.find('.total').val((parseFloat(qty) * parseFloat(unit_price)));
            parent.find('.total_min_price').val((parseFloat(qty) * parseFloat(total_min_price)));
            calc_total()
        } else {
            parent.find('.listed-item-qty').val(1);
            parent.find('.listed-item-qty').trigger('change');
            toastr.error('Only ' + max_qty + ' items are available ')
        }
    });

    $(document).on('keyup', '.u_price:visible', function () {
        var parent = $(this).parents(".parentTR");
        var qty = parent.find('.listed-item-qty').val();
        var max_qty = parent.find('.listed-item-qty').attr('max');
        var unit_price = parent.find('.u_price').val();
        $('.per_price').on('change', function() {
            $('.per_price').val(1);
        });

        if (parseInt(max_qty) >= parseInt(qty) && parseInt(qty) > 0) {
            var per_price = parent.find('.per_price').val();
            var total_min_price = parent.find('.min_price').val();
            parent.find('.amount').html((parseFloat(qty) * parseFloat(unit_price)));
            parent.find('.total').val((parseFloat(qty) * parseFloat(unit_price)));
            parent.find('.total_min_price').val((parseFloat(qty) * parseFloat(total_min_price)));
            calc_total()
        } else {
            parent.find('.listed-item-qty').val(1);
            parent.find('.listed-item-qty').trigger('change');
            toastr.error('Only ' + max_qty + ' items are available ')
        }
    });

    $("#customDiscount").on('keyup', function () {
        $("#customDiscountPercentage").val('');
        var discount_amount = $(this).val();
        if (discount_amount >= 0 && discount_amount != ''){
            $("#customDiscountPercentage").attr('disabled','disabled');
        }else {
            $("#customDiscountPercentage").removeAttr('disabled');
        }
    });
    $("#customDiscountPercentage").on('keyup', function () {
        $("#customDiscount").val('');
        var discount_percentage = $(this).val();
        if (discount_percentage >= 0 && discount_percentage != ''){
            $("#customDiscount").attr('disabled','disabled');
        }else {
            $("#customDiscount").removeAttr('disabled');
        }
    });

    $(document).on('click', '.PayButton:visible', function (e) {
        e.preventDefault();
        var cartList = $("#cartList");
        var getEveryTR = cartList.find('.parentTR');

        var get_stock_detail_id = getEveryTR.find("input[name='stock_detail_id[]']").map(function () {
            return parseInt($(this).val());
        }).get();
        var get_stocked_product_barcode_id = getEveryTR.find("input[name='stocked_product_barcode_id[]']").map(function () {
            return $(this).val();
        }).get();

        var product_id_arr = getEveryTR.find("input[name='product_id[]']").map(function () {
            return parseInt($(this).val());
        }).get();

        var warehouse_id_arr = getEveryTR.find("input[name='warehouse_id[]']").map(function () {
            return parseInt($(this).val());
        }).get();

        var attribute_id_arr = getEveryTR.find("input[name='attribute_id[]']").map(function () {
            return $(this).val();
        }).get();

        var attribute_map_id_arr = getEveryTR.find("input[name='attribute_map_id[]']").map(function () {
            return $(this).val();
        }).get();

        var quantity_arr = getEveryTR.find("input[name='quantity[]']").map(function () {
            return parseInt($(this).val());
        }).get();

        var per_price_arr = getEveryTR.find("input[name='u_price[]']").map(function () {
            return parseFloat($(this).val());
        }).get();

        // var per_price_arr = getEveryTR.find("input[name='per_price[]']").map(function () {
        //     return parseFloat($(this).val());
        // }).get();

        var total_arr = getEveryTR.find("input[name='total[]']").map(function () {
            return parseFloat($(this).val());
        }).get();
        //

        var sub_total = parseFloat(cartList.closest('.card').find("#subTotal").text());
        var final_total = parseFloat($("#grandTotal").text());
        var tax = parseFloat(cartList.closest('.card').find("#vatTotal").text());
        var shipping_charge = 0; // no shipping charge in POS
        var discount = parseFloat(cartList.closest('.card').find("#customDiscount").val()) || 0;
        var discount_percentage = parseFloat(cartList.closest('.card').find("#customDiscountPercentage").val()) || 0;
        var customer_id = $("#Customer").val();
        var customer_name = $("#Customer :selected").text();
        // var pos_customer_name = $(".pos_customer_id").find("option:selected").text();
        // var pos_customer_id = $(".pos_customer_id").val();
        // alert("Selected Text: " + pos_customer_name + " Value: " + pos_customer_id);

        $.post("{{ route('admin.retail.store.sale.value') }}",
            {
                customer_id: customer_id,
                stock_detail_id: get_stock_detail_id,
                stocked_product_barcode_id: get_stocked_product_barcode_id,
                product: product_id_arr,
                warehouse: warehouse_id_arr,
                attribute: attribute_id_arr,
                attribute_map: attribute_map_id_arr,
                quantity: quantity_arr,
                per_price: per_price_arr,
                total: total_arr,
                sub_total: sub_total,
                tax: tax,
                shipping_charge: shipping_charge,
                discount: discount,
                discount_percentage: discount_percentage,
                final_total: final_total,
                dataType: 'json',
            },
            function (res) {

                //start==> if quantity not available
                if (res.quantity_error) {
                    checkCurrentTab().find('.everyTr' + res.quantity_error.stock_detail_id).remove();
                    calc_total();
                    toastr.error('Thats product quantity not Available')
                }
                //end==> if quantity not available

                if (res.success) {
                    toastr.success("Sale Create done");
                    //strat===> now we need to empty all
                    cartList.find('tbody').html('');
                    cartList.closest('.card').find('#customDiscount').val('');
                    cartList.closest('.card').find('#customDiscountPercentage').val('');
                    cartList.closest('.card').find('#subTotal').text('0.00');
                    cartList.closest('.card').find('#vatTotal').text('0.00');
                    cartList.closest('.card').find('#grandTotal').text('0.00');

                    var category_id = $('#category :selected').val();
                    var brand_id = $('#productBrand :selected').val();
                    loadRandomProducts($('#searchOrBarcode').val(),'',category_id,brand_id);
                    // loadRandomProducts();
                    calc_total();

                    //$(".reloadbyAjax").load(location.href + " .reloadbyAjax");
                    //strat===> now we need to empty all

                    // $(".payment_model").modal('show');
                    // $(".p_c_name").html(pos_customer_name + `<input type="hidden" class="p_pos_customer_id" value="` + pos_customer_id + `">`);
                    // $(".sale_pro_item").text("Items:" + product_id_arr.length);
                    // $(".sale_total").text(final_total);
                    // $(".pay_input_field").val(final_total);
                    // $(".last_sale_id").val(res.success);
                    // setTimeout(function() {
                    //     $(".pay_input_field").focus();
                    // }, 1500);

                    openPaymentModal(res.success);

                }
                if (res.errors) {
                    for (var i = 0; i < res.errors.length; i++) {
                        toastr.error(res.errors[i])
                    }
                }
            });

    });

    $(document).on('keyup', '#searchOrBarcode', function (e) {
        var category_id = $('#category :selected').val();
        var brand_id = $('#productBrand :selected').val();
        loadRandomProducts($(this).val(),'',category_id,brand_id);
        if(e.which === 13) {
            if ($(this).val().length > 0) {
                var firstProduct = $("#randomProducts div:first");
                if (firstProduct.length > 0) {
                    firstProduct.trigger('click');
                    toastr.success('Product added');
                    $(this).val('');
                }
            }
        }
    });
    // $(document).on('keyup', '#searchOrBarcode', function () {
        //
    // });
    $(document).on('change', "#category", function () {
        var brand_id = $('#productBrand :selected').val();
        loadRandomProducts($('#searchOrBarcode').val(),'',$(this).val(),brand_id);
    });

    $(document).on('change', '#productBrand', function (e) {
        var category_id = $('#category :selected').val();
        loadRandomProducts($('#searchOrBarcode').val(),'',category_id,$(this).val());
    });
</script>
<script>
    $(document).on('change', ".payment_type", function () {
        var value = $(this).val();

        if (value == 'cash')
        {
            $(".appendCardInput").hide();
            $(".appendCheckInput").hide();
        }else if (value == 'card')
        {
            var html = `<div class="form-group">
                                <label for="formGroupExampleInput2">Card Name</label>
                                <input type='text'  class='form-control card_name'>
                            </div>
                            <div class="form-group">
                                <label for="formGroupExampleInput2">Card Number</label>
                                <input type='text' class='form-control card_number'>
                            </div>`;

            $(".appendCheckInput").hide();
            $(".appendCardInput").show().html(html);
        }else if (value == 'check')
        {
            var html = ` <div class="form-group">
                                <label for="formGroupExampleInput2">Check No</label>
                                <input type='text' class='form-control check_no'>
                            </div>`;

            $(".appendCheckInput").show().html(html);
            $(".appendCardInput").hide();
        }

    });

    $(document).on('change keyup', ".pay_input_field", function (e) {
        var val = $(this).val();
        var total = $(".sale_due").text();
        if (parseFloat(val) > parseFloat(total))
        {
            $(".give_back").html(`Give Back: `+(parseFloat(val) - parseFloat(total))+` <input type="hidden" class="back" value="`+(parseFloat(val) - parseFloat(total))+`">`);
        }else{
            $(".give_back").html(`Due: `+(parseFloat(total) - parseFloat(val))+` <input type="hidden" class="due" value="`+(parseFloat(total) - parseFloat(val))+`">`);
        }
        if (e.keyCode === 13) {
            $('.payment_submit').focus();
        }
    });

    // payment model
    $(".payment_submit").on('click', function () {
        var payment_type = $(".payment_type :selected").val();
        if (payment_type == 'cash')
        {
            var card_name = null;
            var card_number = null;
            var check_no = null;
        }else if(payment_type == 'card')
        {
            var card_name = $(".card_name").val();
            var card_number = $(".card_number").val();
            var check_no = null;
        }else if(payment_type == 'check')
        {
            var card_name = null;
            var card_number = null;
            var check_no = $(".check_no").val();
        }
        // var pos_customer_id = $(".p_pos_customer_id").val();
        var customer_id = $(".customer_id").val();
        var last_sale_id = $(".last_sale_id").val();
        var payment_amount = $(".pay_input_field").val();
        var due_amount = $("input[name=sale_due]").val();
        var total = $(".sale_total").text();
        var due = 0;
        if ( (parseFloat(payment_amount) > parseFloat(total)))
        {
            var due = 0;
            var back = !isEmpty($(".back").val()) ? $(".back").val() : 0;
            var status = "FP";
        }else if( parseFloat(payment_amount) == parseFloat(due_amount) ){
            var due = 0;
            var back = 0;
            var status = 'FP'
        }else{
            var due = !isEmpty($(".due").val()) ? $(".due").val() : 0;
            var back = 0;
            var status = 'PP'
        }

        if (payment_type == null)
        {
            toastr.error('Please select Payment Type');
            return false;
        }


        $.post("{{ route('admin.retail_sale.payment.store.with_ajax') }}",
            {
                last_sale_id: last_sale_id,
                // pos_customer_id: pos_customer_id,
                customer_id: customer_id,
                card_name: card_name,
                card_number: card_number,
                check_no: check_no,
                payment_amount: payment_amount,
                payment_type: payment_type,
                due: due,
                back: back,
                status: status,
                final_total: total,
            },
            function (res) {
                if (res == 'payment_success')
                {
                    toastr.success('Payment Success !');
                    var last_sale_id = $(".last_sale_id").val();
                    $(".payment_type").val('');
                    $(".p_pos_customer_id").val('');
                    $(".last_sale_id").val('');
                    $(".pay_input_field").val('');
                    $(".give_back").html('');
                    $(".sale_total").text('');
                    $(".appendCardInput").hide();
                    $(".appendCheckInput").hide();

                    $("#sale_invoice_receipt .modal-body").load('{!! url('/admin/retail_sell/receipt') !!}' + '/' + last_sale_id,function(){
                        $("#sale_invoice_receipt").modal({show:true});
                        $(".payment_model").modal('hide');
                    });
                }
            });
    });
    {{--function openPaymentModal(saleId) {--}}
    {{--    $(".payment_model .modal-body").load('{!! url('/admin/retail_sell/payment_option') !!}' + '/' + saleId,function(){--}}
    {{--        $(".payment_model").modal({show:true});--}}

    {{--        setTimeout(function() {--}}
    {{--            $(".pay_input_field").focus();--}}
    {{--        }, 1500);--}}
    {{--    });--}}
    {{--}--}}

    function cancelOrder(element) {
        $(".parentTR").remove();
        calc_total();
    }

</script>

</body>
</html>
