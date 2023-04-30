@php
    $isSingleVendor = \App\AdminConfig::where('name','allowed_vendor_count')->first();
@endphp
<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
    <!--begin::Brand-->
    <div class="brand flex-column-auto" id="kt_brand">

    @if(isset($users_vendor))
        <!--begin::Logo-->
            @if($users_vendor->logo)
                <a href="{{ route('admin.dashboard') }}" class="brand-logo">
                    <img alt="Logo" src="{{ asset($users_vendor->logo)}}" width="50" height="50"/>
                </a>
            @endif
            @if($users_vendor->name)
                <span class="" style="color: #F1F6F7">{{$users_vendor->name}}</span>
            @endif
        @else
            <a href="{{ route('dashboard') }}" class="brand-logo">
                <img alt="Logo" src="{{ asset('images/contessa_logo.png')}}" width="100%" />
            </a>
{{--            <span class="" style="color: #F1F6F7">Contessa</span>--}}
    @endif
    <!--end::Logo-->
        <!--begin::Toggle-->
        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
							<span class="svg-icon svg-icon svg-icon-xl">
								<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-left.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                     width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<polygon points="0 0 24 0 24 24 0 24"/>
										<path
                                            d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                                            fill="#000000" fill-rule="nonzero"
                                            transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)"/>
										<path
                                            d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3"
                                            transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)"/>
									</g>
								</svg>
                                <!--end::Svg Icon-->
							</span>
        </button>
        <!--end::Toolbar-->
    </div>
    <!--end::Brand-->


    <!--begin::Aside Menu-->
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
        <!--begin::Menu Container-->
        <div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1"
             data-menu-dropdown-timeout="500">
            <!--begin::Menu Nav-->
            <ul class="menu-nav">
                @if(Request::is('superadmin*'))
                    @php($country_active = '')
                    @if (Request::is('superadmin/country*'))
                        @php( $country_active = 'active')
                    @endif
                    @php($state_active = '')
                    @if (Request::is('superadmin/state*'))
                        @php( $state_active = 'active')
                    @endif
                    @php($city_active = '')
                    @if (Request::is('superadmin/city*'))
                        @php( $city_active = 'active')
                    @endif
                    @php($product_category_active = '')
                    @if (Request::is('superadmin/product_category*'))
                        @php( $product_category_active = 'active')
                    @endif
                    @php($product_brand_active = '')
                    @if (Request::is('superadmin/product_brand*'))
                        @php( $product_brand_active = 'active')
                    @endif
                    @php($manufacturer_active = '')
                    @if (Request::is('superadmin/manufacturer*'))
                        @php( $manufacturer_active = 'active')
                    @endif
                    @php($parent_product_active = '')
                    @if (Request::is('superadmin/parent_product*'))
                        @php( $parent_product_active = 'active')
                    @endif
                    @php($vendor_active = '')
                    @if (Request::is('superadmin/vendor*'))
                        @php( $vendor_active = 'active')
                    @endif
                    @php($banner_active = '')
                    @if (Request::is('superadmin/banner*'))
                        @php( $banner_active = 'active')
                    @endif
                    @php($shipping_methods_active = '')
                    @if (Request::is('superadmin/shipping_methods*'))
                        @php( $shipping_methods_active = 'active')
                    @endif
                    @php($payment_methods_active = '')
                    @if (Request::is('superadmin/payment_methods*'))
                        @php( $payment_methods_active = 'active')
                    @endif
                    @php($promotional_ads_active = '')
                    @if (Request::is('superadmin/promotional_advertisement*'))
                        @php( $promotional_ads_active = 'active')
                    @endif
                    @php($service_contracts_active = '')
                    @if (Request::is('superadmin/service_contracts*'))
                        @php( $service_contracts_active = 'active')
                    @endif
                    @php($contact_us_active = '')
                    @if (Request::is('superadmin/contact_us*'))
                        @php( $contact_us_active = 'active')
                    @endif
                    @php($external_page_active = '')
                    @if (Request::is('superadmin/external_pages*'))
                        @php( $external_page_active = 'active')
                    @endif
                    @php($user_role_active = '')
                    @if (Request::is('superadmin/user_role*'))
                        @php( $user_role_active = 'active')
                    @endif
                    @php($vendor_user_active = '')
                    @if (Request::is('superadmin/user/vendor*'))
                        @php( $vendor_user_active = 'active')
                    @endif
                    @php($super_user_active = '')
                    @if (Request::is('superadmin/user/super*'))
                        @php( $super_user_active = 'active')
                    @endif
                    <li class="menu-item {{ Request::path() == 'superadmin/dashboard' ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('superadmin.dashboard') }}"
                           class="menu-link">
                                <span class="svg-icon menu-icon">
                                    <i class="flaticon2-website"></i>
                                </span>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="menu-section">
                        <h4 class="menu-text">General Settings</h4>
                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $country_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-world"></i>
                                </span>
                            <span class="menu-text">Country</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/country') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.country.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/country/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.country.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $state_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-pin-1"></i>
                                </span>
                            <span class="menu-text">State</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/state') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.state.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/state/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.state.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $city_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-location"></i>
                                </span>
                            <span class="menu-text">City</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/city') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.city.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span></a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/city/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.city.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    {{-- <li class="menu-item menu-item-submenu {{ $product_category_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
										<span class="menu-icon">
											<i class="flaticon2-sort-alphabetically"></i>
										</span>
                            <span class="menu-text">Parent Category</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/product_category') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.product_category.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/product_category/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.product_category.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li> --}}
                    <li class="menu-item menu-item-submenu {{ $product_category_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-icon">
                                <i class="flaticon2-sort-alphabetically"></i>
                            </span>
                            <span class="menu-text">Category</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/product_category') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.product_category.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/product_category/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.product_category.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $product_brand_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
										<span class="menu-icon">
											<i class="flaticon2-tools-and-utensils"></i>
										</span>
                            <span class="menu-text">Parent Brand</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/product_brand') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.product_brand.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/product_brand/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.product_brand.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
{{--                    <li class="menu-item menu-item-submenu {{ $manufacturer_active == 'active' ? 'menu-item-open' : '' }}"--}}
{{--                        aria-haspopup="true" data-menu-toggle="hover">--}}
{{--                        <a href="javascript:;" class="menu-link menu-toggle">--}}
{{--										<span class="menu-icon">--}}
{{--											<i class="flaticon2-architecture-and-city"></i>--}}
{{--										</span>--}}
{{--                            <span class="menu-text">Parent Manufacturer</span>--}}
{{--                            <i class="menu-arrow"></i>--}}
{{--                        </a>--}}
{{--                        <div class="menu-submenu">--}}
{{--                            <ul class="menu-subnav">--}}
{{--                                <li class="menu-item {{ Request::is('superadmin/manufacturer') ? 'menu-item-active' : ''  }}">--}}
{{--                                    <a href="{{ route('superadmin.manufacturer.index') }}" class="menu-link">--}}
{{--                                        <span class="menu-icon">--}}
{{--                                            <i class="flaticon2-indent-dots"></i>--}}
{{--                                        </span>--}}
{{--                                        <span class="menu-text">List</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="menu-item {{ Request::is('superadmin/manufacturer/create') ? 'menu-item-active' : ''  }}">--}}
{{--                                    <a href="{{ route('superadmin.manufacturer.create') }}" class="menu-link">--}}
{{--                                        <span class="menu-icon">--}}
{{--                                            <i class="flaticon2-add-1"></i>--}}
{{--                                        </span>--}}
{{--                                        <span class="menu-text">Add New</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </li>--}}
                    <li class="menu-item menu-item-submenu {{ $parent_product_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-shopping-cart-1"></i>
                                </span>
                            <span class="menu-text">Parent Products</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/parent_product') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.parent_product.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/parent_product/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.parent_product.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-section">
                        <h4 class="menu-text">E-commerce Settings</h4>
                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                    </li>
                    <li class="menu-item {{ Request::is('superadmin/invoice_settings*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('superadmin.invoice_settings.index') }}" class="menu-link">
                                <span class="menu-icon">
                                        <i class="flaticon2-document"></i>
                                </span>
                            <span class="menu-text">Invoice Settings</span>
                        </a>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $banner_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-photograph"></i>
                                </span>
                            <span class="menu-text">Banner</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/banner') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.banner.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/banner/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.banner.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $shipping_methods_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-lorry"></i>
                                </span>
                            <span class="menu-text">Shipping Method</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/shipping_methods') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.shipping_methods.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/shipping_methods/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.shipping_methods.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    {{--                Payment Method Start =>--}}
                    <li class="menu-item menu-item-submenu {{ $payment_methods_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-lorry"></i>
                                </span>
                            <span class="menu-text">Payment Method</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/payment_methods') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.payment_methods.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/payment_methods/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.payment_methods.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    {{--                Payment Method End  =>--}}
                    <li class="menu-item menu-item-submenu {{ $promotional_ads_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-digital-marketing"></i>
                                </span>
                            <span class="menu-text">Promotional Ads</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/promotional_advertisement') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.promotional_advertisement.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/promotional_advertisement/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.promotional_advertisement.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $service_contracts_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-contract"></i>
                                </span>
                            <span class="menu-text">Service Contracts</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/service_contracts') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.service_contracts.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/service_contracts/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.service_contracts.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $contact_us_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-wifi"></i>
                                </span>
                            <span class="menu-text">Contact Us</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/contact_us') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.contact_us.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/contact_us/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.contact_us.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $external_page_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-wifi"></i>
                                </span>
                            <span class="menu-text">External Pages</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/external_pages') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.external_pages.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/external_pages/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.external_pages.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-section">
                        <h4 class="menu-text">Inventory Settings</h4>
                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                    </li>
                    <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">

                    </li>
                    <li class="menu-item menu-item-submenu {{ Request::is('superadmin/sale-commission*') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon-rotate"></i>
                                </span>
                            <span class="menu-text">Sale Commission</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item">
                                    <a href="{{ route('superadmin.commission.ajax') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">Add</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="menu-subnav">
                                <li class="menu-item">
                                    <a href="{{ route('superadmin.commission.received') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">Received</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="menu-subnav">
                                <li class="menu-item">
                                    <a href="{{ route('superadmin.sale_commission.detail') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">Commission Detail</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $vendor_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon-rotate"></i>
                                </span>
                            <span class="menu-text">Vendor</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/vendor') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.vendor.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/vendor/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.vendor.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $user_role_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon-security"></i>
                                </span>
                            <span class="menu-text">User Role</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/user_role') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.user_role.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/user_role/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.user_role.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $vendor_user_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-user-1"></i>
                                </span>
                            <span class="menu-text">Vendor User</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/user/vendor') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.user.vendor') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/user/vendor/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.user.vendor.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    <li class="menu-item menu-item-submenu {{ $super_user_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-avatar"></i>
                                </span>
                            <span class="menu-text">Super User</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('superadmin/user/super') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.user.super') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('superadmin/user/super/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('superadmin.user.super.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-section">
                        <h4 class="menu-text">Product Management</h4>
                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                    </li>
                    <li class="menu-item {{ Request::is('superadmin/product/approval*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('superadmin.product.approval') }}" class="menu-link">
                                <span class="menu-icon">
                                        <i class="flaticon2-shopping-cart-1"></i>
                                </span>
                            <span class="menu-text">Approve Products</span>
                        </a>
                    </li>
                    {{-- <li class="menu-item {{ Request::is('superadmin/product/category/approval*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('superadmin.product.category.approval') }}" class="menu-link">
                                <span class="menu-icon">
                                        <i class="flaticon2-sort-alphabetically"></i>
                                </span>
                            <span class="menu-text">Approve Categories</span>
                        </a>
                    </li> --}}
                    <li class="menu-item {{ Request::is('superadmin/product/product_brand/approval*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('superadmin.product_brand.approval') }}" class="menu-link">
                                <span class="menu-icon">
                                        <i class="flaticon2-tools-and-utensils"></i>
                                </span>
                            <span class="menu-text">Approve Brands</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('superadmin/product/manufacturer/approval*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('superadmin.manufacturer.approval') }}" class="menu-link">
                                <span class="menu-icon">
                                        <i class="flaticon2-architecture-and-city"></i>
                                </span>
                            <span class="menu-text">Approve Manufacturers</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('superadmin/product/parent/featured*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('superadmin.product.featured') }}" class="menu-link">
                                <span class="menu-icon">
                                        <i class="flaticon-cart"></i>
                                </span>
                            <span class="menu-text">Featured Product Mapping</span>
                        </a>
                    </li>
                    {{--                    <li class="menu-item {{ Request::is('superadmin/product/child') ? 'menu-item-active' : ''  }}" aria-haspopup="true">--}}
                    {{--                        <a href="{{ route('superadmin.product.child') }}" class="menu-link">--}}
                    {{--                                <span class="menu-icon">--}}
                    {{--                                        <i class="flaticon-layers"></i>--}}
                    {{--                                </span>--}}
                    {{--                            <span class="menu-text">Parent Product Mapping</span>--}}
                    {{--                        </a>--}}
                    {{--                    </li>--}}
                    {{--                    <li class="menu-section">--}}
                    {{--                        <h4 class="menu-text">Email Template Management</h4>--}}
                    {{--                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>--}}
                    {{--                    </li>--}}
                    {{--                    <li class="menu-item {{ Request::is('superadmin/email/registration*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">--}}
                    {{--                        <a href="{{ route('superadmin.email_registration') }}" class="menu-link">--}}
                    {{--                                <span class="menu-icon">--}}
                    {{--                                        <i class="flaticon-layers"></i>--}}
                    {{--                                </span>--}}
                    {{--                            <span class="menu-text">Registration</span>--}}
                    {{--                        </a>--}}
                    {{--                    </li>--}}
                    <li class="menu-section">
                        <h4 class="menu-text">Reports</h4>
                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                    </li>
                    <li class="menu-item {{ Request::is('superadmin/report/pending-vendors*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('superadmin.report.pending_vendors') }}" class="menu-link">
                                <span class="menu-icon">
                                        <i class="flaticon2-poll-symbol"></i>
                                </span>
                            <span class="menu-text">Pending Vendors</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->user_type_id == 2)
                    @php($country_active = '')
                    @if (Request::is('admin/country*'))
                        @php( $country_active = 'active')
                    @endif
                    @php($state_active = '')
                    @if (Request::is('admin/state*'))
                        @php( $state_active = 'active')
                    @endif
                    @php($city_active = '')
                    @if (Request::is('admin/city*'))
                        @php( $city_active = 'active')
                    @endif
                    @php($banner_active = '')
                    @if (Request::is('admin/banner*'))
                        @php( $banner_active = 'active')
                    @endif
                    @php($shipping_methods_active = '')

                    @php($promotional_ads_active = '')
                    @if (Request::is('admin/promotional_advertisement*'))
                        @php( $promotional_ads_active = 'active')
                    @endif
                    @php($service_contracts_active = '')
                    @if (Request::is('admin/service_contracts*'))
                        @php( $service_contracts_active = 'active')
                    @endif
                    @php($contact_us_active = '')
                    @if (Request::is('admin/contact_us*'))
                        @php( $contact_us_active = 'active')
                    @endif
                    @php($banner_active = '')
                    @if (Request::is('admin/banner*'))
                        @php( $banner_active = 'active')
                    @endif

                        @php($manufacturer_active = '')
                    @if (Request::is('admin/manufacturer*'))
                        @php( $manufacturer_active = 'active')
                    @endif
                    @php($supplier_active = '')
                    @if (Request::is('admin/supplier*','admin/add/payment_method*','admin/edit/payment_method/*','admin/add/supplier_payment*'))
                        @php( $supplier_active = 'active')
                    @endif
                    @php($pos_customer_active = '')
                    @if (Request::is('admin/pos_customer-all*','admin/add/payment_method*','admin/edit/payment_method/*','admin/add/pos_customer_payment*','admin/mp_customer-all*'))
                        @php( $pos_customer_active = 'active')
                    @endif
                    @php($warehouse_active = '')
                    @if (Request::is('admin/warehouse*'))
                        @php( $warehouse_active = 'active')
                    @endif
                    @php($user_config_active = '')
                    @if (Request::is('admin/user*'))
                        @php( $user_config_active = 'active')
                    @endif
                    @php($product_active = '')
                    @if (Request::is('admin/product','admin/product/parent','admin/product/*/show','admin/product/*/edit'))
                        @php( $product_active = 'active')
                    @endif
                    @php($product_config_active = '')
                    @if (Request::is('admin/tax*', 'admin/product_brand*', 'admin/product_category*', 'admin/product_attribute*', 'admin/product_attribute_map*', 'admin/manufacturer*'))
                        @php( $product_config_active = 'active')
                    @endif
                    @php($tax_active = '')
                    @if (Request::is('admin/tax*'))
                        @php( $tax_active = 'active')
                    @endif
                    @php($product_brand_active = '')
                    @if (Request::is('admin/product_brand*'))
                        @php( $product_brand_active = 'active')
                    @endif
                    @php($product_category_active = '')
                    @if (Request::is('admin/product_category*'))
                        @php( $product_category_active = 'active')
                    @endif
                    @php($product_attribute_active = '')
                    @if (Request::is('admin/product_attribute','admin/product_attribute/create','admin/product_attribute/*/edit'))
                        @php( $product_attribute_active = 'active')
                    @endif
                    @php($product_attribute_map_active = '')
                    @if (Request::is('admin/product_attribute_map*'))
                        @php( $product_attribute_map_active = 'active')
                    @endif
                    @php($purchase_active = '')
                    @if (Request::is('admin/purchase*'))
                        @php( $purchase_active = 'active')
                    @endif

                    @php($sale_active = '')
                    @if (Request::is('admin/sale*'))
                        @php( $sale_active = 'active')
                    @endif
                    @php($product_return_active = '')
                    @if (Request::is('admin/return*') || Request::is('admin/product_return*'))
                        @php($product_return_active = 'active')
                    @endif
                    @php($sold_order_active = '')
                    @if (Request::is('admin/sale-order*') || Request::is('admin/sale-order*'))
                        @php($sold_order_active = 'active')
                    @endif
                        @php($shipping_methods_active = '')
                        @if (Request::is('admin/shipping_methods*'))
                            @php( $shipping_methods_active = 'active')
                        @endif
                        @php($payment_methods_active = '')
                        @if (Request::is('admin/payment_methods*'))
                            @php( $payment_methods_active = 'active')
                        @endif


                    <li class="menu-item {{ Request::path() == 'admin' ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('admin.dashboard') }}"
                           class="menu-link">
                            <span class="svg-icon menu-icon">
                                <i class="flaticon2-website"></i>
                            </span>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="menu-section">
                        <h4 class="menu-text">Settings</h4>
                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                    </li>

                    <li class="menu-item menu-item-submenu {{ $supplier_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-delivery-truck"></i>
                                </span>
                            <span class="menu-text">Supplier</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('admin/supplier') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.supplier.index') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-indent-dots"></i>
                                            </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/supplier/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.supplier.create') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-add-1"></i>
                                            </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $pos_customer_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-delivery-truck"></i>
                                </span>
                            <span class="menu-text">Customers</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('admin/pos_customer-all') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.pos_customer.all.list') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-indent-dots"></i>
                                            </span>
                                        <span class="menu-text">Pos Customers</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/mp_customer-all') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.mp_customer.all.list') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-indent-dots"></i>
                                            </span>
                                        <span class="menu-text">Marketplace Customers</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    {{--                                    <li class="menu-item {{ Request::is('admin/supplier_payment*') ? 'menu-item-active' : ''  }}">--}}
                    {{--                                        <a href="{{ route('admin.supplier_payment.index') }}" class="menu-link">--}}
                    {{--                                            <i class="menu-bullet menu-bullet-line">--}}
                    {{--                                                <span></span>--}}
                    {{--                                            </i>--}}
                    {{--                                            <span class="menu-text">Supplier Payment</span>--}}
                    {{--                                        </a>--}}
                    {{--                                    </li>--}}
                    <li class="menu-item menu-item-submenu {{ $warehouse_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-open-box"></i>
                                </span>
                            <span class="menu-text">Warehouse</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('admin/warehouse') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.warehouse.index') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-indent-dots"></i>
                                            </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/warehouse/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.warehouse.create') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-add-1"></i>
                                            </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/warehouse_detail') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.warehouse_detail.index') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-indent-dots"></i>
                                            </span>
                                        <span class="menu-text">Section List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/warehouse_detail/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.warehouse_detail.create') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-add-1"></i>
                                            </span>
                                        <span class="menu-text">Section Add</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $user_config_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-user-1"></i>
                                </span>
                            <span class="menu-text">Users Config</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('admin/users') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.user') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-indent-dots"></i>
                                            </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/user') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.add.user') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-add-1"></i>
                                            </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ Request::is('admin/mp_users','admin/mp_user') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-user-1"></i>
                                </span>
                            <span class="menu-text">Retailer Config</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('admin/mp_users') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.mp_user') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-indent-dots"></i>
                                            </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/mp_user') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.add.mp_user') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-add-1"></i>
                                            </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    {{--                                    <li class="menu-item {{ Request::is('admin/banner*') ? 'menu-item-active' : ''  }}">--}}
                    {{--                                        <a href="{{ route('admin.banner.index') }}" class="menu-link">--}}
                    {{--                                            <i class="menu-bullet menu-bullet-line">--}}
                    {{--                                                <span></span>--}}
                    {{--                                            </i>--}}
                    {{--                                            <span class="menu-text">Banners</span>--}}
                    {{--                                        </a>--}}
                    {{--                                    </li>--}}
                    <li class="menu-item {{ Request::is('admin/discounts*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.discounts.index') }}" class="menu-link">
                                <span class="menu-icon">
                                        <i class="flaticon2-percentage"></i>
                                </span>
                            <span class="menu-text">Discounts</span>
                        </a>
                    </li>

                    @if(!empty($isSingleVendor) && $isSingleVendor->value == 1 && strtolower(auth()->user()->user_role->name) == 'admin')

                            <li class="menu-item menu-item-submenu {{ $country_active == 'active' ? 'menu-item-open' : '' }}"
                                aria-haspopup="true" data-menu-toggle="hover">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-world"></i>
                                </span>
                                    <span class="menu-text">Country</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu">
                                    <ul class="menu-subnav">
                                        <li class="menu-item {{ Request::is('admin/country') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.country.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                                <span class="menu-text">List</span>
                                            </a>
                                        </li>
                                        <li class="menu-item {{ Request::is('admin/country/create') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.country.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                                <span class="menu-text">Add New</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item menu-item-submenu {{ $state_active == 'active' ? 'menu-item-open' : '' }}"
                                aria-haspopup="true" data-menu-toggle="hover">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-pin-1"></i>
                                </span>
                                    <span class="menu-text">State</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu">
                                    <ul class="menu-subnav">
                                        <li class="menu-item {{ Request::is('admin/state') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.state.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                                <span class="menu-text">List</span>
                                            </a>
                                        </li>
                                        <li class="menu-item {{ Request::is('admin/state/create') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.state.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                                <span class="menu-text">Add New</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item menu-item-submenu {{ $city_active == 'active' ? 'menu-item-open' : '' }}"
                                aria-haspopup="true" data-menu-toggle="hover">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-location"></i>
                                </span>
                                    <span class="menu-text">City</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu">
                                    <ul class="menu-subnav">
                                        <li class="menu-item {{ Request::is('admin/city') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.city.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                                <span class="menu-text">List</span></a>
                                        </li>
                                        <li class="menu-item {{ Request::is('admin/city/create') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.city.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                                <span class="menu-text">Add New</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="menu-item menu-item-submenu {{ $banner_active == 'active' ? 'menu-item-open' : '' }}"
                                aria-haspopup="true" data-menu-toggle="hover">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-photograph"></i>
                                </span>
                                    <span class="menu-text">Banner</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu">
                                    <ul class="menu-subnav">
                                        <li class="menu-item {{ Request::is('admin/banner') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.banner.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                                <span class="menu-text">List</span>
                                            </a>
                                        </li>
                                        <li class="menu-item {{ Request::is('admin/banner/create') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.banner.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                                <span class="menu-text">Add New</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item menu-item-submenu {{ $promotional_ads_active == 'active' ? 'menu-item-open' : '' }}"
                                aria-haspopup="true" data-menu-toggle="hover">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-digital-marketing"></i>
                                </span>
                                    <span class="menu-text">Promotional Ads</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu">
                                    <ul class="menu-subnav">
                                        <li class="menu-item {{ Request::is('admin/promotional_advertisement') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.promotional_advertisement.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                                <span class="menu-text">List</span>
                                            </a>
                                        </li>
                                        <li class="menu-item {{ Request::is('admin/promotional_advertisement/create') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.promotional_advertisement.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                                <span class="menu-text">Add New</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item menu-item-submenu {{ $service_contracts_active == 'active' ? 'menu-item-open' : '' }}"
                                aria-haspopup="true" data-menu-toggle="hover">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-contract"></i>
                                </span>
                                    <span class="menu-text">Service Contracts</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu">
                                    <ul class="menu-subnav">
                                        <li class="menu-item {{ Request::is('admin/service_contracts') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.service_contracts.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                                <span class="menu-text">List</span>
                                            </a>
                                        </li>
                                        <li class="menu-item {{ Request::is('admin/service_contracts/create') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.service_contracts.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                                <span class="menu-text">Add New</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item menu-item-submenu {{ $contact_us_active == 'active' ? 'menu-item-open' : '' }}"
                                aria-haspopup="true" data-menu-toggle="hover">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-wifi"></i>
                                </span>
                                    <span class="menu-text">Contact Us</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu">
                                    <ul class="menu-subnav">
                                        <li class="menu-item {{ Request::is('admin/contact_us') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.contact_us.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                                <span class="menu-text">List</span>
                                            </a>
                                        </li>
                                        <li class="menu-item {{ Request::is('admin/contact_us/create') ? 'menu-item-active' : ''  }}">
                                            <a href="{{ route('admin.contact_us.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                                <span class="menu-text">Add New</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                        <li class="menu-item menu-item-submenu {{ $shipping_methods_active == 'active' ? 'menu-item-open' : '' }}"
                            aria-haspopup="true" data-menu-toggle="hover">
                            <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-lorry"></i>
                                </span>
                                <span class="menu-text">Shipping Method</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="menu-submenu">
                                <ul class="menu-subnav">
                                    <li class="menu-item {{ Request::is('admin/shipping_methods') ? 'menu-item-active' : ''  }}">
                                        <a href="{{ route('admin.shipping_methods.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                            <span class="menu-text">List</span>
                                        </a>
                                    </li>
                                    <li class="menu-item {{ Request::is('admin/shipping_methods/create') ? 'menu-item-active' : ''  }}">
                                        <a href="{{ route('admin.shipping_methods.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                            <span class="menu-text">Add New</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        {{--                Payment Method Start =>--}}

                        <li class="menu-item menu-item-submenu {{ $payment_methods_active == 'active' ? 'menu-item-open' : '' }}"
                            aria-haspopup="true" data-menu-toggle="hover">
                            <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-lorry"></i>
                                </span>
                                <span class="menu-text">Payment Method</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="menu-submenu">
                                <ul class="menu-subnav">
                                    <li class="menu-item {{ Request::is('admin/payment_methods') ? 'menu-item-active' : ''  }}">
                                        <a href="{{ route('admin.payment_methods.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                            <span class="menu-text">List</span>
                                        </a>
                                    </li>
                                    <li class="menu-item {{ Request::is('admin/payment_methods/create') ? 'menu-item-active' : ''  }}">
                                        <a href="{{ route('admin.payment_methods.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                            <span class="menu-text">Add New</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                        {{--                Payment Method End  =>--}}


                    <li class="menu-section">
                        <h4 class="menu-text">Product</h4>
                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                    </li>

                    <li class="menu-item menu-item-submenu {{ $product_config_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-icon">
                                <i class="flaticon2-shopping-cart"></i>
                            </span>
                            <span class="menu-text">Product Config</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-submenu {{ $manufacturer_active == 'active' ? 'menu-item-open' : '' }}"
                                    aria-haspopup="true" data-menu-toggle="hover">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-icon">
                                            <i class="flaticon2-architecture-and-city"></i>
                                        </span>
                                        <span class="menu-text">Manufacturer</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu">
                                        <ul class="menu-subnav">
                                            <li class="menu-item {{ Request::is('admin/manufacturer') ? 'menu-item-active' : ''  }}">
                                                <a href="{{ route('admin.manufacturer.index') }}" class="menu-link">
                                                    <span class="menu-icon">
                                                        <i class="flaticon2-indent-dots"></i>
                                                    </span>
                                                    <span class="menu-text">List</span>
                                                </a>
                                            </li>
                                            <li class="menu-item {{ Request::is('admin/manufacturer/create') ? 'menu-item-active' : ''  }}">
                                                <a href="{{ route('admin.manufacturer.create') }}" class="menu-link">
                                                    <span class="menu-icon">
                                                        <i class="flaticon2-add-1"></i>
                                                    </span>
                                                    <span class="menu-text">Add New</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="menu-item menu-item-submenu {{ $tax_active == 'active' ? 'menu-item-open' : '' }}"
                                    aria-haspopup="true" data-menu-toggle="hover">
                                    <a href="javascript:;" class="menu-link menu-toggle">
										<span class="menu-icon">
											<i class="flaticon2-list"></i>
										</span>
                                        <span class="menu-text">Tax</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu">
                                        <ul class="menu-subnav">
                                            <li class="menu-item {{ Request::is('admin/tax') ? 'menu-item-active' : ''  }}">
                                                <a href="{{ route('admin.tax.index') }}" class="menu-link">
                                                    <span class="menu-icon">
                                                        <i class="flaticon2-indent-dots"></i>
                                                    </span>
                                                    <span class="menu-text">List</span>
                                                </a>
                                            </li>
                                            <li class="menu-item {{ Request::is('admin/tax/create') ? 'menu-item-active' : ''  }}">
                                                <a href="{{ route('admin.tax.create') }}" class="menu-link">
                                                    <span class="menu-icon">
                                                        <i class="flaticon2-add-1"></i>
                                                    </span>
                                                    <span class="menu-text">Add New</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="menu-item menu-item-submenu {{ $product_brand_active == 'active' ? 'menu-item-open' : '' }}"
                                    aria-haspopup="true" data-menu-toggle="hover">
                                    <a href="javascript:;" class="menu-link menu-toggle">
										<span class="menu-icon">
											<i class="flaticon2-tools-and-utensils"></i>
										</span>
                                        <span class="menu-text">Brand</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu">
                                        <ul class="menu-subnav">
                                            <li class="menu-item {{ Request::is('admin/product_brand') ? 'menu-item-active' : ''  }}">
                                                <a href="{{ route('admin.product_brand.index') }}" class="menu-link">
                                                    <span class="menu-icon">
                                                        <i class="flaticon2-indent-dots"></i>
                                                    </span>
                                                    <span class="menu-text">List</span>
                                                </a>
                                            </li>
                                            <li class="menu-item {{ Request::is('admin/product_brand/create') ? 'menu-item-active' : ''  }}">
                                                <a href="{{ route('admin.product_brand.create') }}" class="menu-link">
                                                    <span class="menu-icon">
                                                        <i class="flaticon2-add-1"></i>
                                                    </span>
                                                    <span class="menu-text">Add New</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @if($allowed = App\AdminConfig::where('name','allowed_vendor_count')->value('value') == 1)
                                    <li class="menu-item menu-item-submenu {{ $product_category_active == 'active' ? 'menu-item-open' : '' }}"
                                        aria-haspopup="true" data-menu-toggle="hover">
                                        <a href="javascript:;" class="menu-link menu-toggle">
										<span class="menu-icon">
											<i class="flaticon2-sort-alphabetically"></i>
										</span>
                                            <span class="menu-text">Category</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                        <div class="menu-submenu">
                                            <ul class="menu-subnav">
                                                <li class="menu-item {{ Request::is('admin/product_category') ? 'menu-item-active' : ''  }}">
                                                    <a href="{{ route('admin.product_category.index') }}" class="menu-link">
                                                    <span class="menu-icon">
                                                        <i class="flaticon2-indent-dots"></i>
                                                    </span>
                                                        <span class="menu-text">List</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item {{ Request::is('admin/product_category/create') ? 'menu-item-active' : ''  }}">
                                                    <a href="{{ route('admin.product_category.create') }}" class="menu-link">
                                                    <span class="menu-icon">
                                                        <i class="flaticon2-add-1"></i>
                                                    </span>
                                                        <span class="menu-text">Add New</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif
                                <li class="menu-item menu-item-submenu {{ $product_attribute_active == 'active' ? 'menu-item-open' : '' }}"
                                    aria-haspopup="true" data-menu-toggle="hover">
                                    <a href="javascript:;" class="menu-link menu-toggle">
										<span class="menu-icon">
											<i class="flaticon2-layers-2"></i>
										</span>
                                        <span class="menu-text">Attribute</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu">
                                        <ul class="menu-subnav">
                                            <li class="menu-item {{ Request::is('admin/product_attribute') ? 'menu-item-active' : ''  }}">
                                                <a href="{{ route('admin.product_attribute.index') }}" class="menu-link">
                                                    <span class="menu-icon">
                                                        <i class="flaticon2-indent-dots"></i>
                                                    </span>
                                                    <span class="menu-text">List</span>
                                                </a>
                                            </li>
                                            <li class="menu-item {{ Request::is('admin/product_attribute/create') ? 'menu-item-active' : ''  }}">
                                                <a href="{{ route('admin.product_attribute.create') }}" class="menu-link">
                                                    <span class="menu-icon">
                                                        <i class="flaticon2-add-1"></i>
                                                    </span>
                                                    <span class="menu-text">Add New</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="menu-item menu-item-submenu {{ $product_attribute_map_active == 'active' ? 'menu-item-open' : '' }}"
                                    aria-haspopup="true" data-menu-toggle="hover">
                                    <a href="javascript:;" class="menu-link menu-toggle">
										<span class="menu-icon">
											<i class="flaticon2-sort-down"></i>
										</span>
                                        <span class="menu-text">Attribute Map</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu">
                                        <ul class="menu-subnav">
                                            <li class="menu-item {{ Request::is('admin/product_attribute_map') ? 'menu-item-active' : ''  }}">
                                                <a href="{{ route('admin.product_attribute_map.index') }}" class="menu-link">
                                                    <span class="menu-icon">
                                                        <i class="flaticon2-indent-dots"></i>
                                                    </span>
                                                    <span class="menu-text">List</span>
                                                </a>
                                            </li>
                                            <li class="menu-item {{ Request::is('admin/product_attribute_map/create') ? 'menu-item-active' : ''  }}">
                                                <a href="{{ route('admin.product_attribute_map.create') }}" class="menu-link">
                                                    <span class="menu-icon">
                                                        <i class="flaticon2-add-1"></i>
                                                    </span>
                                                    <span class="menu-text">Add New</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                {{--                                <li class="menu-item {{ Request::is('admin/product_image*') ? 'menu-item-active' : ''  }}">--}}
                                {{--                                    <a href="{{ route('admin.product_image.index') }}" class="menu-link">--}}
                                {{--                                        <i class="menu-bullet menu-bullet-line">--}}
                                {{--                                            <span></span>--}}
                                {{--                                        </i>--}}
                                {{--                                        <span class="menu-text">Product Image List</span>--}}
                                {{--                                    </a>--}}
                                {{--                                </li>--}}
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $product_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-icon">
                                    <i class="flaticon2-shopping-cart-1"></i>
                                </span>
                            <span class="menu-text">Products</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('admin/product') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.product.index') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-indent-dots"></i>
                                            </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/product/parent') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.product.parent') }}" class="menu-link">
                                            <span class="menu-icon">
                                                <i class="flaticon2-add-1"></i>
                                            </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item {{ Request::is('admin/products/stocks*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.products.stocks') }}" class="menu-link">
                        <span class="menu-icon">
                                <i class="flaticon2-box-1"></i>
                            </span>
                            <span class="menu-text">Stocks</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('admin/product_stock_transfer*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.product_stock_transfer.index') }}" class="menu-link">
                        <span class="menu-icon">
                                <i class="flaticon2-delivery-package"></i>
                            </span>
                            <span class="menu-text">Stock Transfer</span>
                        </a>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $product_return_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-icon">
                                <i class="flaticon2-fast-back"></i>
                            </span>
                            <span class="menu-text">Product Return</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('admin/return/product') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.return.product') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/return/request-endpoint-form') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.return.request.endpoint.form') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $sold_order_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-icon">
                                <i class="flaticon2-supermarket"></i>
                            </span>
                            <span class="menu-text">Sold Order</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                @if(getActiveGuard() == 'vendor' && getActiveUserRole() == 'Warehouse Manager')
                                    <li class="menu-item {{ Request::is('admin/sale-order') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                        <a href="{{ route('admin.sale.order') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon-browser"></i>
                                        </span>
                                            <span class="menu-text">POS</span>
                                        </a>
                                    </li>
                                    <li class="menu-item {{ Request::is('admin/sale-order-wholesale') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                        <a href="{{ route('admin.sale.order.wholesale') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon-open-box"></i>
                                        </span>
                                            <span class="menu-text">Wholesale</span>
                                        </a>
                                    </li>
                                @else
                                    <li class="menu-item {{ Request::is('admin/sale-order-admin') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                        <a href="{{ route('admin.sale.order.admin') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon-shapes"></i>
                                        </span>
                                            <span class="menu-text">All</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @if(getActiveGuard() == 'vendor' && getActiveUserRole() == 'Admin' || getActiveUserRole() == 'Warehouse Manager')
                        <li class="menu-item {{ Request::is('admin/negotiation') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                            <a href="{{ route('admin.sale.negotiation') }}" class="menu-link">
                        <span class="menu-icon">
                            <i class="flaticon2-chat-2"></i>
                        </span>
                                <span class="menu-text">Order Negotiations</span>
                            </a>
                        </li>
                    @endif
                    @if(getActiveGuard() == 'vendor' && getActiveUserRole() == 'Admin')
                        <li class="menu-item {{ Request::is('admin/retail_sell') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                            <a href="{{ url('admin/retail_sell') }}" class="menu-link" target="_blank">
                        <span class="menu-icon">
                            <i class="flaticon2-chat-2"></i>
                        </span>
                                <span class="menu-text">Retail Sell</span>
                            </a>
                        </li>
                    @endif
                    <li class="menu-item {{ Request::is('admin/bulk_uploads') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.bulk.upload.admin') }}" class="menu-link">
                        <span class="menu-icon">
                            <i class="flaticon2-hourglass-1"></i>
                        </span>
                            <span class="menu-text">Bulk Upload</span>
                        </a>
                    </li>

                    <li class="menu-section">
                        <h4 class="menu-text">Expenditure</h4>
                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                    </li>
                    <li class="menu-item {{ Request::is('admin/vendor-expenses*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.vendorexpenses.all.index') }}" class="menu-link">
                            <span class="menu-icon">
                                <i class="flaticon-coins"></i>
                            </span>
                            <span class="menu-text">Expense</span>
                        </a>
                    </li>
                    <li class="menu-item menu-item-submenu {{ $purchase_active == 'active' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-icon">
                                <i class="flaticon-price-tag"></i>
                            </span>
                            <span class="menu-text">Purchase</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('admin/purchase') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.purchase.index') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">List</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/purchase/create') ? 'menu-item-active' : ''  }}">
                                    <a href="{{ route('admin.purchase.create') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-add-1"></i>
                                        </span>
                                        <span class="menu-text">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-icon">
                                <i class="flaticon-price-tag"></i>
                            </span>
                            <span class="menu-text">Sale Commission</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item">
                                    <a href="{{ route('admin.vendor.commission') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">View</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('admin.report.sale_commission.detail') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-indent-dots"></i>
                                        </span>
                                        <span class="menu-text">Detail</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    {{--
                                            <li class="menu-section">
                                                <h4 class="menu-text">Sale</h4>
                                                <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                                            </li> --}}

                    {{--                    <li class="menu-item menu-item-submenu {{ $sale_active == 'active' ? 'menu-item-open' : '' }}" aria-haspopup="true" data-menu-toggle="hover">--}}
                    {{--                        <a href="javascript:;" class="menu-link menu-toggle">--}}
                    {{--                            <span class="menu-icon">--}}
                    {{--                                <i class="flaticon-layers"></i>--}}
                    {{--                            </span>--}}
                    {{--                            <span class="menu-text">Sale</span>--}}
                    {{--                            <i class="menu-arrow"></i>--}}
                    {{--                        </a>--}}
                    {{--                        <div class="menu-submenu">--}}
                    {{--                            <ul class="menu-subnav">--}}
                    {{--                                <li class="menu-item {{ Request::is('admin/sale*') ? 'menu-item-active' : ''  }}">--}}
                    {{--                                    <a href="{{ route('admin.sale.index') }}" class="menu-link">--}}
                    {{--                                        <i class="menu-bullet menu-bullet-line">--}}
                    {{--                                            <span></span>--}}
                    {{--                                        </i>--}}
                    {{--                                        <span class="menu-text">Product Sale</span>--}}
                    {{--                                    </a>--}}
                    {{--                                </li>--}}
                    {{--                            </ul>--}}
                    {{--                        </div>--}}
                    {{--                    </li>--}}
                    <li class="menu-section">
                        <h4 class="menu-text">Reports</h4>
                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                    </li>
                    <li class="menu-item menu-item-submenu {{ Request::is('admin/report/purchase*','admin/report/brand-wise-total-purchases*') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-icon">
                                <i class="flaticon-price-tag"></i>
                            </span>
                            <span class="menu-text">Purchase</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('admin/report/purchases') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.purchases') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-infographic"></i>
                                        </span>
                                        <span class="menu-text">Purchase</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/report/purchase-details*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.purchase_details') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-heart-rate-monitor"></i>
                                        </span>
                                        <span class="menu-text">Purchase Details</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/report/purchases/supplier') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.purchases.supplier') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-heart-rate-monitor"></i>
                                        </span>
                                        <span class="menu-text">Supplierwise</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/report/purchases/supplier/details') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.purchases.supplier.details') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-heart-rate-monitor"></i>
                                        </span>
                                        <span class="menu-text">Supplierwise Details</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/report/brand-wise-total-purchases*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.brand_wise.total.purchases') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-heart-rate-monitor"></i>
                                        </span>
                                        <span class="menu-text">Brand Wise Purchases</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item menu-item-submenu {{ Request::is('admin/report/sales*','admin/report/item-wise-total-sales*','admin/report/brand-wise-total-sales*','admin/report/customer-sales') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-icon">
                            <i class="flaticon2-chart"></i>
                        </span>
                            <span class="menu-text">Sell</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('admin/report/sales*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.sales') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-chart2"></i>
                                        </span>
                                        <span class="menu-text">Sell</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/report/item-wise-total-sales*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.item_wise.total.sales') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-chart2"></i>
                                        </span>
                                        <span class="menu-text">Products Sell</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/report/customer-sales*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.customer_sales') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-chart2"></i>
                                        </span>
                                        <span class="menu-text">Customer Sell</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/report/brand-wise-total-sales*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.brand_wise.total.sales') }}" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="flaticon2-chart2"></i>
                                    </span>
                                        <span class="menu-text">Brand Wise Sell</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item {{ Request::is('admin/report/profit-loss*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.report.profit_loss') }}" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="flaticon2-chart2"></i>
                                    </span>
                            <span class="menu-text">Profit & Loss Report</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('admin/report/most_sale_products') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.report.most_sale_products') }}" class="menu-link">
                            <span class="menu-icon">
                                <i class="flaticon2-chart"></i>
                            </span>
                            <span class="menu-text">Most Sold Products</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('admin/report/order_shipment*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.report.order_shipment') }}" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="flaticon2-chart2"></i>
                                    </span>
                            <span class="menu-text">Order Shipment Report</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('admin/report/product-wise-warehouse-stock-detail*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.report.product-wise.warehouse.stock.detail') }}" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="flaticon2-chart2"></i>
                                    </span>
                            <span class="menu-text">Product Wise Warehouse Detail Report</span>
                        </a>
                    </li>
                    <li class="menu-item menu-item-submenu {{ Request::is('admin/report/product-stocks*','admin/report/stock-detail*','admin/report/category-stock-detail*','admin/report/category-wise-stock-detail*') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="menu-icon">
                        <i class="flaticon2-line-chart"></i>
                    </span>
                            <span class="menu-text">Products Stock</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('admin/report/product-stocks*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.product_stocks') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-line-chart"></i>
                                        </span>
                                        <span class="menu-text">Products Stock</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/report/stock-detail*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.stock.detail') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-line-chart"></i>
                                        </span>
                                        <span class="menu-text">Products Stock Details</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/report/category-stock-detail*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.category.stock.detail') }}" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="flaticon2-line-chart"></i>
                                    </span>
                                        <span class="menu-text">Category Products Stock Details</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/report/category-wise-stock-detail*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.category_wise.stock.detail') }}" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="flaticon2-line-chart"></i>
                                    </span>
                                        <span class="menu-text">Category Wise Stock Details</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item {{ Request::is('admin/report/warehouse-wise-stock*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.report.warehouse_wise.stock') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-line-chart"></i>
                                        </span>
                            <span class="menu-text">Warehouse Wise Stock Report</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('admin/report/customer-wise-product-buy*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.report.customer_wise.product.buy') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-line-chart"></i>
                                        </span>
                            <span class="menu-text">Customer Wise Product Buy</span>
                        </a>
                    </li>
                    <li class="menu-item menu-item-submenu {{ Request::is('admin/report/brand-wise-due*','admin/report/customer-wise-due*','admin/report/sale-wise-due*') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="menu-icon">
                        <i class="flaticon2-line-chart"></i>
                    </span>
                            <span class="menu-text">Due Reports</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">
                                <li class="menu-item {{ Request::is('admin/report/brand-wise-due*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.brand_wise.due') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-line-chart"></i>
                                        </span>
                                        <span class="menu-text">Brand Wise Due</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/report/customer-wise-due*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.customer_wise.due') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-line-chart"></i>
                                        </span>
                                        <span class="menu-text">Customer Wise Due</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ Request::is('admin/report/sale-wise-due*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                                    <a href="{{ route('admin.report.sale_wise.due') }}" class="menu-link">
                                        <span class="menu-icon">
                                            <i class="flaticon2-line-chart"></i>
                                        </span>
                                        <span class="menu-text">Sale Order Wise Due</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item {{ Request::is('admin/report/alert-quantity-details*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.report.product_alert_quantity') }}" class="menu-link">
                            <span class="menu-icon">
                                <i class="flaticon2-pie-chart"></i>
                            </span>
                            <span class="menu-text">Reorder Quantity</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('admin/report/supplier-ledgers*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.report.supplier_ledgers') }}" class="menu-link">
                            <span class="menu-icon">
                                <i class="flaticon2-analytics"></i>
                            </span>
                            <span class="menu-text">Supplier Ledgers</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('admin/report/vendor-expense*') ? 'menu-item-active' : ''  }}" aria-haspopup="true">
                        <a href="{{ route('admin.report.vendor_expense') }}" class="menu-link">
                            <span class="menu-icon">
                                <i class="flaticon2-pie-chart-1"></i>
                            </span>
                            <span class="menu-text">Expenses</span>
                        </a>
                    </li>
                @endif
            </ul>
            <!--end::Menu Nav-->
        </div>
        <!--end::Menu Container-->
    </div>
    <!--end::Aside Menu-->
</div>
