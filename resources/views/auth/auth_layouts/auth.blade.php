<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <base href="../../../../">
    <meta charset="utf-8"/>
    <title>{!! config('app.name') !!} | Inventory</title>
    <meta name="description" content="Marketplace Inventory Login"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
    <!--end::Fonts-->
    <!--begin::Page Custom Styles(used by this page)-->
    <link href="{!! asset('assets/css/pages/login/classic/login-3.css?v=7.0.5') !!}" rel="stylesheet" type="text/css"/>
    <!--end::Page Custom Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{!! asset('assets/plugins/global/plugins.bundle.css?v=7.0.5') !!}" rel="stylesheet" type="text/css"/>
    <link href="{!! asset('assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.5') !!}" rel="stylesheet" type="text/css"/>
    <link href="{!! asset('assets/css/style.bundle.css?v=7.0.5') !!}" rel="stylesheet" type="text/css"/>
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <link href="{!! asset('assets/css/themes/layout/header/base/light.css?v=7.0.5') !!}" rel="stylesheet" type="text/css"/>
    <link href="{!! asset('assets/css/themes/layout/header/menu/light.css?v=7.0.5') !!}" rel="stylesheet" type="text/css"/>
    <link href="{!! asset('assets/css/themes/layout/brand/dark.css?v=7.0.5') !!}" rel="stylesheet" type="text/css"/>
    <link href="{!! asset('assets/css/themes/layout/aside/dark.css?v=7.0.5') !!}" rel="stylesheet" type="text/css"/>
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="{!! asset('assets/media/logos/favicon.ico') !!}"/>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body"
      class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Login-->
    <div class="login login-3 login-signin-on d-flex flex-row-fluid" id="kt_login">
        <div class="d-flex flex-center bgi-size-cover bgi-no-repeat flex-row-fluid"
             style="background-image: url({!! asset('assets/media/bg/bg-1.jpg') !!});">

            <div id="login-banner" class="login-form text-center text-white p-7 position-relative overflow-hidden" style="background: #0000001a;">
                <!--begin::Login Header-->
                @php
                    $isSingleVendor = \App\AdminConfig::where('name','allowed_vendor_count')->first();
                @endphp
                <div class="d-flex flex-center mb-15">
                    @if($isSingleVendor->value  == 1)
                        <a href="#">
                            <img src="{!! url('images/main-logo.png') !!}" class="max-h-75px" alt=""/>
                        </a>
                    @else
                        <a href="#">
                            <img src="{!! url('images/contessa_logo.png') !!}" class="max-h-75px" alt=""/>
                        </a>
                    @endif
                </div>
                <!--end::Login Header-->

            </div>

            <div id="login-form" class="login-form text-center text-white p-7 position-relative overflow-hidden">
                <!--begin::Login Header-->
                <div class="d-flex flex-center mb-15">
                    @if($isSingleVendor->value  == 1)
                        <a href="#">
                            <img src="{!! url('images/main-logo.png') !!}" class="max-h-75px" alt=""/>
                        </a>
                    @else
                        <a href="#">
                            <img src="{!! url('images/contessa_logo.png') !!}" class="max-h-75px" alt=""/>
                        </a>
                    @endif
                </div>
                <!--end::Login Header-->

                @yield('auth_content')

            </div>
        </div>
    </div>
    <!--end::Login-->
</div>
<!--end::Main-->
<script>var HOST_URL = "https://keenthemes.com/metronic/tools/preview";</script>
<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = {"breakpoints": {"sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400},
        "colors": {
            "theme": {
                "base": {
                    "white": "#ffffff",
                    "primary": "#3699FF",
                    "secondary": "#E5EAEE",
                    "success": "#1BC5BD",
                    "info": "#8950FC",
                    "warning": "#FFA800",
                    "danger": "#F64E60",
                    "light": "#E4E6EF",
                    "dark": "#181C32"
                },
                "light": {
                    "white": "#ffffff",
                    "primary": "#E1F0FF",
                    "secondary": "#EBEDF3",
                    "success": "#C9F7F5",
                    "info": "#EEE5FF",
                    "warning": "#FFF4DE",
                    "danger": "#FFE2E5",
                    "light": "#F3F6F9",
                    "dark": "#D6D6E0"
                },
                "inverse": {
                    "white": "#ffffff",
                    "primary": "#ffffff",
                    "secondary": "#3F4254",
                    "success": "#ffffff",
                    "info": "#ffffff",
                    "warning": "#ffffff",
                    "danger": "#ffffff",
                    "light": "#464E5F",
                    "dark": "#ffffff"
                }
            },
            "gray": {
                "gray-100": "#F3F6F9",
                "gray-200": "#EBEDF3",
                "gray-300": "#E4E6EF",
                "gray-400": "#D1D3E0",
                "gray-500": "#B5B5C3",
                "gray-600": "#7E8299",
                "gray-700": "#5E6278",
                "gray-800": "#3F4254",
                "gray-900": "#181C32"
            }
        },
        "font-family": "Poppins"
    };
</script>
<!--end::Global Config-->
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="{!! asset('assets/plugins/global/plugins.bundle.js?v=7.0.5') !!}"></script>
<script src="{!! asset('assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.5') !!}"></script>
<script src="{!! asset('assets/js/scripts.bundle.js?v=7.0.5') !!}"></script>
<!--end::Global Theme Bundle-->
<!--begin::Page Scripts(used by this page)-->
{{-- <script src="assets/js/pages/custom/login/login-general.js?v=7.0.5"></script> --}}
<!--end::Page Scripts-->
<script>
    $(document).ready(function () {
        $("#login-banner").height($("#login-form").height());
    })
</script>
</body>
<!--end::Body-->
</html>
