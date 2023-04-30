<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Gentelella Alela!</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ asset('backend/usersprofiles/'.Auth::user()->image) }}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ Auth::user()->name }}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

    @php($setting_active = '')
    @if (Request::is('superadmin/vendor*', 'superadmin/country*', 'superadmin/state*', 'superadmin/city*','superadmin/user*'))
        @php( $setting_active = 'active')
    @endif
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="{{ $setting_active }}"><a><i class="fa fa-cog"></i> Setting <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li class="{{ Request::is('superadmin/country*') ? 'current-page' : '' }}"><a href="{{ route('superadmin.country.index') }}">Country</a></li>
                            <li class="{{ Request::is('superadmin/state*') ? 'current-page' : '' }}"><a href="{{ route('superadmin.state.index') }}">State</a></li>
                            <li class="{{ Request::is('superadmin/city*') ? 'current-page' : '' }}"><a href="{{ route('superadmin.city.index') }}">City</a></li>
                            <li class="{{ Request::is('superadmin/vendor*') ? 'current-page' : '' }}"><a href="{{ route('superadmin.vendor.index') }}">Company</a></li>
                            <li class="{{ Request::is('superadmin/user*') ? 'current-page' : '' }}"><a href="{{ route('superadmin.user') }}">Add User</a></li>
                        </ul>
                    </li>
                </ul>
            </div>


        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
