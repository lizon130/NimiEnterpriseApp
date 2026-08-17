<style>
    :root {
        --primary: #f85606;
        --primary-dark: #d94a04;
        --dark: #1f2937;
        --muted: #6b7280;
        --light: #fff7f2;
        --border: #eeeeee;
    }

    #header {
        background: #ffffff;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        padding: 10px 0;
        transition: all 0.3s ease;
        z-index: 9998;
    }

    #header .container {
        max-width: 1240px;
    }

    .logo img {
        max-height: 52px;
        width: auto;
        object-fit: contain;
    }

    .navbar {
        padding: 0;
    }

    .navbar ul {
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        list-style: none;
        gap: 3px;
    }

    .navbar li {
        position: relative;
        padding: 0;
    }

    .navbar a {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 9px 11px;
        font-weight: 600;
        font-size: 14px;
        color: var(--dark);
        transition: all 0.25s ease;
        text-decoration: none;
        border-radius: 10px;
        white-space: nowrap;
    }

    .navbar a:hover,
    .navbar .active {
        color: var(--primary);
        background: var(--light);
    }

    .dropdown-menu {
        border: 0;
        border-radius: 14px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        padding: 8px;
        margin-top: 12px;
        min-width: 210px;
    }

    .dropdown-menu .dropdown-item {
        justify-content: flex-start;
        padding: 10px 14px;
        font-size: 14px;
        color: var(--dark);
        border-radius: 10px;
        gap: 8px;
    }

    .dropdown-menu .dropdown-item i {
        width: 18px;
        color: var(--primary);
    }

    .dropdown-menu .dropdown-item:hover {
        background: var(--light);
        color: var(--primary);
        padding-left: 18px;
    }

    .dropdown-divider {
        margin: 6px 0;
    }

    .getstarted {
        background: var(--primary);
        color: #ffffff !important;
        border-radius: 999px !important;
        padding: 9px 20px !important;
        margin-left: 6px;
        box-shadow: 0 8px 16px rgba(248, 86, 6, 0.22);
    }

    .getstarted:hover {
        background: var(--primary-dark) !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .header__icon-size {
        position: relative;
        width: 38px;
        height: 38px;
        padding: 0 !important;
        border-radius: 50% !important;
        background: #f9fafb;
    }

    .header__icon-size:hover {
        background: var(--light);
    }

    .wish_cart--icon {
        font-size: 17px;
        color: var(--dark);
    }

    .badge-secondary {
        background: var(--primary);
        color: white;
        border-radius: 50%;
        min-width: 18px;
        height: 18px;
        line-height: 14px;
        padding: 2px 5px;
        font-size: 10px;
        position: absolute;
        top: -5px;
        right: -5px;
        border: 2px solid #fff;
    }

    .sidebar-toggle-btn {
        background: #f9fafb;
        border-radius: 50% !important;
        width: 40px;
        height: 40px;
        padding: 0 !important;
        margin-left: 4px;
    }

    .sidebar-toggle-btn:hover {
        background: var(--light);
        color: var(--primary);
    }

    .mobile-nav-toggle {
        display: none;
        font-size: 22px;
        cursor: pointer;
        color: var(--primary);
        width: 42px;
        height: 42px;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fff7f200;
        margin-top: 12px;
    }

    .top-language .language-text {
        text-decoration: none;
        color: var(--dark);
        font-weight: 700;
        font-size: 13px;
        background: var(--light);
        padding: 7px 10px;
        border-radius: 20px;
    }

    .flag {
        width: 20px;
        margin-right: 8px;
    }

    .search-input-container {
        position: relative;
        width: 100%;
    }

    .search-input-container__input {
        padding: 11px 38px 11px 15px;
        border: 1px solid #e5e7eb;
        border-radius: 999px;
        outline: none;
        width: 100%;
        font-size: 14px;
    }

    .search-input-container__input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(248, 86, 6, 0.12);
    }

    .search-input-container__input-padding {
        padding-right: 15px;
    }

    #search-input-icon-id {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
    }

    .sidebar {
        position: fixed;
        top: 0;
        right: -390px;
        width: 350px;
        height: 100%;
        background: #ffffff;
        box-shadow: -10px 0 35px rgba(0, 0, 0, 0.14);
        transition: right 0.3s ease-in-out;
        z-index: 9999;
        overflow-y: auto;
        padding: 22px;
    }

    .sidebar.active {
        right: 0;
    }

    .close-button {
        position: absolute;
        top: 18px;
        left: 18px;
        cursor: pointer;
        font-size: 18px;
        color: var(--primary);
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: var(--light);
        transition: 0.2s;
    }

    .close-button:hover {
        background: var(--primary);
        color: #fff;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        margin-top: 55px;
    }

    .sidebar ul li {
        margin-bottom: 8px;
    }

    .sidebar ul li a {
        color: var(--dark);
        text-decoration: none;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 13px;
        border-radius: 12px;
        transition: 0.2s;
        font-weight: 500;
    }

    .sidebar ul li a:hover,
    .sidebar ul li a.active {
        background: var(--light);
        color: var(--primary);
        padding-left: 18px;
    }

    .sidebar ul li a i {
        width: 22px;
        font-size: 16px;
        color: var(--primary);
    }

    .sidebar-header {
        border-bottom: 2px solid var(--primary);
        padding-bottom: 12px;
        margin-bottom: 18px;
    }

    .sidebar-header h4 {
        font-size: 21px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }

    .sidebar-header h4 i {
        margin-left: 8px;
        color: var(--primary);
    }

    .sidebar-section-title {
        margin-top: 18px;
        margin-bottom: 8px;
        padding-top: 12px;
        border-top: 1px solid var(--border);
    }

    .sidebar-section-title h4 {
        font-size: 14px;
        font-weight: 800;
        color: var(--primary);
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .social-icons-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .social-icon {
        width: 38px;
        height: 38px;
        background: var(--light);
        border-radius: 50%;
        color: var(--primary) !important;
        padding: 0 !important;
        justify-content: center;
    }

    .social-icon:hover {
        background: var(--primary) !important;
        color: #fff !important;
        transform: translateY(-2px);
        padding-left: 0 !important;
    }

    .social-icon:hover i {
        color: #fff !important;
    }

    #toastr-container {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 10000;
    }

    .d-none {
        display: none !important;
    }

    /* Mobile header icons row */
    .mobile-header-icons {
        display: none;
        align-items: center;
        gap: 6px;
    }

    .mobile-header-icons .mobile-icon-btn {
        position: relative;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f9fafb;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .mobile-header-icons .mobile-icon-btn:hover {
        background: var(--light);
    }

    .mobile-header-icons .mobile-icon-btn i {
        font-size: 17px;
        color: var(--dark);
    }

    .mobile-header-icons .mobile-icon-btn .badge-secondary {
        top: -5px;
        right: -5px;
    }

    @media (max-width: 1199px) {
        .navbar a {
            font-size: 13px;
            padding: 8px 8px;
        }

        .getstarted {
            padding: 8px 15px !important;
        }

        .logo img {
            max-height: 46px;
        }
    }

    @media (max-width: 991px) {
        #header {
            padding: 8px 0;
        }

        #header .container {
            position: relative;
        }

        .logo img {
            max-height: 44px;
        }

        .mobile-nav-toggle {
            display: flex;
        }

        .mobile-header-icons {
            display: flex;
        }

        /* Hide cart & wishlist from the mobile dropdown menu */
        .navbar ul .hide-on-mobile {
            display: none !important;
        }

        .navbar ul {
            display: none;
            position: fixed;
            top: 64px;
            left: 12px;
            right: 12px;
            width: auto;
            max-height: calc(100vh - 85px);
            overflow-y: auto;
            background: #ffffff;
            flex-direction: column;
            align-items: stretch;
            padding: 14px;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.18);
            z-index: 9999;
            gap: 4px;
        }

        .navbar ul.show {
            display: flex;
        }

        .navbar li {
            width: 100%;
            padding: 0;
        }

        .navbar a {
            width: 100%;
            justify-content: flex-start;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 15px;
        }

        .padding-sm-home {
            padding-left: 14px !important;
        }

        .nav-item.dropdown .dropdown-menu {
            position: static !important;
            transform: none !important;
            width: 100%;
            box-shadow: none;
            border-radius: 12px;
            background: #fff7f2;
            margin: 4px 0 0 0;
            padding: 6px;
        }

        .show-on-mobile {
            display: block !important;
        }

        .header__icon-size {
            width: 100%;
            height: auto;
            background: transparent;
            justify-content: flex-start !important;
            padding: 12px 14px !important;
            border-radius: 12px !important;
        }

        .badge-secondary {
            top: 8px;
            right: 12px;
        }

        .getstarted {
            width: 100%;
            justify-content: center !important;
            margin-left: 0;
            margin-top: 4px;
            border-radius: 12px !important;
        }

        .sidebar-toggle-btn {
            display: none !important;
        }

        #google_translate_element {
            width: 100%;
            padding: 8px 0;
        }

        .sidebar {
            width: 310px;
            right: -330px;
        }
    }

    @media (max-width: 576px) {
        #header .container {
            padding-left: 14px;
            padding-right: 14px;
        }

        .logo img {
            max-height: 40px;
        }

        .top-language .language-text {
            font-size: 12px;
            padding: 6px 9px;
        }

        .navbar ul {
            top: 58px;
            left: 8px;
            right: 8px;
            border-radius: 16px;
            padding: 12px;
        }

        .navbar a {
            font-size: 14px;
            padding: 11px 12px;
        }

        .sidebar {
            width: 100%;
            right: -100%;
            padding: 20px;
        }

        .sidebar ul li a {
            font-size: 14px;
        }

        .sidebar-header h4 {
            font-size: 19px;
        }

        #toastr-container {
            left: 12px;
            right: 12px;
            top: 70px;
        }
    }
