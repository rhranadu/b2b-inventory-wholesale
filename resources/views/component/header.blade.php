<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
    <!-- font awesome CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/font-awesome.min.css') }}">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/owl.theme.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/owl.transitions.css') }}">

    <!-- meanmenu CSS
     ============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/meanmenu/meanmenu.min.css') }}">

    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/animate.css') }}">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/normalize.css') }}">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/scrollbar/jquery.mCustomScrollbar.min.css') }}">
    <!-- wave CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/wave/waves.min.css') }}">
    <!-- Notika icon CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/notika-custom-icon.css') }}">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/main.css') }}">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/style.css') }}">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/responsive.css') }}">
    <!-- modernizr JS
		============================================ -->
    <script src="{{ asset('backend/js/vendor/modernizr-2.8.3.min.js') }}"></script>

    @stack('css')

<!-- toastr css
		============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/toastr.min.css') }}">
    <!-- Data Table JS
          ============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/jquery.dataTables.min.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/custom.css') }}">


</head>

<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<!-- Start Header Top Area -->
<div class="header-top-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="logo-area">
                    <a href="{{Auth::user()->user_type_id == 1 ? route('superadmin.dashboard') :  route('admin.dashboard') }}"><img src="{{ asset('backend/img/logo/logo.png') }}" alt="" /></a>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="header-top-menu">
                    <ul class="nav navbar-nav notika-top-nav nav-item dropdown">
                         <li class="nav-item dropdown">
                            <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                <span>
                                    @if(auth()->check())
                                        {{ auth()->user()->name }}
                                    @endif
                                </span>
                            </a>
                            <div role="menu" class="dropdown-menu message-dd animated zoomIn">
                                <div class="hd-mg-tt">
                                    <h2></h2>
                                </div>
                                <div class="hd-message-info">
                                    <a href="{{ route('user.profile', auth()->id() ?? '' ) }}">
                                        <div class="hd-message-sn">
                                            <div class="hd-message-img">
                                                <img style="width: 64px; height: 64px; border: 1px solid mediumspringgreen; border-radius: 50px" src="{{ asset( auth()->user()->image ?? ''   ) }}" alt="" />
                                            </div>
                                            <div class="hd-mg-ctn">
                                                <h3>
                                                    @if(auth()->check())
                                                        {{ auth()->user()->name }}
                                                    @endif
                                                </h3>
                                                <p>Your Profile</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('form-logout').submit()">
                                        <div class="hd-message-sn">
                                            <div class="hd-message-img">
                                                <i class="fa fa-sign-out-alt"></i>
                                            </div>
                                            <div class="hd-mg-ctn">
                                                <h3>Logout</h3>
                                            </div>
                                        </div>

                                        <form style="display: none" action="{{ route('logout') }}" id="form-logout" method="post">
                                            @csrf
                                            <button type="submit"></button>
                                        </form>

                                    </a>

                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                @if(Auth::user()->user_type_id == 1)
                    <div class="header-top-menu">
                        <ul class="nav navbar-nav notika-top-nav nav-item dropdown">
                            <li class="nav-item dropdown">
                                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                    Admin Panel
                                </a>
                                <div role="menu" class="dropdown-menu message-dd animated zoomIn">
                                    <div class="hd-message-info">
                                        <a href="#" onclick="submitAdminPanel('Notika')">
                                            <div class="hd-message-sn">
                                                <div class="hd-mg-ctn">
                                                    <p>Notika</p>
                                                    <p style="background-color: #00c292; padding: 5px"></p>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" onclick="submitAdminPanel('AdminLTE')">
                                            <div class="hd-message-sn">
                                                <div class="hd-mg-ctn">
                                                    <p>AdminLTE</p>
                                                    <p style="background-color: #2a3f54; padding: 5px"></p>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" onclick="submitAdminPanel('Gebtelella')">
                                            <div class="hd-message-sn">
                                                <div class="hd-mg-ctn">
                                                    <p>Gebtelella</p>
                                                    <p style="background-color: #2a3f54; padding: 5px"></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
<!-- End Header Top Area -->


{{--****************** start superadmin link******************--}}
@php($vendor_active = '')
@if (Request::is('superadmin/vendor*', 'superadmin/country*', 'superadmin/state*', 'superadmin/city*','superadmin/user*'))
    @php( $vendor_active = 'active')
@endif

