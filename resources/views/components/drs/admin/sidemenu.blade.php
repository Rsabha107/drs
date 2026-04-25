<nav class="navbar navbar-vertical navbar-expand-lg" data-navbar-appearance="darker">
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <!-- scrollbar removed-->
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                <li class="nav-item">
                    <!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-home"
                            role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-home">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span></div><span
                                    class="nav-link-icon"><span data-feather="pie-chart"></span></span><span
                                    class="nav-link-text">Home</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-home">
                                <li class="collapsed-nav-item-title d-none">Home
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('drs.admin.dashboard') }}">
                                        <div class="d-flex align-items-center"><span
                                                class="nav-link-text">Dashboard</span>
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
                    <p class="navbar-vertical-label">Apps</p>
                    <hr class="navbar-vertical-line" />

                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1" href="#nv-guest" role="button"
                            data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-guest">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon"><span data-feather="users"></span></span>
                                <span class="nav-link-text">DRS</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent {{ Request::is('drs/admin/report*') || Request::is('drs/drs*') || Request::is('drs/setting/*') ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse" id="nv-guest">
                                <li class="collapsed-nav-item-title d-none">DRS</li>

                                <!-- DRS main pages -->
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('drs/drs*') ? 'active' : '' }}"
                                        href="{{ route('drs.drs.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Daily Run Sheet</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                @if (Auth::user()->can('setup.menu'))
                    <li class="nav-item">
                        <!-- label-->
                        <p class="navbar-vertical-label">Settings
                        </p>
                        <hr class="navbar-vertical-line" />
                        <!-- parent pages-->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('drs/setting/event') ? 'active' : '' }}"
                            href="{{ route('drs.setting.event') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-text">Event</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('drs/setting/venue') ? 'active' : '' }}"
                            href="{{ route('drs.setting.venue') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-text">Venue</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('drs/setting/functional_area') ? 'active' : '' }}"
                            href="{{ route('drs.setting.functional_area') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-text">Functional Area</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('drs/setting/sheet-type') ? 'active' : '' }}"
                            href="{{ route('drs.setting.sheet.type') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-text">Sheet Type</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('drs/setting/match') ? 'active' : '' }}"
                            href="{{ route('drs.setting.match') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-text">Match</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('drs.setting.application') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-text">Application Settings</span>
                            </div>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->hasRole('SecurityRole'))
                    <li class="nav-item">
                        <!-- label-->
                        <p class="navbar-vertical-label">Roles and Permissions
                        </p>
                        <hr class="navbar-vertical-line" />
                        <!-- parent pages-->
                        <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-list"
                                role="button" data-bs-toggle="collapse" aria-expanded="false"
                                aria-controls="nv-list">
                                <div class="d-flex align-items-center">
                                    <div class="dropdown-indicator-icon-wrapper"><span
                                            class="fas fa-caret-right dropdown-indicator-icon"></span></div><span
                                        class="nav-link-icon"><span data-feather="file-text"></span></span><span
                                        class="nav-link-text">List</span>
                                </div>
                            </a>
                            <div class="parent-wrapper label-1">
                                <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse"
                                    id="nv-list">
                                    <li class="collapsed-nav-item-title d-none">List
                                    </li>
                                    <li class="nav-item"><a
                                            class="nav-link {{ Request::is('sec/groups/list') ? 'active' : '' }}"
                                            href="{{ route('sec.groups.list') }}">
                                            <div class="d-flex align-items-center"><span
                                                    class="nav-link-text">Groups</span>
                                            </div>
                                        </a>
                                        <!-- more inner pages-->
                                    </li>
                                    <li class="nav-item"><a
                                            class="nav-link {{ Request::is('sec/permissions/list') ? 'active' : '' }}"
                                            href="{{ route('sec.perm.list') }}">
                                            <div class="d-flex align-items-center"><span
                                                    class="nav-link-text">Permissions</span>
                                            </div>
                                        </a>
                                        <!-- more inner pages-->
                                    </li>
                                    <li class="nav-item"><a
                                            class="nav-link {{ Request::is('sec/roles/list') ? 'active' : '' }}"
                                            href="{{ route('sec.roles.list') }}">
                                            <div class="d-flex align-items-center"><span
                                                    class="nav-link-text">Roles</span>
                                            </div>
                                        </a>
                                        <!-- more inner pages-->
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Request::is('log-viewer') ? 'active' : '' }}"
                                            href="{{ route('log-viewer.index') }}">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text">Application Log</span>
                                            </div>
                                        </a>
                                        <!-- more inner pages-->
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- parent pages-->
                        <div class="nav-item-wrapper"><a
                                class="nav-link label-1 {{ Request::is('sec/rolesetup/list') ? 'active' : '' }}"
                                href="{{ route('sec.rolesetup.list') }}" role="button" data-bs-toggle=""
                                aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                            data-feather="server"></span></span><span
                                        class="nav-link-text-wrapper"><span class="nav-link-text">Roles in
                                            Permission</span></span>
                                </div>
                            </a>
                        </div>
                        <!-- parent pages-->
                        <div class="nav-item-wrapper"><a
                                class="nav-link label-1 {{ Request::is('sec/audit') ? 'active' : '' }}"
                                href="{{ route('sec.audit') }}" role="button" data-bs-toggle=""
                                aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                            data-feather="server"></span></span><span
                                        class="nav-link-text-wrapper"><span class="nav-link-text">Audit</span></span>
                                </div>
                            </a>
                        </div>

                    </li>
                @endif
                @if (Auth::user()->can('manage.admin.users.menu'))
                    <li class="nav-item">
                        <!-- label-->
                        <p class="navbar-vertical-label">User Management
                        </p>
                        <hr class="navbar-vertical-line" />
                        <!-- parent pages-->
                        <div class="nav-item-wrapper"><a
                                class="nav-link label-1 {{ Request::is('sec/adminuser/list') }}"
                                href="{{ route('sec.adminuser.list') }}" role="button" data-bs-toggle=""
                                aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                            data-feather="life-buoy"></span></span><span
                                        class="nav-link-text-wrapper"><span class="nav-link-text">List
                                            Users</span></span>
                                </div>
                            </a>
                        </div>
                        @if (Auth::user()->hasRole('SecurityRole'))
                        <div class="nav-item-wrapper"><a
                                class="nav-link label-1 {{ Request::is('sec/adminuser/add') }}"
                                href="{{ route('sec.adminuser.add') }}" role="button" data-bs-toggle=""
                                aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                            data-feather="life-buoy"></span></span><span
                                        class="nav-link-text-wrapper"><span class="nav-link-text">Add
                                            User</span></span>
                                </div>
                            </a>
                        </div>
                        @endif
                        <div class="nav-item-wrapper"><a
                                class="nav-link label-1 {{ Request::is('/auth/ms-signup') }}"
                                href="{{ route('auth.ms.signup') }}" role="button" data-bs-toggle=""
                                aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                            data-feather="life-buoy"></span></span><span
                                        class="nav-link-text-wrapper"><span class="nav-link-text">Grant
                                            Access</span></span>
                                </div>
                            </a>
                        </div>
                        <div class="nav-item-wrapper"><a
                                class="nav-link label-1 {{ Request::is('wdr/admin/users/invite-user') }}"
                                href="{{ route('admin.users.invite.form') }}" role="button" data-bs-toggle=""
                                aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                            data-feather="life-buoy"></span></span><span
                                        class="nav-link-text-wrapper"><span class="nav-link-text">Invite
                                            Users</span></span>
                                </div>
                            </a>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="navbar-vertical-footer">
        <button
            class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center"><span
                class="uil uil-left-arrow-to-left fs-8"></span><span
                class="uil uil-arrow-from-right fs-8"></span><span class="navbar-vertical-footer-text ms-2">Collapsed
                View</span></button>
    </div>
</nav>
