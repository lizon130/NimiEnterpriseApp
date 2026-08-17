<nav class="sb-topnav navbar navbar-expand navbar-dark">
    <a class="navbar-brand text-center ps-3" target="_blank" href="{{ route('home') }}">
        <img src="{{ Helper::getSettings('site_logo') ? asset('uploads/settings/' . Helper::getSettings('site_logo')) : asset('assets/img/Logo.png') }}"
            width="85px" alt="Logo">
    </a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
            class="fas fa-bars"></i></button>
    <ul class="ms-auto me-0 me-md-3 my-2 my-md-0 me-lg-4 gap-3">

        @if (Auth()->user()->role == 1)
            <li>
                <div class="top-language">
                    <div class="dropdown">
                        <a href="#" class="language-text text-uppercase" id="navbarDropdown" role="button"
                            aria-expanded="false">
                            <img class="flag"
                                src="{{ asset('assets/flag/') }}/@if (Session::get('admin_language') == 'en') united-kingdom.png @elseif(Session::get('admin_language') == 'fr')france.png @elseif (Session::get('admin_language') == 'es')spain.png @elseif (Session::get('admin_language') == 'pt')portugal.png @endif"
                                alt=""> {{ Session::get('admin_language') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item flag-select" href="" data-language="en">
                                    <img class="flag" src="{{ asset('assets/flag/united-kingdom.png') }}"
                                        alt=""> English
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item flag-select" href="" data-language="fr">
                                    <img class="flag" src="{{ asset('assets/flag/france.png') }}" alt="">
                                    French
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item flag-select" href="" data-language="es">
                                    <img class="flag" src="{{ asset('assets/flag/spain.png') }}" alt="">
                                    Spanish
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item flag-select" href="" data-language="pt">
                                    <img class="flag" src="{{ asset('assets/flag/portugal.png') }}" alt="">
                                    Portuguese
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        @endif
        {{-- <li>
            <div class="notification-bell">
                <button class="dropdown-button"><i class="fa-solid fa-bell"></i></button>
                <div class="dropdown-content">
                    <div class="notification">
                        <span class="notification-icon">📢</span>
                        <span class="notification-text">New announcement! Check it out.</span>
                        <span class="notification-time">3 mins ago</span>
                    </div>
                    <div class="notification">
                        <span class="notification-icon">🎉</span>
                        <span class="notification-text">You have a new follower.</span>
                        <span class="notification-time">5 mins ago</span>
                    </div>
                    <div class="notification">
                        <span class="notification-icon">📧</span>
                        <span class="notification-text">You have unread emails.</span>
                        <span class="notification-time">10 mins ago</span>
                    </div>
                </div>
            </div>
        </li> --}}
        {{-- <li class="">

            <div class="msg">
                <button class="dropdown-button"><i class="fa-regular fa-message"></i></button>
                <div class="dropdown-content">
                    <a href="#">Option 1</a>
                    <a href="#">Option 2</a>
                    <a href="#">Option 3</a>
                </div>
            </div>
        </li> --}}
        <li class="">
            <div class="ok">
                <div class="admin-profile">
                    <div class="dropdown">
                        <a href="#" class="topimage" id="navbarDropdown" role="button" aria-expanded="false">
                            <img class="profile-img"
                                src="{{ Auth::user()->profile_image ? asset('uploads/user-images/' . Auth::user()->profile_image) : asset('assets/img/no-img.jpg') }}"
                                alt="profile image">
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                    <i class="fa fa-user"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.profile.setting') }}">
                                    <i class="fa-solid fa-gear"></i> Change Password
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.logout') }}">
                                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</nav>


<style>
    /* Style for the dropdown within the .admin-profile parent */
    .admin-profile .dropdown {
        position: relative;
    }

    .admin-profile .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
    }

    .admin-profile .dropdown:hover .dropdown-menu {
        display: block;
    }
</style>
