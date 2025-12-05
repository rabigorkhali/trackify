<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
{{--        <!-- Search -->--}}
{{--        <div class="navbar-nav align-items-center">--}}
{{--            <div class="nav-item navbar-search-wrapper mb-0">--}}
{{--                <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">--}}
{{--                    <i class="ti ti-search ti-md me-2"></i>--}}
{{--                    <span class="d-none d-md-inline-block text-muted">Search (Ctrl+/)</span>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <!-- /Search -->--}}

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Global Kanban -->
            @if(hasPermission('/kanban', 'get'))
                <li class="nav-item me-2 me-xl-0">
                    <a class="nav-link" href="{{ route('kanban.index') }}" title="Global Kanban">
                        <i class="ti ti-layout-kanban ti-md"></i> Kanban
                    </a>
                </li>
            @endif
            <!-- / Global Kanban -->

            <!-- Notifications -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" id="notificationDropdown">
                    <i class="ti ti-bell ti-md"></i>
                    <span class="badge bg-danger rounded-pill badge-notifications" id="notification-count" style="display: none;">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0" style="width: 380px;">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h5 class="text-body mb-0 me-auto">Notifications</h5>
                            <a href="javascript:void(0);" class="dropdown-notifications-all text-body" id="mark-all-read" data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read">
                                <i class="ti ti-mail-opened fs-4"></i>
                            </a>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container" style="max-height: 400px; overflow-y: auto;">
                        <ul class="list-group list-group-flush" id="notifications-list">
                            <li class="list-group-item list-group-item-action dropdown-notifications-item text-center py-5">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mb-0 mt-2 text-muted">Loading notifications...</p>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown-menu-footer border-top">
                        <a href="{{ route('notifications.index') }}" class="dropdown-item d-flex justify-content-center p-3">
                            View all notifications
                        </a>
                    </li>
                </ul>
            </li>
            <!-- / Notifications -->

            <!-- Style Switcher -->
            <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="ti ti-md"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                            <span class="align-middle"><i class="ti ti-sun me-2"></i>Light</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                            <span class="align-middle"><i class="ti ti-moon me-2"></i>Dark</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                            <span class="align-middle"><i class="ti ti-device-desktop me-2"></i>System</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- / Style Switcher-->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{asset(authUser()->image)}}" alt class="h-auto rounded-circle"/>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="pages-account-settings-account.html">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{asset(authUser()->image)}}" alt class="h-auto rounded-circle"/>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-medium d-block">{{authUser()->name}}</span>
                                    <small class="text-muted">{{ucfirst(authUser()->role->name)}}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('profile.index')}}">
                            <i class="ti ti-user-check me-2 ti-sm"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('change.password')}}">
                            <i class="ti ti-eye me-2 ti-sm"></i>
                            <span class="align-middle">{{__('Change Password')}}</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('configs.index')}}">
                            <i class="ti ti-settings me-2 ti-sm"></i>
                            <span class="align-middle">Settings</span>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>


                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <i class="ti ti-logout me-2 ti-sm"></i>
                            <span class="align-middle">{{ __('Logout') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>

    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper d-none">
        <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..."
               aria-label="Search..."/>
        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
    </div>
</nav>

<style>
    .badge-notifications {
        position: absolute;
        top: -5px;
        right: -8px;
        font-size: 0.65rem;
        padding: 0.25em 0.45em;
    }
    .dropdown-notifications-item {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .dropdown-notifications-item:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    .dropdown-notifications-item.unread {
        background-color: rgba(var(--bs-primary-rgb), 0.05);
    }
    .notification-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let notificationsLoaded = false;

    // Load notifications when dropdown is opened
    document.getElementById('notificationDropdown').addEventListener('click', function(e) {
        if (!notificationsLoaded) {
            loadNotifications();
            notificationsLoaded = true;
        }
    });

    // Load notification count on page load
    loadNotificationCount();

    // Refresh notifications every 30 seconds
    setInterval(loadNotificationCount, 30000);

    function loadNotificationCount() {
        fetch('{{ route('notifications.unread-count') }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notification-count');
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(error => console.error('Error loading notification count:', error));
    }

    function loadNotifications() {
        console.log('Loading notifications...');
        fetch('{{ route('notifications.recent') }}')
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Notifications data:', data);
                const list = document.getElementById('notifications-list');
                list.innerHTML = '';

                if (!data.notifications || data.notifications.length === 0) {
                    list.innerHTML = `
                        <li class="list-group-item text-center py-5">
                            <i class="ti ti-bell-off" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mb-0 mt-2 text-muted">No notifications</p>
                        </li>
                    `;
                } else {
                    data.notifications.forEach(notification => {
                        const li = createNotificationItem(notification);
                        list.appendChild(li);
                    });
                }

                // Update count badge
                const badge = document.getElementById('notification-count');
                if (data.unread_count > 0) {
                    badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                document.getElementById('notifications-list').innerHTML = `
                    <li class="list-group-item text-center py-3 text-danger">
                        <i class="ti ti-alert-circle me-2"></i>Failed to load notifications
                        <br><small>${error.message}</small>
                    </li>
                `;
            });
    }

    function createNotificationItem(notification) {
        const li = document.createElement('li');
        li.className = 'list-group-item list-group-item-action dropdown-notifications-item' + (!notification.read ? ' unread' : '');
        li.setAttribute('data-notification-id', notification.id);

        const iconColor = `bg-label-${notification.color}`;
        const timeAgo = getTimeAgo(notification.created_at);
        const ticketUrl = notification.ticket ? `{{ url('/') }}/{{ getSystemPrefix() }}/projects/${notification.ticket.project_id}/tickets/${notification.ticket_id}/show` : '#';

        li.innerHTML = `
            <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                    <div class="notification-icon ${iconColor}">
                        <i class="ti ${notification.icon}"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 ${!notification.read ? 'fw-bold' : ''}">${notification.title}</h6>
                    <p class="mb-0 small text-muted">${notification.message}</p>
                    <small class="text-muted">${timeAgo}</small>
                </div>
                ${!notification.read ? '<div class="flex-shrink-0"><span class="badge badge-dot bg-primary"></span></div>' : ''}
            </div>
        `;

        li.addEventListener('click', function() {
            markAsRead(notification.id);
            if (notification.ticket_id) {
                window.location.href = ticketUrl;
            }
        });

        return li;
    }

    function markAsRead(notificationId) {
        fetch(`/{{ getSystemPrefix() }}/notifications/${notificationId}/mark-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotificationCount();
            }
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }

    // Mark all as read
    document.getElementById('mark-all-read').addEventListener('click', function(e) {
        e.preventDefault();
        fetch('{{ route('notifications.mark-all-read') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
                loadNotificationCount();
            }
        })
        .catch(error => console.error('Error marking all as read:', error));
    });

    function getTimeAgo(datetime) {
        const now = new Date();
        const date = new Date(datetime);
        const seconds = Math.floor((now - date) / 1000);

        if (seconds < 60) return 'Just now';
        if (seconds < 3600) return Math.floor(seconds / 60) + ' minutes ago';
        if (seconds < 86400) return Math.floor(seconds / 3600) + ' hours ago';
        if (seconds < 604800) return Math.floor(seconds / 86400) + ' days ago';
        return date.toLocaleDateString();
    }
});
</script>