@if(Request::is('superadmin*'))
{{--
    <!-- Mobile Menu start -->
    <div class="mobile-menu-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="mobile-menu">
                        <nav id="dropdown">
                            <ul class="mobile-menu-nav">
                                <li><a data-toggle="collapse" data-target="#Charts" href="#">Dashboard</a>
                                    <ul class="collapse dropdown-header-top">
                                        <li><a href="index.html">Dashboard One</a></li>
                                        <li><a href="index-2.html">Dashboard Two</a></li>
                                        <li><a href="index-3.html">Dashboard Three</a></li>
                                        <li><a href="index-4.html">Dashboard Four</a></li>
                                        <li><a href="analytics.html">Analytics</a></li>
                                        <li><a href="widgets.html">Widgets</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demoevent" href="#">Email</a>
                                    <ul id="demoevent" class="collapse dropdown-header-top">
                                        <li><a href="inbox.html">Inbox</a></li>
                                        <li><a href="view-email.html">View Email</a></li>
                                        <li><a href="compose-email.html">Compose Email</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#democrou" href="#">Interface</a>
                                    <ul id="democrou" class="collapse dropdown-header-top">
                                        <li><a href="animations.html">Animations</a></li>
                                        <li><a href="google-map.html">Google Map</a></li>
                                        <li><a href="data-map.html">Data Maps</a></li>
                                        <li><a href="code-editor.html">Code Editor</a></li>
                                        <li><a href="image-cropper.html">Images Cropper</a></li>
                                        <li><a href="wizard.html">Wizard</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demolibra" href="#">Charts</a>
                                    <ul id="demolibra" class="collapse dropdown-header-top">
                                        <li><a href="flot-charts.html">Flot Charts</a></li>
                                        <li><a href="bar-charts.html">Bar Charts</a></li>
                                        <li><a href="line-charts.html">Line Charts</a></li>
                                        <li><a href="area-charts.html">Area Charts</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demodepart" href="#">Tables</a>
                                    <ul id="demodepart" class="collapse dropdown-header-top">
                                        <li><a href="normal-table.html">Normal Table</a></li>
                                        <li><a href="data-table.html">Data Table</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demo" href="#">Forms</a>
                                    <ul id="demo" class="collapse dropdown-header-top">
                                        <li><a href="form-elements.html">Form Elements</a></li>
                                        <li><a href="form-components.html">Form Components</a></li>
                                        <li><a href="form-examples.html">Form Examples</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#Miscellaneousmob" href="#">App views</a>
                                    <ul id="Miscellaneousmob" class="collapse dropdown-header-top">
                                        <li><a href="notification.html">Notifications</a>
                                        </li>
                                        <li><a href="alert.html">Alerts</a>
                                        </li>
                                        <li><a href="modals.html">Modals</a>
                                        </li>
                                        <li><a href="buttons.html">Buttons</a>
                                        </li>
                                        <li><a href="tabs.html">Tabs</a>
                                        </li>
                                        <li><a href="accordion.html">Accordion</a>
                                        </li>
                                        <li><a href="dialog.html">Dialogs</a>
                                        </li>
                                        <li><a href="popovers.html">Popovers</a>
                                        </li>
                                        <li><a href="tooltips.html">Tooltips</a>
                                        </li>
                                        <li><a href="dropdown.html">Dropdowns</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#Pagemob" href="#">Pages</a>
                                    <ul id="Pagemob" class="collapse dropdown-header-top">
                                        <li><a href="contact.html">Contact</a>
                                        </li>
                                        <li><a href="invoice.html">Invoice</a>
                                        </li>
                                        <li><a href="typography.html">Typography</a>
                                        </li>
                                        <li><a href="color.html">Color</a>
                                        </li>
                                        <li><a href="login-register.html">Login Register</a>
                                        </li>
                                        <li><a href="404.html">404 Page</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu end -->
--}}

    <!-- Main Menu area start-->
    <div class="main-menu-area mg-tb-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro">
                        <li>
                            <a class="" href="{{ route('superadmin.dashboard') }}"><i class="fa fa-home"></i> Dashboard</a>
                        </li>
                        <li class="{{ $vendor_active }}"><a data-toggle="tab" href="#company"><i class="fa fa-cog"></i> company</a></li>
                    </ul>
                    <div class="tab-content custom-menu-content">

                        <div id="admin_user" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                            </ul>
                        </div>
                        <div id="company" class="tab-pane notika-tab-menu-bg animated flipInX {{ $vendor_active }}">
                            <ul class="notika-main-menu-dropdown">
                                @if(auth()->user()->userType->id === 1)
                                    <li>
                                        <a class="{{ Request::is('superadmin/vendor*') ? 'active' : '' }}" href="{{ route('superadmin.vendor.index') }}">Company</a></li>
                                    <li>
                                        <a class="{{ Request::is('superadmin/country*') ? 'active' : '' }}" href="{{ route('superadmin.country.index') }}">Country</a>
                                    </li>
                                    <li>
                                        <a class="{{ Request::is('superadmin/state*') ? 'active' : '' }}" href="{{ route('superadmin.state.index') }}">State</a>
                                    </li>
                                    <li>
                                        <a class="{{ Request::is('superadmin/city*') ? 'active' : '' }}" href="{{ route('superadmin.city.index') }}">City</a>
                                    </li>
                                  {{--  <li >
                                        <a class="{{ Request::is('admin/user/*') ? 'active' : '' }}"href="{{ route('user_type.index') }}"> Users Type</a>
                                    </li>--}}
                                    <li >
                                        <a class=" {{ Request::is('superadmin/user') ? 'active' : '' }}{{ Request::is('superadmin/user/register') ? 'active' : '' }}  "href="{{ route('superadmin.user') }}"> Add Users</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div id="Page" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="contact.html">Contact</a>
                                </li>
                                <li><a href="invoice.html">Invoice</a>
                                </li>
                                <li><a href="typography.html">Typography</a>
                                </li>
                                <li><a href="color.html">Color</a>
                                </li>
                                <li><a href="login-register.html">Login Register</a>
                                </li>
                                <li><a href="404.html">404 Page</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Menu area End-->
