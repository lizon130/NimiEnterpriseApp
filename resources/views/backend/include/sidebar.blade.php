<div id="layoutSidenav_nav">

    <div class="user_profile">
        <img class="profile-image"
            src="{{ Auth::user()->profile_image ? asset('uploads/user-images/' . Auth::user()->profile_image) : asset('assets/img/no-img.jpg') }}"
            alt="">

        <div class="profile-title">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
        <div class="profile-description">{{ Auth::user()->roles->name }}</div>
    </div>

    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">

            <div class="nav">

                {{-- <a class="nav-link" target="_blank" href="{{ route('home') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-globe"></i></div>
                    View Website
                </a> --}}

                @if (Helper::hasRight('setting.view'))
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#settingNav"
                        aria-expanded="@if(Route::is('admin.setting.general') || Route::is('admin.setting.static.content') || Route::is('admin.setting.legal.content') || Route::is('admin.contact') || Route::is('admin.resource')) true @else false @endif" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div> Setup
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse @if(Route::is('admin.setting.general') || Route::is('admin.setting.static.content') || Route::is('admin.setting.legal.content') || Route::is('admin.contact') || Route::is('admin.resource')) show @endif" id="settingNav" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav down">
                            @if (Helper::hasRight('setting.general'))
                                <a class="nav-link {{ Route::is('admin.setting.general') ? 'active' : '' }}"
                                    href="{{ route('admin.setting.general') }}"><i class="fa-solid fa-angles-right ikon"></i> General Setting </a>
                            @endif

                            @if (Helper::hasRight('setting.static-content'))
                                <a class="nav-link {{ Route::is('admin.setting.static.content') ? 'active' : '' }}"
                                    href="{{ route('admin.setting.static.content') }}"><i class="fa-solid fa-angles-right ikon"></i> Static Content</a>
                            @endif

                            @if (Helper::hasRight('setting.legal-content'))
                                <a class="nav-link {{ Route::is('admin.setting.legal.content') ? 'active' : '' }}"
                                    href="{{ route('admin.setting.legal.content') }}"><i class="fa-solid fa-angles-right ikon"></i> Legal Content</a>
                            @endif

                            @if (Helper::hasRight('contact.view'))
                                <a class="nav-link {{ Route::is('admin.contact') ? 'active' : '' }}"
                                    href="{{ route('admin.contact') }}"><i class="fa-solid fa-angles-right ikon"></i> Contact Management
                                </a>
                            @endif

                            @if (Helper::hasRight('resource.view'))
                                <a class="nav-link {{ Route::is('admin.resource') ? 'active' : '' }}"
                                    href="{{ route('admin.resource') }}"><i class="fa-solid fa-angles-right ikon"></i>
                                    Resource Management
                                </a>
                            @endif

							@if (Helper::hasRight('resource.view'))
                                <a class="nav-link {{ Route::is('admin.setting.reorder') ? 'active' : '' }}"
                                    href="{{ route('admin.setting.reorder') }}"><i class="fa-solid fa-angles-right ikon"></i>
                                    Re-order Management
                                </a>
                            @endif
                        </nav>
                    </div>
                @endif


                {{-- admin  --}}
                @if (Helper::hasRight('setting.view'))
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#setupNav"
                        aria-expanded="@if(Route::is('admin.role') || Route::is('admin.role.create') || Route::is('admin.role.edit') || Route::is('admin.role.right') || Route::is('admin.partner') || Route::is('admin.partner.product') || Route::is('admin.user')) true @else false @endif" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-user-tie"></i></div> Administration
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse @if(Route::is('admin.role') || Route::is('admin.role.create') || Route::is('admin.role.edit') || Route::is('admin.role.right') || Route::is('admin.partner') || Route::is('admin.partner.product') || Route::is('admin.user')) show @endif" id="setupNav" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav down">
                            @if (Helper::hasRight('role.view'))
                                <a class="nav-link {{ Route::is('admin.role') || Route::is('admin.role.create') || Route::is('admin.role.edit') ? 'active' : '' }}"
                                    href="{{ route('admin.role') }}"><i class="fa-solid fa-angles-right ikon"></i> Role Management</a>
                            @endif
                            <a class="nav-link {{ Route::is('admin.role.right') ? 'active' : '' }}"
                                href="{{ route('admin.role.right') }}"><i class="fa-solid fa-angles-right ikon"></i> Right Management</a>
                            @if (Helper::hasRight('partner.view'))
                                <a class="nav-link {{ Route::is('admin.partner') ? 'active' : '' }}"
                                    href="{{ route('admin.partner') }}"><i class="fa-solid fa-angles-right ikon"></i> Partner Management
                                </a>
                            @endif

                            @if (Helper::hasRight('partnerproduct.view'))
                                <a class="nav-link {{ Route::is('admin.partner.product') ? 'active' : '' }}"
                                    href="{{ route('admin.partner.product') }}"><i class="fa-solid fa-angles-right ikon"></i> Partner's Product Management
                                </a>
                            @endif

                            @if (Helper::hasRight('user.view'))
                                <a class="nav-link {{ Route::is('admin.user') ? 'active' : '' }}"
                                    href="{{ route('admin.user') }}"><i class="fa-solid fa-angles-right ikon"></i> User Management
                                </a>
                            @endif
                        </nav>
                    </div>
                @endif


                @if (Helper::hasRight('dashboard.view'))
                    <a class="nav-link {{ Route::is('admin.index') ? 'active' : '' }}"
                        href="{{ route('admin.index') }}" href="{{ route('admin.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div> Dashboard
                    </a>
                @endif


                @if (Helper::hasRight('category.view'))
                    <a class="nav-link {{ Route::is('admin.category') ? 'active' : '' }}"
                        href="{{ route('admin.category') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-shapes"></i></div> Category Management
                    </a>
                @endif

				@if (Helper::hasRight('brand.view'))
                    <a class="nav-link {{ Route::is('admin.brand') ? 'active' : '' }}"
                        href="{{ route('admin.brand') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-bookmark"></i></div> Brand Management
                    </a>
                @endif

                @if (Helper::hasRight('product.view'))
                    <a class="nav-link {{ Route::is('admin.product') ? 'active' : '' }}"
                        href="{{ route('admin.product') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-cube"></i></div> Product Management
                    </a>
                @endif

                @if (Helper::hasRight('stock.view'))
                    <a class="nav-link {{ Route::is('admin.stock*') ? 'active' : '' }}"
                        href="{{ route('admin.stock') }}">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        Stock Management
                    </a>
                @endif


                {{-- @if (Helper::hasRight('part.view'))
                    <a class="nav-link {{ Route::is('admin.part') ? 'active' : '' }}"
                        href="{{ route('admin.part') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-cubes"></i></div> Parts Management
                    </a>
                @endif --}}

                @if (Helper::hasRight('order.view'))
                    <a class="nav-link {{ Route::is('admin.order') ? 'active' : '' }}"
                        href="{{ route('admin.order') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-file"></i></div> Order Management
                    </a>
                @endif

                @if (Helper::hasRight('brand.view'))
                    <a class="nav-link {{ Route::is('admin.wholesale-calculation') ? 'active' : '' }}"
                        href="{{ route('admin.wholesale-calculation') }}">
                        <i class="fa-solid fa-angles-right ikon"></i> Wholesale Calculation
                    </a>
                @endif

                {{-- @if (Helper::hasRight('transaction.view'))
                    <a class="nav-link {{ Route::is('admin.transaction') ? 'active' : '' }}"
                        href="{{ route('admin.transaction') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-dollar-sign"></i></div> Transaction Management
                    </a>
                @endif --}}

                {{-- @if (Helper::hasRight('order.view'))
                    <a class="nav-link {{ Route::is('admin.inquiry') ? 'active' : '' }}"
                        href="{{ route('admin.inquiry') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div> Inquiry Management
                    </a>
                @endif --}}


                {{-- @if (Helper::hasRight('service.view'))
                    <a class="nav-link {{ Route::is('admin.service') ? 'active' : '' }}"
                        href="{{ route('admin.service') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-toolbox"></i></div> Service Management
                    </a>
                @endif --}}

                {{-- @if (Helper::hasRight('service-order.view'))
                    <a class="nav-link {{ Route::is('admin.service.order') ? 'active' : '' }}"
                        href="{{ route('admin.service.order') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-file-lines"></i></div> Service Order Management
                    </a>
                @endif --}}

                {{-- @if (Helper::hasRight('news.view'))
                    <a class="nav-link {{ Route::is('admin.news') ? 'active' : '' }}"
                        href="{{ route('admin.news') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-newspaper"></i></div> News Management
                    </a>
                @endif --}}

                {{-- @if (Helper::hasRight('catalogue.view'))
                    <a class="nav-link {{ Route::is('admin.catalogue') ? 'active' : '' }}"
                        href="{{ route('admin.catalogue') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-file-pdf"></i></div> Catalogue Management
                    </a>
                @endif --}}

            </div>
        </div>
    </nav>
</div>
