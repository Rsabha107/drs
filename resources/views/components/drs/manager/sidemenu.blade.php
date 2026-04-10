<nav class="navbar navbar-vertical navbar-expand-lg" data-navbar-appearance="darker">
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <!-- scrollbar removed-->
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                <li class="nav-item">
                    <!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-home" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-home">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span class="fas fa-caret-right dropdown-indicator-icon"></span></div><span class="nav-link-icon"><span data-feather="pie-chart"></span></span><span class="nav-link-text">Home</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-home">
                                <li class="collapsed-nav-item-title d-none">Home
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('mds.customer.dashboard') }}">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Dashboard</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">Apps
                    </p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator  label-1" href="#nv-MDS" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-MDS">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span class="fas fa-caret-right dropdown-indicator-icon"></span></div><span class="nav-link-icon"><span data-feather="phone"></span></span><span class="nav-link-text">MDS</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent {{ Request::is('mds/customer/booking')||Request::is('mds/customer/booking/create') ? 'show' : '' }}" data-bs-parent="#navbarVerticalCollapse" id="nv-MDS">
                                <li class="collapsed-nav-item-title d-none">MDS
                                </li>
                                <li class="nav-item"><a class="nav-link {{ Request::is('mds/customer/booking') ? 'active' : '' }}" href="{{route('mds.customer.booking')}}">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">List of Bookings</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link {{ Request::is('mds/customer/booking/create') ? 'active' : '' }}" href="{{route('mds.customer.booking.create')}}">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Make a Booking</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                @hasrole('Manager')
                                <li class="nav-item"><a class="nav-link {{ Request::is('mds/manager/booking') ? 'active' : '' }}" href="{{route('mds.customer.booking.create')}}">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Functional Area List</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                @endhasrole
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">Settings
                    </p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link label-1 {{ Request::is('mds/setting/driver') ? 'active' : '' }}" href="{{route('mds.setting.driver')}}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="compass"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Drivers</span></span>
                            </div>
                        </a>
                    </div>
                    <!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link label-1 {{ Request::is('mds/setting/vehicle') ? 'active' : '' }}" href="{{route('mds.setting.vehicle')}}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="compass"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Vehicles</span></span>
                            </div>
                        </a>
                    </div>
                </li>
                @if (Auth::user()->can('roles.permissions.menu'))
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">Roles and Permissions
                    </p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-list" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-list">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span class="fas fa-caret-right dropdown-indicator-icon"></span></div><span class="nav-link-icon"><span data-feather="file-text"></span></span><span class="nav-link-text">List</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-list">
                                <li class="collapsed-nav-item-title d-none">List
                                </li>
                                <li class="nav-item"><a class="nav-link {{ Request::is('sec/groups/list') ? 'active' : '' }}" href="{{route('sec.groups.list')}}">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Groups</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link {{ Request::is('sec/permissions/list') ? 'active' : '' }}" href="{{route('sec.perm.list')}}">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Permissions</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link {{ Request::is('sec/roles/list') ? 'active' : '' }}" href="{{route('sec.roles.list')}}">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Roles</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link label-1 {{ Request::is('sec/rolesetup/list') ? 'active' : '' }}" href="{{route('sec.rolesetup.list')}}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="server"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Roles in Permission</span></span>
                            </div>
                        </a>
                    </div>
                </li>
                @endif
                @if (Auth::user()->can('manage.customer.users.menu'))
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">User Management
                    </p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link label-1 {{ Request::is('sec/adminuser/list')}}" href="{{route('sec.adminuser.list')}}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="life-buoy"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">List Users</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper"><a class="nav-link label-1 {{ Request::is('sec/adminuser/add')}}" href="{{route('sec.adminuser.add')}}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="life-buoy"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Add User</span></span>
                            </div>
                        </a>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="navbar-vertical-footer">
        <button class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center"><span class="uil uil-left-arrow-to-left fs-8"></span><span class="uil uil-arrow-from-right fs-8"></span><span class="navbar-vertical-footer-text ms-2">Collapsed View</span></button>
    </div>
</nav>