</style>

<header id="header" class="fixed-top">
    <div class="container d-flex gap-2 align-items-center">
        <div class="flex-fill logo me-auto d-flex align-items-center ms-5">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/img/Logo.png') }}" alt="Logo">
            </a>
        </div>

        <!-- Mobile-only: Cart & Wishlist icons in header -->
        <div class="mobile-header-icons">
            <a class="mobile-icon-btn" href="{{ url('cart') }}">
                <i class="fa-solid fa-shopping-cart"></i>
                <span id="cart-counter-mobile" class="badge badge-secondary"
                    style="{{ count(session('cartlist') ?? []) > 0 ? '' : 'display: none;' }}">
                    {{ count(session('cartlist') ?? []) }}
                </span>
            </a>
            <a class="mobile-icon-btn" href="{{ url('wishlist') }}">
                <i class="fa-solid fa-heart"></i>
                <span id="wishlist-counter-mobile" class="badge badge-secondary"
                    style="{{ count(session('wishlist') ?? []) > 0 ? '' : 'display: none;' }}">
                    {{ count(session('wishlist') ?? []) }}
                </span>
            </a>
        </div>

        <nav id="navbar" class="navbar">
            <ul>
                <li class="show-on-mobile d-none">
                    <div class="flex-fill px-1">
                        <div class="search-input-container">
                            <form action="{{ route('search') }}" method="get">
                                <input oninput="hideIcon()" id="search-input-id" class="search-input-container__input"
                                    name="search_text" type="text" placeholder="Search" />
                                <i id="search-input-icon-id" class="fa-solid fa-magnifying-glass"></i>
                            </form>
                        </div>
                    </div>
                </li>

                <li><a class="{{ request()->is('/') ? 'active' : '' }} nav-link scrollto ps-0 padding-sm-home"
                        href="{{ url('/') }}">{{ trans('language.home') }}</a></li>

                <li><a class="{{ request()->is('products') ? 'active' : '' }} nav-link scrollto ps-0 padding-sm-home"
                        href="{{ route('products') }}">{{ trans('language.products') }}</a></li>

                <li><a class="{{ request()->is('categories') ? 'active' : '' }} nav-link scrollto"
                        href="{{ url('categories') }}">{{ trans('language.categories') }}</a></li>

                {{-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        {{ trans('language.catalogues') }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ url('catalogues') }}"><i
                                    class="fa-solid fa-file-pdf"></i>{{ trans('language.catalogues') }}</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('manuals') }}"><i
                                    class="fa-solid fa-file-lines"></i>{{ trans('language.manual') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('forms') }}"><i
                                    class="fa-solid fa-file-pen"></i>{{ trans('language.form') }}</a></li>
                    </ul>
                </li> --}}

                {{-- <li><a class="{{ request()->is('services') ? 'active' : '' }} nav-link scrollto"
                        href="{{ url('services') }}">{{ trans('language.service') }}</a></li> --}}

                <li class="hide-on-mobile">
                    <a class="{{ request()->is('cart') ? 'active' : '' }} nav-link scrollto header__icon-size"
                        href="{{ url('cart') }}">
                        <i class="fa-solid fa-shopping-cart wish_cart--icon"></i>
                        <span class="ms-2 d-block d-lg-none">Your Cart</span>
                        <span id="cart-counter" class="badge badge-secondary"
                            style="{{ count(session('cartlist') ?? []) > 0 ? '' : 'display: none;' }}">
                            {{ count(session('cartlist') ?? []) }}
                        </span>
                    </a>
                </li>

                <li class="hide-on-mobile">
                    <a class="{{ request()->is('wishlist') ? 'active' : '' }} nav-link scrollto header__icon-size"
                        href="{{ url('wishlist') }}">
                        <i class="fa-solid fa-heart wish_cart--icon"></i>
                        <span class="ms-2 d-block d-lg-none">Wishlist</span>
                        <span id="wishlist-counter" class="badge badge-secondary"
                            style="{{ count(session('wishlist') ?? []) > 0 ? '' : 'display: none;' }}">
                            {{ count(session('wishlist') ?? []) }}
                        </span>
                    </a>
                </li>

                @if (Auth::user())
                    <li><a class="getstarted scrollto"
                            href="{{ route('admin.index') }}">{{ trans('language.dashboard') }}</a></li>
                @else
                    <li><a class="getstarted scrollto" href="{{ url('login') }}">{{ trans('language.login') }}</a>
                    </li>
                @endif

                <li>
                    <a href="#" class="sidebar-toggle-btn" onclick="toggleSidebar()">
                        <i style="font-size: 20px;" class="fa fa-bars"></i>
                    </a>
                </li>

                <div id="google_translate_element"></div>
            </ul>

            <i class="fa fa-bars mobile-nav-toggle"></i>
        </nav>
    </div>

    <div class="sidebar" id="sidebar">
        <div class="close-button" onclick="toggleSidebar()">
            <i class="fa-solid fa-angles-right"></i>
        </div>

        <ul>
            <li class="sidebar-header">
                <h4>Corporate <i class="fa-solid fa-briefcase"></i></h4>
            </li>

            <li class="sidebar-section-title">
                <h4>Company Information</h4>
            </li>
            <li><a class="{{ request()->is('news') ? 'active' : '' }} nav-link scrollto"
                    href="{{ url('news') }}">{{ trans('language.news') }}</a></li>
            <li><a class="{{ request()->is('about') ? 'active' : '' }} nav-link scrollto"
                    href="{{ url('about') }}">{{ trans('language.about') }}</a></li>
            <li><a class="{{ request()->is('contact') ? 'active' : '' }} nav-link scrollto"
                    href="{{ url('contact') }}">{{ trans('language.contact') }}</a></li>

            <li class="sidebar-section-title">
                <h4>Legal Informations</h4>
            </li>
            <li><a href="{{ route('page', 'terms-and-conditions') }}">{{ trans('language.terms_condition') }}</a></li>
            <li><a href="{{ route('page', 'privacy-policy') }}">{{ trans('language.privacy_policy') }}</a></li>
            <li><a href="{{ route('page', 'return-policy') }}">{{ trans('language.return_policy') }}</a></li>

            <li class="sidebar-section-title">
                <h4>{{ trans('language.get_in_touch') }}</h4>
            </li>
            <li><a href="tel:{{ Helper::getSettings('application_phone') }}"><i
                        class="fa-solid fa-phone"></i>{{ Helper::getSettings('application_phone') }}</a></li>
            <li><a href="mailto:{{ Helper::getSettings('application_email') }}"><i
                        class="fa-regular fa-envelope"></i>{{ Helper::getSettings('application_email') }}</a></li>

            <li class="sidebar-section-title">
                <h4>Social Links</h4>
            </li>
            <li class="social-icons-row">
                <a class="social-icon" href="{{ Helper::getSettings('facebook_link') }}" target="_blank"><i
                        class="fab fa-facebook-f"></i></a>
                <a class="social-icon" href="{{ Helper::getSettings('twitter_link') }}" target="_blank"><i
                        class="fa-brands fa-x-twitter"></i></a>
                <a class="social-icon" href="{{ Helper::getSettings('instagram_link') }}" target="_blank"><i
                        class="fab fa-instagram"></i></a>
                <a class="social-icon" href="{{ Helper::getSettings('linkedin_link') }}" target="_blank"><i
                        class="fab fa-linkedin-in"></i></a>
                <a class="social-icon" href="{{ Helper::getSettings('youtube_link') }}" target="_blank"><i
                        class="fab fa-youtube"></i></a>
                <a class="social-icon" href="{{ route('review.us') }}" target="_blank"><i
                        class="fa-regular fa-star"></i></a>
            </li>
        </ul>
    </div>

    <div id="toastr-container"></div>
