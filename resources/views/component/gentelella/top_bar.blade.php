<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <nav class="nav navbar-nav">
            <ul class=" navbar-right">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                    <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('backend/usersprofiles/'.Auth::user()->image) }}" alt="">{{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item"  href="{{ route('user.profile', Auth::id()) }}"> Profile</a>

                        <a href="#" onclick="event.preventDefault(); document.getElementById('form-logout').submit()" class="dropdown-item">
                            <i class="fa fa-sign-out pull-right"></i> Log Out
                            <form style="display: none" action="{{ route('logout') }}" id="form-logout" method="post">
                                @csrf
                                <button type="submit"></button>
                            </form>
                        </a>

                    </div>
                </li>

                <li role="presentation" class="nav-item dropdown open">
                    <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false">
                        Admin Panel
                    </a>
                    <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1">
                        <li class="nav-item">
                            <a class="dropdown-item" onclick="submitAdminPanel('Notika')"><strong>Notika</strong> <p style="background-color: #00c292; padding: 5px"></p></a>
                        </li>
                        <li class="nav-item">
                            <a class="dropdown-item" onclick="submitAdminPanel('AdminLTE')"><strong>AdminLTE</strong> <p style="background-color: #2a3f54; padding: 5px"></p></a>
                        </li>
                        <li class="nav-item">
                            <a class="dropdown-item" onclick="submitAdminPanel('Gentelella')"><strong>Gentelella</strong> <p style="background-color: #2a3f54; padding: 5px"></p></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->