@endif
{{--****************** end superadmin link******************--}}





{{--****************** start admin link ******************--}}
@if(Request::is('admin*'))

    @if( Route::current()->getName() != 'admin.sale.create')


        <!-- Mobile Menu start -->
        <div class="mobile-menu-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="mobile-menu">
                            <nav id="dropdown">
                                <ul class="mobile-menu-nav">
                                    <li><a data-toggle="collapse" data-target="#Charts" href="#">Dashboard</a>
                                        <ul class="collapse dropdown-header-top">
                                            <li><a href="index.html">Dashboard One</a></li>
                                            <li><a href="index-2.html">Dashboard Two</a></li>
                                            <li><a href="index-3.html">Dashboard Three</a></li>
                                            <li><a href="index-4.html">Dashboard Four</a></li>
                                            <li><a href="analytics.html">Analytics</a></li>
                                            <li><a href="widgets.html">Widgets</a></li>
                                        </ul>
                                    </li>
                                    <li><a data-toggle="collapse" data-target="#demoevent" href="#">Email</a>
                                        <ul id="demoevent" class="collapse dropdown-header-top">
                                            <li><a href="inbox.html">Inbox</a></li>
                                            <li><a href="view-email.html">View Email</a></li>
                                            <li><a href="compose-email.html">Compose Email</a></li>
                                        </ul>
                                    </li>
                                    <li><a data-toggle="collapse" data-target="#democrou" href="#">Interface</a>
                                        <ul id="democrou" class="collapse dropdown-header-top">
                                            <li><a href="animations.html">Animations</a></li>
                                            <li><a href="google-map.html">Google Map</a></li>
                                            <li><a href="data-map.html">Data Maps</a></li>
                                            <li><a href="code-editor.html">Code Editor</a></li>
                                            <li><a href="image-cropper.html">Images Cropper</a></li>
                                            <li><a href="wizard.html">Wizard</a></li>
                                        </ul>
                                    </li>
                                    <li><a data-toggle="collapse" data-target="#demolibra" href="#">Charts</a>
                                        <ul id="demolibra" class="collapse dropdown-header-top">
                                            <li><a href="flot-charts.html">Flot Charts</a></li>
                                            <li><a href="bar-charts.html">Bar Charts</a></li>
                                            <li><a href="line-charts.html">Line Charts</a></li>
                                            <li><a href="area-charts.html">Area Charts</a></li>
                                        </ul>
                                    </li>
                                    <li><a data-toggle="collapse" data-target="#demodepart" href="#">Tables</a>
                                        <ul id="demodepart" class="collapse dropdown-header-top">
                                            <li><a href="normal-table.html">Normal Table</a></li>
                                            <li><a href="data-table.html">Data Table</a></li>
                                        </ul>
                                    </li>
                                    <li><a data-toggle="collapse" data-target="#demo" href="#">Forms</a>
                                        <ul id="demo" class="collapse dropdown-header-top">
                                            <li><a href="form-elements.html">Form Elements</a></li>
                                            <li><a href="form-components.html">Form Components</a></li>
                                            <li><a href="form-examples.html">Form Examples</a></li>
                                        </ul>
                                    </li>
                                    <li><a data-toggle="collapse" data-target="#Miscellaneousmob" href="#">App views</a>
                                        <ul id="Miscellaneousmob" class="collapse dropdown-header-top">
                                            <li><a href="notification.html">Notifications</a>
                                            </li>
                                            <li><a href="alert.html">Alerts</a>
                                            </li>
                                            <li><a href="modals.html">Modals</a>
                                            </li>
                                            <li><a href="buttons.html">Buttons</a>
                                            </li>
                                            <li><a href="tabs.html">Tabs</a>
                                            </li>
                                            <li><a href="accordion.html">Accordion</a>
                                            </li>
                                            <li><a href="dialog.html">Dialogs</a>
                                            </li>
                                            <li><a href="popovers.html">Popovers</a>
                                            </li>
                                            <li><a href="tooltips.html">Tooltips</a>
                                            </li>
                                            <li><a href="dropdown.html">Dropdowns</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a data-toggle="collapse" data-target="#Pagemob" href="#">Pages</a>
                                        <ul id="Pagemob" class="collapse dropdown-header-top">
                                            <li><a href="contact.html">Contact</a>
                                            </li>
                                            <li><a href="invoice.html">Invoice</a>
                                            </li>
                                            <li><a href="typography.html">Typography</a>
                                            </li>
                                            <li><a href="color.html">Color</a>
                                            </li>
                                            <li><a href="login-register.html">Login Register</a>
                                            </li>
                                            <li><a href="404.html">404 Page</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mobile Menu end -->

        @php($product_active = '')
        @if (Request::is('admin/tax*', 'admin/product', 'admin/transfer-product', 'admin/transfer/*/product', 'admin/return/product-show-warehouse', 'admin/return/*/product-show'))
            @php( $product_active = 'active')
        @endif

        @php($product_only_active = '')
        @if (Request::is('admin/product', 'admin/product/create', 'admin/product/*', 'admin/product/*/edit'))
            @php( $product_only_active = 'active')
        @endif

        <?php

        ?>

        <!-- Main Menu area start-->
        <div class="main-menu-area mg-tb-40">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro">
                            <li class="{{ Request::path() == 'admin' ? 'active' : '' }}"><a class=""   href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                            @if(Auth::check())
                                @if( Auth::user()->user_type_id == 2) {{--who are a vendor--}}
                                    <li class="{{ Request::is('admin/sale*') ? 'active'  :'' }}"><a data-toggle="tab" href="#product_sale"><i class="fa fa-shopping-bag"></i>Sale</a></li>
                                    <li class="{{ $product_active == 'active' ? 'active' : $product_only_active }}"><a data-toggle="tab" href="#products"><i class="fa fa-product-hunt"></i>Product</a></li>
                                    <li class="{{ Request::is('admin/product_category*') ? 'active' : '' }}"><a data-toggle="tab" href="#category"><i class="fa fa-tags"></i>Category</a></li>
                                    <li class="{{ Request::is('admin/product_brand*') ? 'active' : '' }}"><a data-toggle="tab" href="#brand"><i class="fa fa-tags"></i>Brand</a></li>
                                    <li class="{{ Request::is('admin/product_attribute*',  'admin/product_attribute_map*') ? 'active' : '' }}"><a data-toggle="tab" href="#attribute"><i class="fa fa-tags"></i>Attribute</a></li>
                                    <li class="{{ Request::is('admin/user*') ? 'active' : '' }}"><a data-toggle="tab" href="#user"><i class="fa fa-users"></i>User</a></li>
                                    <li class="{{ $vendor_active }}"><a data-toggle="tab" href="#company"><i class="fa fa-building"></i> Company</a></li>
                                    <li class="{{ Request::is('admin/warehouse', 'admin/warehouse/*/edit', 'admin/warehouse/create', 'admin/products/stocks*') ? 'active' : '' }}"><a data-toggle="tab" href="#store"><i class="fa fa-building-o"></i>Store</a></li>
                                    <li class="{{ Request::is('admin/purchase*') ? 'active' : '' }}"><a data-toggle="tab" href="#Purchase"><i class="fa fa-truck"></i>Purchases</a></li>
                                    <li class="{{ Request::is('admin/vendorexpense*') ? 'active' : '' }}"><a data-toggle="tab" href="#VendorExpenses"><i class="fa fa-truck"></i>Vendor Expenses</a></li>
                                    <li class="{{ Request::is('admin/report/vendor*', 'admin/report/warehouse*', 'admin/report/product*', 'admin/report/purchase*', 'admin/report/poscustomer*', 'admin/report/sale*', 'admin/report/transfers', 'admin/report/returns') ? 'active' : '' }}"><a data-toggle="tab" href="#Reports"><i class="fa fa-flag" aria-hidden="true"></i>Reports</a></li>
                                @elseif(Auth::user()->user_type_id == 0)
                                    @if($oparator_role)
                                        <li class="{{ $product_active == 'active' ? 'active' : $product_only_active }}"><a data-toggle="tab" href="#products"><i class="fa fa-product-hunt"></i>Product</a></li>
                                        <li class="{{ Request::is('admin/product_category*') ? 'active' : '' }}"><a data-toggle="tab" href="#category"><i class="fa fa-tags"></i>Category</a></li>
                                        <li class="{{ Request::is('admin/product_brand*') ? 'active' : '' }}"><a data-toggle="tab" href="#brand"><i class="fa fa-tags"></i>Brand</a></li>
                                        <li class="{{ Request::is('admin/product_attribute*',  'admin/product_attribute_map*') ? 'active' : '' }}"><a data-toggle="tab" href="#attribute"><i class="fa fa-tags"></i>Attribute</a></li>
                                        <li class="{{ Request::is('admin/purchase*') ? 'active' : '' }}"><a data-toggle="tab" href="#Purchase"><i class="fa fa-truck"></i>Purchases</a></li>
                                    @endif
                                    @if($sale)
                                        @if(Auth::user()->warehouse_type_name == 'show-room')
                                            <li class="{{ Request::is('admin/sale*') ? 'active' : '' }}"><a data-toggle="tab" href="#product_sale"><i class="fa fa-shopping-bag"></i>Sale</a></li>
                                            <li class="{{ Request::is('admin/warehouse-according/product/report', 'admin/warehouse-according/product/receive', 'admin/return/product') ? 'active' : '' }}"><a data-toggle="tab" href="#make_product_report"><i class="fa fa-shopping-bag"></i>Product Request</a></li>
                                        @endif
                                    @endif

                                    @if($account_role || $oparator_role)
                                        <li class="{{ Request::is('admin/manufacturer*', 'admin/vendor*') ? 'active' : ''}}"><a data-toggle="tab" href="#company"><i class="fa fa-building"></i> Company</a></li>
                                    @endif

                                    @if($sale || $account_role)
                                        <li class="{{ Request::is('admin/vendorexpense*') ? 'active' : '' }}"><a data-toggle="tab" href="#VendorExpenses"><i class="fa fa-building"></i>Vendor Expenses</a></li>
                                    @endif
                                @endif
                            @endif
                        </ul>
                        <div class="tab-content custom-menu-content">

                            <div id="admin_user" class="tab-pane notika-tab-menu-bg animated flipInX">
                                <ul class="notika-main-menu-dropdown">
                                </ul>
                            </div>
                            <div id="product_sale" class="tab-pane notika-tab-menu-bg animated flipInX {{ Request::is('admin/sale*') ? 'active' : '' }}">
                                <ul class="notika-main-menu-dropdown">
                                    <li><a class="{{ Request::is('admin/sale*') ? 'active' : '' }}" href="{{ route('admin.sale.index') }}">Product Sale</a></li>
                                </ul>
                            </div>

                            <div id="products" class="tab-pane notika-tab-menu-bg animated flipInX {{ $product_active == 'active' ? 'active' : $product_only_active }}">
                                <ul class="notika-main-menu-dropdown">
                                        <li><a class="{{ $product_only_active }}" href="{{ route('admin.product.index') }}">Add Product</a></li>
                                        <li><a class="{{ Request::is('admin/transfer-product', 'admin/transfer/*/product') ? 'active' : '' }}" href="{{ route('admin.transfer.product') }}"> Product Distribution</a></li>
                                        <li><a class="{{ Request::is('admin/return/product-show-warehouse', 'admin/return/*/product-show') ? 'active' : '' }}" href="{{ route('admin.return.product.warehouse.show') }}">Product Return</a></li>
                                        <li><a class="{{ request()->routeIs('admin.tax*') ? 'active' : '' }}" href="{{ route('admin.tax.index') }}">Product Tax</a></li>
                                </ul>
                            </div>
                            <div id="category" class="tab-pane notika-tab-menu-bg animated flipInX {{ Request::is('admin/product_category*') ? 'active' : '' }}">
                                <ul class="notika-main-menu-dropdown">
                                    <li><a class="{{ request()->routeIs('admin.product_category*') ? 'active' : '' }}" href="{{ route('admin.product_category.index') }}">Product Category</a></li>
                                </ul>
                            </div>
                            <div id="brand" class="tab-pane notika-tab-menu-bg animated flipInX {{ Request::is('admin/product_brand*') ? 'active' : '' }}">
                                <ul class="notika-main-menu-dropdown">
                                    <li><a class="{{ request()->routeIs('admin.product_brand*') ? 'active' : '' }}" href="{{ route('admin.product_brand.index') }}">Product Brand</a></li>
                                </ul>
                            </div>
                            <div id="attribute" class="tab-pane notika-tab-menu-bg animated flipInX {{ Request::is('admin/product_attribute*',  'admin/product_attribute_map*') ? 'active' : '' }}">
                                <ul class="notika-main-menu-dropdown">
                                    <li><a class="{{ Request::is('admin/product_attribute', 'admin/product_attribute/create', 'admin/product_attribute/*/edit') ? 'active' : ''  }}" href="{{ route('admin.product_attribute.index') }}">Product Attribute</a></li>
                                    <li><a class="{{ request()->routeIs('admin.product_attribute_map*') ? 'active' : '' }}" href="{{ route('admin.product_attribute_map.index') }}">Product Attribute Map</a></li>
                                </ul>
                            </div>
                            <div id="user" class="tab-pane notika-tab-menu-bg animated flipInX {{ Request::is('admin/user*') ? 'active' : '' }}">
                                <ul class="notika-main-menu-dropdown">
                                    <li><a class="{{ Request::is('admin/user*') ? 'active' : '' }}" href="{{ route('admin.user') }}"> Add Users</a></li>
                                </ul>
                            </div>
                            <div id="company" class="tab-pane notika-tab-menu-bg animated flipInX {{ Request::is('admin/manufacturer*', 'admin/vendor*') ? 'active' : '' }}">
                                <ul class="notika-main-menu-dropdown">
                                    @if(Auth::check())
                                        @if( Auth::user()->user_type_id == 2) {{--who are a vendor--}}
                                        <li><a class="{{ Request::is('admin/manufacturer*') ? 'active' : ''  }}" href="{{ route('admin.manufacturer.index') }}">Manufacturer</a></li>
                                        <li><a class="{{ Request::is('admin/vendor*') ? 'active' : '' }}" href="{{ route('admin.supplier.index') }}">Supplier</a></li>
                                        @elseif(Auth::user()->user_type_id == 0)
                                            @if($oparator_role)
                                                    <li><a class="{{ Request::is('admin/vendor*') ? 'active' : '' }}" href="{{ route('admin.supplier.index') }}">Supplier</a></li>
                                            @elseif($account_role)
                                                    <li><a class="{{ Request::is('admin/vendor*') ? 'active' : '' }}" href="{{ route('admin.supplier.all.index') }}">Supplier</a></li>
                                            @endif
                                        @endif
                                    @endif
                                </ul>
                            </div>
                            <div id="store" class="tab-pane notika-tab-menu-bg animated flipInX {{ Request::is('admin/warehouse', 'admin/warehouse/*/edit', 'admin/warehouse/create', 'admin/products/stocks*') ? 'active' : '' }}">
                                <ul class="notika-main-menu-dropdown">
                                    <li><a class="{{ Request::is('admin/warehouse*') ? 'active' : '' }}" href="{{ route('admin.warehouse.index') }}">Warehouse</a><li >
                                    <li><a class="{{ Request::is('admin/products/stock*') ? 'active' : '' }}" href="{{ route('admin.products.stocks') }}">Product Store</a></li>
                                </ul>
                            </div>
                            <div id="Purchase" class="tab-pane notika-tab-menu-bg animated flipInX {{ Request::is('admin/purchase*') ? 'active' : '' }}">
                                <ul class="notika-main-menu-dropdown">
                                    <li><a class="{{ Request::is('admin/purchase*') ? 'active' : '' }}" href="{{ route('admin.purchase.index') }}">Product Purchase</a></li>
                                </ul>
                            </div>
                            <div id="VendorExpenses" class="tab-pane notika-tab-menu-bg animated flipInX {{ Request::is('admin/vendorexpense*') ? 'active' : '' }}">
                                <ul class="notika-main-menu-dropdown">
                                    @if($account_role && $sale)
                                         <li><a  class="{{ Request::is('admin/vendorexpense*') ? 'active' : ''  }}" href="{{ route('admin.vendorexpenses.index') }}">Vendor Expenses</a></li>
                                    @elseif(!$sale && $account_role)
                                        <li><a  class="{{ Request::is('admin/vendorexpense*') ? 'active' : ''  }}" href="{{ route('admin.vendorexpenses.index') }}">Vendor Expenses</a></li>
                                    @elseif($sale && !$account_role)
                                        <li><a  class="{{ Request::is('admin/vendorexpense*') ? 'active' : ''  }}" href="{{ route('admin.vendorexpense.all.index') }}">Vendor Expenses</a></li>
                                    @else
                                        <li><a  class="{{ Request::is('admin/vendorexpense*') ? 'active' : ''  }}" href="{{ route('admin.vendorexpenses.index') }}">Vendor Expenses</a></li>
                                    @endif
                                </ul>
                            </div>
                            <div id="Reports" class="tab-pane notika-tab-menu-bg animated flipInX {{ Request::is('admin/report/vendor*', 'admin/report/warehouse*', 'admin/report/product*', 'admin/report/purchase*', 'admin/report/poscustomer*', 'admin/report/sale*', 'admin/report/transfers', 'admin/report/returns') ? 'active' : '' }}">
                                <ul class="notika-main-menu-dropdown">
                                    <li><a class="{{ Request::is('admin/report/sale*') ? 'active' : ''  }}"  href="{{ route('admin.report.sales') }}">Sale</a></li>
                                    <li><a class="{{ Request::is('admin/report/transfer*') ? 'active' : ''  }}"  href="{{ route('admin.report.transfers') }}">Product Distribution</a></li>
                                    <li><a class="{{ Request::is('admin/report/returns*') ? 'active' : ''  }}"  href="{{ route('admin.report.returns') }}">Product Return </a></li>
                                    <li><a class="{{ Request::is('admin/report/vendor*') ? 'active' : ''  }}"  href="{{ route('admin.report.vendors') }}">Suppliers</a></li>
                                    <li><a class="{{ Request::is('admin/report/warehouse*') ? 'active' : ''  }}" href="{{ route('admin.report.warehouses') }}">Warehouses</a></li>
                                    <li><a class="{{ Request::is('admin/report/product*') ? 'active' : ''  }}" href="{{ route('admin.report.products') }}">Products</a></li>
                                    <li><a class="{{ Request::is('admin/report/purchase*') ? 'active' : ''  }}" href="{{ route('admin.report.purchases') }}">Purchases</a></li>
                                    <li><a class="{{ Request::is('admin/report/poscustomer*') ? 'active' : ''  }}" href="{{ route('admin.report.poscustomers') }}">POS Customers</a></li>
                                </ul>
                            </div>
                            <div id="make_product_report" class="tab-pane notika-tab-menu-bg animated flipInX {{ Request::is('admin/warehouse-according/product/report', 'admin/warehouse-according/product/receive', 'admin/return/product') ? 'active' : '' }}">
                                <ul class="notika-main-menu-dropdown">
                                    <li><a class="{{ Request::is('admin/warehouse-according/product/report') ? 'active' : '' }}" href="{{ route('admin.stock.out.product.report') }}">Request</a></li>
                                    <li><a class="{{ Request::is('admin/warehouse-according/product/receive') ? 'active' : '' }}" href="{{ route('admin.stock.out.product.receive') }}">Receive</a></li>
                                    <li><a class="{{ Request::is('admin/return/product') ? 'active' : '' }}" href="{{ route('admin.return.product') }}">Return</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Menu area End-->
    @endif
@endif
{{--****************** end admin link******************--}}