</header>

<script>
    const inputField = document.getElementById('search-input-id');
    const icon = document.getElementById('search-input-icon-id');

    function hideIcon() {
        if (inputField && inputField.value) {
            icon.classList.add("d-none");
            inputField.classList.add("search-input-container__input-padding");
        } else if (icon) {
            icon.classList.remove("d-none");
            inputField.classList.remove("search-input-container__input-padding");
        }
    }

    function toggleSidebar() {
        var sidebar = document.getElementById("sidebar");
        if (sidebar) {
            sidebar.classList.toggle("active");
        }
    }

    document.addEventListener("mousedown", function(event) {
        var sidebar = document.getElementById("sidebar");
        var toggleButton = document.querySelector(".sidebar-toggle-btn");

        if (sidebar && toggleButton) {
            setTimeout(function() {
                if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
                    sidebar.classList.remove("active");
                }
            }, 0);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
        const navbarUl = document.querySelector('#navbar ul');

        if (mobileNavToggle && navbarUl) {
            mobileNavToggle.addEventListener('click', function() {
                navbarUl.classList.toggle('show');
                this.classList.toggle('fa-bars');
                this.classList.toggle('fa-times');
            });
        }
    });

    document.querySelectorAll('.flag-select').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const lang = this.getAttribute('data-language');
            if (lang) {
                console.log('Language selected:', lang);
            }
        });
    });
</script>
