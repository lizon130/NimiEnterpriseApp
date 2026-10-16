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
        {{-- Notification Bell --}}
        <li>
            <div class="notification-bell" id="backendNotificationBell">
                <button class="dropdown-button" type="button" aria-label="Notifications">
                    <i class="fa-solid fa-bell"></i>
                    <span class="notification-count-badge d-none" id="notifCountBadge">0</span>
                </button>
                <div class="dropdown-content notif-dropdown" id="notifDropdown">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom notif-dropdown-header">
                        <strong><i class="fa-solid fa-bell me-1"></i> Notifications</strong>
                        <a href="javascript:void(0)" id="notifMarkAllRead" class="small text-decoration-none">Mark all as read</a>
                    </div>
                    <div id="notifList">
                        <div class="notification notif-empty text-center text-muted py-3">Loading...</div>
                    </div>
                </div>
            </div>
        </li>
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

    /* Notification bell */
    .notification-bell {
        position: relative;
    }

    .notification-bell .dropdown-button {
        position: relative;
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, .5);
        font-size: 1.25rem;
        padding: .25rem .5rem;
        cursor: pointer;
    }

    .notification-bell .dropdown-button:hover,
    .notification-bell .dropdown-button:focus {
        color: #fff;
    }

    .notification-count-badge {
        position: absolute;
        top: 0;
        right: -2px;
        min-width: 18px;
        height: 18px;
        padding: 0 4px;
        border-radius: 999px;
        background: #e74a3b;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        line-height: 18px;
        text-align: center;
    }

    .notification-bell .dropdown-content.notif-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        width: 360px;
        max-width: 90vw;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .18);
        z-index: 1080;
        overflow: hidden;
    }

    .notification-bell:hover .dropdown-content.notif-dropdown,
    .notification-bell .dropdown-content.notif-dropdown.show {
        display: block;
    }

    .notif-dropdown-header {
        background: #f8f9fc;
        color: #333;
    }

    .notif-dropdown-header strong {
        font-size: 14px;
    }

    #notifList {
        max-height: 340px;
        overflow-y: auto;
    }

    #notifList .notification {
        display: flex;
        flex-direction: column;
        padding: 10px 14px;
        border-bottom: 1px solid #f1f1f1;
        cursor: pointer;
        transition: background .15s ease;
    }

    #notifList .notification:hover {
        background: #f8f9fc;
    }

    #notifList .notification:last-child {
        border-bottom: none;
    }

    #notifList .notification.unread {
        background: #fff3ec;
    }

    #notifList .notification.unread:hover {
        background: #ffe8db;
    }

    #notifList .notif-title {
        font-size: 13.5px;
        font-weight: 600;
        color: #333;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    #notifList .notif-msg {
        font-size: 12.5px;
        color: #6b7280;
        margin-top: 2px;
    }

    #notifList .notif-time {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 4px;
    }

    #notifList .notif-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #f85606;
        display: inline-block;
        flex-shrink: 0;
    }

    /* Mobile: full width notification panel below topbar */
    @media (max-width: 767.98px) {
        .notification-bell .dropdown-content.notif-dropdown {
            position: fixed;
            top: 56px;
            left: 10px;
            right: 10px;
            width: auto;
            max-width: none;
        }
    }
</style>

@push('footer')
    <script type="text/javascript">
        function renderBackendNotifications(res) {
            var count = res.unread_count || 0;
            if (count > 0) {
                $('#notifCountBadge').removeClass('d-none').text(count > 99 ? '99+' : count);
            } else {
                $('#notifCountBadge').addClass('d-none');
            }

            var list = res.notifications || [];
            var html = '';
            if (list.length === 0) {
                html = '<div class="notification notif-empty text-center text-muted py-4">No notifications yet.</div>';
            } else {
                list.forEach(function (n) {
                    var icon = n.type === 'order' ? 'fa-cart-shopping' : 'fa-bullhorn';
                    var iconColor = n.type === 'order' ? '#f85606' : '#2563eb';
                    html += '<div class="notification ' + (n.is_read == 0 ? 'unread' : '') + '" data-id="' + n.id + '" data-url="' + (n.url || '') + '">'
                        + '<span class="notif-title">'
                        + '<span class="notif-dot" style="background:' + iconColor + '"></span>'
                        + '<i class="fa-solid ' + icon + '" style="color:' + iconColor + '"></i> '
                        + n.title
                        + '</span>'
                        + '<span class="notif-msg">' + n.message + '</span>'
                        + '<span class="notif-time"><i class="fa-regular fa-clock me-1"></i>' + n.time + '</span>'
                        + '</div>';
                });
            }
            $('#notifList').html(html);
        }

        function loadBackendNotifications() {
            $.ajax({
                url: "{{ url('admin/notification/list') }}",
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    renderBackendNotifications(res);
                }
            });
        }

        $(document).ready(function () {
            loadBackendNotifications();
            setInterval(loadBackendNotifications, 60000);

            // Toggle dropdown on bell click (needed for touch devices)
            $('#backendNotificationBell .dropdown-button').on('click', function (e) {
                e.stopPropagation();
                $('#notifDropdown').toggleClass('show');
            });

            // Close dropdown when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#backendNotificationBell').length) {
                    $('#notifDropdown').removeClass('show');
                }
            });

            $(document).on('click', '#notifList .notification', function (e) {
                e.stopPropagation();
                var el = $(this);
                var id = el.data('id');
                var url = el.attr('data-url');
                $.ajax({
                    url: "{{ url('admin/notification/read') }}/" + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (res) {
                        if (res.type === 'success') {
                            el.removeClass('unread');
                            if (url && url !== '') {
                                // e.g. order notifications -> admin order list
                                window.location.href = url;
                            } else {
                                loadBackendNotifications();
                            }
                        }
                    }
                });
            });

            $(document).on('click', '#notifMarkAllRead', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $.ajax({
                    url: "{{ url('admin/notification/read-all') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function (res) {
                        loadBackendNotifications();
                    }
                });
            });
        });
    </script>
@endpush
