<nav class="navbar navbar-vertical navbar-expand-lg" data-navbar-appearance="darker">
    <script>
        var navbarStyle = window.config.config.phoenixNavbarStyle;
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('body').classList.add(`navbar-${navbarStyle}`);
        }
    </script>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <!-- scrollbar removed-->
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                @hasanyrole('SuperAdmin|HRMSADMIN')
                <li class="nav-item">
                    <!-- parent pages-->
                    <div class="nav-item-wrapper">
                        <a
                            class="nav-link dropdown-indicator label-1"
                            href="#nv-home"
                            role="button"
                            data-bs-toggle="collapse"
                            aria-expanded="true"
                            aria-controls="nv-home">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon"><span data-feather="pie-chart"></span></span><span class="nav-link-text">Home</span><span
                                    class="fa-solid fa-circle text-info ms-1 new-page-indicator"
                                    style="font-size: 6px"></span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul
                                class="nav collapse parent show"
                                data-bs-parent="#navbarVerticalCollapse"
                                id="nv-home">
                                <li class="collapsed-nav-item-title d-none">Home</li>
                                @if (Auth::user()->can('hrms.menu'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('hr.admin.dashboard') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">HRMS</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </li>
                @endhasanyrole
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">Apps</p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->

                                        <!-- ************* Project Management **************** -->
                                        @if (Auth::user()->can('procurement.menu'))
                    <div class="nav-item-wrapper">
                        <a
                            class="nav-link dropdown-indicator label-1 {{ Request::is('procurement/admin*') ? '' : 'collapsed' }}"
                            href="#nv-procurement-managementz"
                            role="button"
                            data-bs-toggle="collapse"
                            aria-expanded="{{ Request::is('procurement/admin*') ? 'true' : 'false' }}"
                            aria-controls="nv-procurement-managementz">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon"><i class="fa-solid fa-clipboard text-success"></i></span><span class="nav-link-text">Procurement</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul
                                class="nav collapse parent {{ Request::is('procurement/admin*') ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse"
                                id="nv-procurement-managementz">
                                <li class="collapsed-nav-item-title d-none">
                                    procurement management
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('procurement/admin/vendor') ? 'active' : '' }}" href="{{ route('procurement.admin.vendor') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Vendor</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                {{-- <li class="nav-item">
                                    <a class="nav-link {{ Request::is('projects/admin/task/template') ? 'active' : '' }}" 
                                        href="{{ route('projects.admin.task.template.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Templates</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li> --}}
                                @if (Auth::user()->can('procurement.setting.menu'))
                                <li class="nav-item">
                                    <a
                                        class="nav-link dropdown-indicator {{ Request::is('projects/admin/setting*') ? '' : 'collapsed' }}"
                                        href="#nv-settings"
                                        data-bs-toggle="collapse"
                                        aria-expanded="{{ Request::is('projects/admin/setting*') ? 'true' : 'false' }}"
                                        aria-controls="nv-settings">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown-indicator-icon-wrapper">
                                                <span
                                                    class="fas fa-caret-right dropdown-indicator-icon"></span>
                                            </div>
                                            <span class="nav-link-text">Settings</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                    <div class="parent-wrapper">
                                        <ul
                                            class="nav collapse parent {{ Request::is('projects/admin/setting*') ? 'show' : '' }}"
                                            data-bs-parent="#settings"
                                            id="nv-settings">
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/category') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.category.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Category</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/audience') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.audience.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Audience</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            {{-- <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/setup/planner-list') ? 'active' : '' }}"
                                                    href="{{ route('tracki.setup.planner') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Planner</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li> --}}
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/tag') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.tag.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Tags</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                           <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/location') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.location.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Location</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/venue') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.venue.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Venue</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                           <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/location') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.location.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Location</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/departments')||Request::is('projects/admin/setting/departments') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.departments') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Departments</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/funcareas')||Request::is('projects/admin/setting/funcareas') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.funcareas') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Functional Area</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/projecttype') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.projecttype.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Project Type</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            {{--  
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/setup/priority/*') ? 'active' : '' }}"
                                                    href="{{ route('tracki.setup.priority.manage') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Priority</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/setup/projecttype-list') ? 'active' : '' }}"
                                                    href="{{ route('tracki.setup.projecttype') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Project Type</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/setup/fundcategory-list') ? 'active' : '' }}"
                                                    href="{{ route('tracki.setup.fundcategory') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Fund Category</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/setup/workspace') ? 'active' : '' }}"
                                                    href="{{ route('tracki.setup.workspace') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Workspace</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li> --}}
                                        </ul>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    @endif

                    <!-- ************* Project Management **************** -->
                    @if (Auth::user()->can('project.menu'))
                    <div class="nav-item-wrapper">
                        <a
                            class="nav-link dropdown-indicator label-1 {{ Request::is('projects/admin/project*') || Request::is('projects/admin/task*') || Request::is('projects/admin/setting*') ? '' : 'collapsed' }}"
                            href="#nv-project-managementz"
                            role="button"
                            data-bs-toggle="collapse"
                            aria-expanded="{{ Request::is('projects/admin/project*') || Request::is('projects/admin/task*') || Request::is('projects/admin/setting*') ? 'true' : 'false' }}"
                            aria-controls="nv-project-managementz">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon"><i class="fa-solid fa-clipboard text-success"></i></span><span class="nav-link-text">Project management</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul
                                class="nav collapse parent {{ Request::is('projects/admin/project*')||Request::is('projects/admin/task*') || Request::is('projects/admin/setting*') ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse"
                                id="nv-project-managementz">
                                <li class="collapsed-nav-item-title d-none">
                                    Project management
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link dropdown-indicator {{ Request::is('projects/admin/project*') || Request::is('projects/admin/task*') ? '' : 'collapsed' }}"
                                        href="#nv-admin"
                                        data-bs-toggle="collapse"
                                        aria-expanded="true"
                                        aria-controls="nv-admin">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown-indicator-icon-wrapper">
                                                <span
                                                    class="fas fa-caret-right dropdown-indicator-icon"></span>
                                            </div>
                                            <span class="nav-link-text">Projects</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                    <div class="parent-wrapper">
                                        <ul
                                            class="nav collapse parent {{ Request::is('projects/admin/project*') || Request::is('projects/admin/task*') ? 'show' : '' }}"
                                            data-bs-parent="#project-managementz"
                                            id="nv-admin">
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/project*') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.project','') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">All projects</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            {{-- <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/project/archive*') ? 'active' : '' }}"
                                                    href="{{ route('tracki.project.show.card') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text text-danger">Archieved</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li> --}}
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('projects/admin/task') ? 'active' : '' }}" href="{{ route('projects.admin.task') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Tasks</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('projects/admin/task/template') ? 'active' : '' }}" 
                                        href="{{ route('projects.admin.task.template.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Templates</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                @if (Auth::user()->can('project.setting.menu'))
                                <li class="nav-item">
                                    <a
                                        class="nav-link dropdown-indicator {{ Request::is('projects/admin/setting*') ? '' : 'collapsed' }}"
                                        href="#nv-settings"
                                        data-bs-toggle="collapse"
                                        aria-expanded="{{ Request::is('projects/admin/setting*') ? 'true' : 'false' }}"
                                        aria-controls="nv-settings">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown-indicator-icon-wrapper">
                                                <span
                                                    class="fas fa-caret-right dropdown-indicator-icon"></span>
                                            </div>
                                            <span class="nav-link-text">Settings</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                    <div class="parent-wrapper">
                                        <ul
                                            class="nav collapse parent {{ Request::is('projects/admin/setting*') ? 'show' : '' }}"
                                            data-bs-parent="#settings"
                                            id="nv-settings">
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/category') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.category.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Category</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/audience') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.audience.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Audience</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            {{-- <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/setup/planner-list') ? 'active' : '' }}"
                                                    href="{{ route('tracki.setup.planner') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Planner</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li> --}}
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/tag') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.tag.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Tags</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                           <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/location') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.location.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Location</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/venue') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.venue.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Venue</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                           <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/location') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.location.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Location</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/departments')||Request::is('projects/admin/setting/departments') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.departments') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Departments</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/funcareas')||Request::is('projects/admin/setting/funcareas') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.funcareas') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Functional Area</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('projects/admin/setting/projecttype') ? 'active' : '' }}"
                                                    href="{{ route('projects.admin.setting.projecttype.index') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Project Type</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            {{--  
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/setup/priority/*') ? 'active' : '' }}"
                                                    href="{{ route('tracki.setup.priority.manage') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Priority</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/setup/projecttype-list') ? 'active' : '' }}"
                                                    href="{{ route('tracki.setup.projecttype') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Project Type</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/setup/fundcategory-list') ? 'active' : '' }}"
                                                    href="{{ route('tracki.setup.fundcategory') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Fund Category</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/setup/workspace') ? 'active' : '' }}"
                                                    href="{{ route('tracki.setup.workspace') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Workspace</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li> --}}
                                        </ul>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    @endif

                    <!-- ************* HRMS **************** -->
                    @if (Auth::user()->can('hrms.menu'))
                    <div class="nav-item-wrapper">
                        <a
                            class="nav-link dropdown-indicator label-1 {{ Request::is('hr/admin/*')||Request::is('tracki/payroll/*') ? '' : 'collapsed' }}"
                            href="#nv-hrms"
                            role="button"
                            data-bs-toggle="collapse"
                            aria-expanded="{{ Request::is('hr/admin/*') ? 'true' : 'false' }}"
                            aria-controls="nv-hrms">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon"><i class="fa fa-users text-primary" aria-hidden="true"></i></span><span class="nav-link-text">HRMS (Admin)</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul
                                class="nav collapse parent {{ Request::is('hr/admin*')||Request::is('tracki/setting/*')||Request::is('tracki/payroll*') ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse"
                                id="nv-hrms">
                                <li class="collapsed-nav-item-title d-none">
                                    HRMS
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('hr/admin/employee') ? 'active' : '' }}"
                                        href="{{ route('hr.admin.employee') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Employees</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                @if (Auth::user()->hasrole('Manager'))
                                <li class="nav-item">
                                    <a
                                        class="nav-link dropdown-indicator {{ Request::is('tracki/employee/managers/*') ? '' : 'collapsed' }}"
                                        href="#nv-manager"
                                        data-bs-toggle="collapse"
                                        aria-expanded="{{ Request::is('tracki/employee/*') ? 'true' : 'false' }}"
                                        aria-controls="nv-manager">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown-indicator-icon-wrapper">
                                                <span
                                                    class="fas fa-caret-right dropdown-indicator-icon"></span>
                                            </div>
                                            <span class="nav-link-text">Manager</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                    <div class="parent-wrapper">
                                        <ul
                                            class="nav collapse parent {{ Request::is('tracki/employee/managers*') ? 'show' : '' }}"
                                            data-bs-parent="#manager"
                                            id="nv-manager">
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/employee/managers') ? 'active' : '' }}"
                                                    href="{{ route('tracki.employee.managers') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Employees</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            @can('leave.show')
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/employee/managers/leave') ? 'active' : '' }}"
                                                    href="{{ route('tracki.employee.managers.leave') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Leaves</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            @endcan
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/employee/managers/timesheet') ? 'active' : '' }}"
                                                    href="{{ route('tracki.employee.managers.timesheet') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Time Sheets</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @endif
                                @if (Auth::user()->hasrole('Payroll'))
                                <li class="nav-item">
                                    <a
                                        class="nav-link dropdown-indicator {{ Request::is('tracki/payroll/*') ? '' : 'collapsed' }}"
                                        href="#nv-payroll"
                                        data-bs-toggle="collapse"
                                        aria-expanded="{{ Request::is('tracki/employee/*') ? 'true' : 'false' }}"
                                        aria-controls="nv-payroll">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown-indicator-icon-wrapper">
                                                <span
                                                    class="fas fa-caret-right dropdown-indicator-icon"></span>
                                            </div>
                                            <span class="nav-link-text">Payroll</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                    <div class="parent-wrapper">
                                        <ul
                                            class="nav collapse parent {{ Request::is('tracki/payroll*') ? 'show' : '' }}"
                                            data-bs-parent="#payroll"
                                            id="nv-payroll">
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/payroll/timesheet') ? 'active' : '' }}"
                                                    href="{{ route('hr.payroll.timesheet') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Timesheets</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/payroll/timesheet/missing') ? 'active' : '' }}"
                                                    href="{{ route('hr.payroll.timesheet.missing') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Missing Timesheets</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/payroll/bank') ? 'active' : '' }}"
                                                    href="{{ route('hr.payroll.bank') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Bank File</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/payroll/payment') ? 'active' : '' }}"
                                                    href="{{ route('hr.payroll.payment') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Payment File</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @endif
                                @hasanyrole('SuperAdmin|HRMSADMIN')
                                @can('leave.show')
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('hr/admin/leave') ? 'active' : '' }} label-1"
                                        href="{{route('hr.admin.leave')}}" role="button">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Leaves</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                @endcan
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('hr/admin/address') ? 'active' : '' }} label-1"
                                        href="{{route('hr.admin.address')}}" role="button">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Addresses</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('hr/admin/bank') ? 'active' : '' }} label-1"
                                        href="{{route('hr.admin.bank')}}" role="button">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Banks</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('hr/admin/timesheet') ? 'active' : '' }} label-1"
                                        href="{{route('hr.admin.timesheet')}}" role="button">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Time Sheet</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('hr/admin/salary') ? 'active' : '' }} label-1"
                                        href="{{route('hr.admin.salary')}}" role="button">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Salary</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('hr/admin/emergency') ? 'active' : '' }} label-1"
                                        href="{{route('hr.admin.emergency')}}" role="button">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Emergency Contact</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('hr/amdin/files') ? 'active' : '' }}"
                                        href="{{ route('hr.admin.files') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Attachments</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                @endhasanyrole
                                @hasanyrole('SuperAdmin|HRMSADMIN')
                                <li class="nav-item">
                                    <a
                                        class="nav-link dropdown-indicator {{ Request::is('tracki/employee/letter*') ? '' : 'collapsed' }}"
                                        href="#nv-letter"
                                        data-bs-toggle="collapse"
                                        aria-expanded="{{ Request::is('tracki/employee/letter*') ? 'true' : 'false' }}"
                                        aria-controls="nv-letter">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown-indicator-icon-wrapper">
                                                <span
                                                    class="fas fa-caret-right dropdown-indicator-icon"></span>
                                            </div>
                                            <span class="nav-link-text">Letter</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                    <div class="parent-wrapper">
                                        <ul
                                            class="nav collapse parent {{ Request::is('hr/admin/letter/*') ? 'show' : '' }}"
                                            data-bs-parent="#letter"
                                            id="nv-letter">
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/letter/generate') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.letter.generate') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Generate</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/letter') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.letter') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Template</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @endhasanyrole
                                @if (Auth::user()->can('audit.show'))
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('hr/admin/setting/audit') ? 'active' : '' }} label-1"
                                        href="{{route('hr.admin.setting.audit')}}" role="button">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Audit</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                @endif
                                @if (Auth::user()->can('setup.menu'))
                                <li class="nav-item">
                                    <a
                                        class="nav-link dropdown-indicator {{ Request::is('tracki/employee/*') ? '' : 'collapsed' }}"
                                        href="#nv-settings"
                                        data-bs-toggle="collapse"
                                        aria-expanded="{{ Request::is('tracki/employee/*') ? 'true' : 'false' }}"
                                        aria-controls="nv-settings">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown-indicator-icon-wrapper">
                                                <span
                                                    class="fas fa-caret-right dropdown-indicator-icon"></span>
                                            </div>
                                            <span class="nav-link-text">Settings</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                    <div class="parent-wrapper">
                                        <ul
                                            class="nav collapse parent {{ Request::is('hr/admin/setting/*') ? 'show' : '' }}"
                                            data-bs-parent="#settings"
                                            id="nv-settings">
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/designations') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.designations') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Jobs</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/joblevel') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.joblevel') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Job Level</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>

                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/sponsorship') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.sponsorship') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Sponsorships</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/contracttype') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.contracttype') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Contract Type</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/addresstype') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.addresstype') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Address Types</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/funcareas') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.funcareas') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Functional Area</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/gender') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.gender') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Gender</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/prefix') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.prefix') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Prefix</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/marital') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.marital') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Marital Satus</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/countries') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.countries') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Countries</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/nationalities') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.nationalities') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Nationality</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/departments') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.departments') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Departments</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            {{-- <!-- <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('tracki/setup/priority/*') ? 'active' : '' }}"
                                                    href="{{ route('tracki.setup.priority.manage') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Priority</span>
                                                    </div>
                                                </a>
                                            </li> --> --}}
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/entities') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.entities') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Entity</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/directorates') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.directorates') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Directorate</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/relationships') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.relationships') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Relationship</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/leavetypes') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.leavetypes') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Leave Types</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/element/classifications') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.element.classifications') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Element Classifications</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/element') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.element') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Elements</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/elementset') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.elementset') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Element Sets</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            {{-- <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/elementset/assignment') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.elementset.assignment') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Element Set Assignment</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li> --}}
                                            @hasanyrole('Payroll|SuperAdmin')
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/paycycle') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.paycycle') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Pay Cycle</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            @endif
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link {{ Request::is('hr/admin/setting/invoice/notes') ? 'active' : '' }}"
                                                    href="{{ route('hr.admin.setting.invoice.notes') }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-text">Invoice Notes</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>


                                        </ul>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    @endif
                </li>

                @if (Auth::user()->can('roles.permissions.menu')||Auth::user()->can('manage.admin.users.menu'))
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">Security/Privacy</p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    @if (Auth::user()->can('roles.permissions.menu'))
                    <div class="nav-item-wrapper">
                        <a
                            class="nav-link dropdown-indicator label-1 {{ Request::is('sec/permissions/*')
                                                                        ||Request::is('sec/roles/*')
                                                                        ||Request::is('sec/groups/*') ? '' : 'collapsed' }}"
                            href="#nv-security-privacy"
                            role="button"
                            data-bs-toggle="collapse"
                            aria-expanded="{{ Request::is('sec/permissions/*')||Request::is('sec/roles/*')
                                                                                     ||Request::is('sec/groups/*')
                                                                                     ||Request::is('sec/groups/*') ? 'true' : 'false' }}"
                            aria-controls="nv-security-privacy">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon"><i class="fa-solid fa-user-shield text-warning"></i></span><span class="nav-link-text">Roles & Permissioins</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul
                                class="nav collapse parent {{ Request::is('sec/permissions/*')
                                                            ||Request::is('sec/roles/*')
                                                            ||Request::is('sec/groups/*') ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse"
                                id="nv-security-privacy">
                                <li class="collapsed-nav-item-title d-none">Roles & Permissioins</li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('sec/permissions/list') ? 'active' : '' }}"
                                        href="{{route('sec.perm.list')}}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">All Permissions</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('sec/roles/roles/list') ? 'active' : '' }}"
                                        href="{{route('sec.roles.list')}}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">All Roles</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('sec/rolesetup/list') ? 'active' : '' }}"
                                        href=" {{route('sec.rolesetup.list')}}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">List Roles in Permission</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <!-- <li
                                    class="nav-item"><a class="nav-link"
                                    href="{{route('sec.rolesetup.add')}}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Role in Permission</span>
                                        </div>
                                    </a>
                                </li> -->
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('sec/groups/groups/list') ? 'active' : '' }}"
                                        href="{{route('sec.groups.list')}}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Groups</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
                    <!-- parent pages-->
                    @if (Auth::user()->can('manage.admin.users.menu'))
                    <div class="nav-item-wrapper">
                        <a
                            class="nav-link dropdown-indicator label-1 {{ Request::is('sec/adminuser/*') ? '' : 'collapsed' }}"
                            href="#nv-manage-users"
                            role="button"
                            data-bs-toggle="collapse"
                            aria-expanded="{{ Request::is('sec/adminuser/*') ? 'true' : 'false' }}"
                            aria-controls="nv-manage-users">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon"><i class="fa-solid fa-user-shield text-warning"></i></span><span class="nav-link-text">Manage Users</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul
                                class="nav collapse parent {{ Request::is('sec/adminuser/*') ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse"
                                id="nv-manage-users">
                                <li class="collapsed-nav-item-title d-none">Manage Users</li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('sec/adminuser/list') ? 'active' : '' }}"
                                        href="{{route('sec.adminuser.list')}}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">All users</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('sec/adminuser/add') ? 'active' : '' }}"
                                        href="{{route('sec.adminuser.add')}}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Add users</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ Request::is('sec/adminuser/add2') ? 'active' : '' }}"
                                        href="{{route('sec.adminuser.add2')}}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Add users2</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
                </li>
                @endif

                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">General</p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    {{-- <div class="nav-item-wrapper">
                        <a
                            class="nav-link label-1"
                            href="#"
                            role="button"
                            data-bs-toggle=""
                            aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span data-feather="life-buoy"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Company</span></span>
                            </div>
                        </a>
                    </div> --}}
                    <div class="nav-item-wrapper">
                        <a
                            class="nav-link dropdown-indicator label-1"
                            href="#nv-customization"
                            role="button"
                            data-bs-toggle="collapse"
                            aria-expanded="false"
                            aria-controls="nv-customization">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon"><span data-feather="settings"></span></span><span class="nav-link-text">Settings</span><span
                                    class="fa-solid fa-circle text-info ms-1 new-page-indicator"
                                    style="font-size: 6px"></span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul
                                class="nav collapse parent"
                                data-bs-parent="#navbarVerticalCollapse"
                                id="nv-customization">
                                <li class="collapsed-nav-item-title d-none">
                                    Customization
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link"
                                        href="documentation/customization/configuration.html">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Company Settings</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link"
                                        href="documentation/customization/styling.html">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Business Address</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link"
                                        href="documentation/customization/color.html">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Color</span>
                                        </div>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <!-- <div class="nav-item-wrapper">
                        <a
                            class="nav-link dropdown-indicator label-1"
                            href="#nv-layouts-doc"
                            role="button"
                            data-bs-toggle="collapse"
                            aria-expanded="false"
                            aria-controls="nv-layouts-doc">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon"><span data-feather="table"></span></span><span class="nav-link-text">Layouts doc</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul
                                class="nav collapse parent"
                                data-bs-parent="#navbarVerticalCollapse"
                                id="nv-layouts-doc">
                                <li class="collapsed-nav-item-title d-none">
                                    Layouts doc
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link"
                                        href="documentation/layouts/vertical-navbar.html">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Vertical navbar</span>
                                        </div>
                                    </a>
                                    
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link"
                                        href="documentation/layouts/horizontal-navbar.html">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Horizontal navbar</span>
                                        </div>
                                    </a>
                                    
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link"
                                        href="documentation/layouts/combo-navbar.html">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Combo navbar</span>
                                        </div>
                                    </a>
                                    
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link"
                                        href="documentation/layouts/dual-nav.html">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Dual nav</span>
                                        </div>
                                    </a>
                                    
                                </li>
                            </ul>
                        </div>
                    </div> -->
                    <!-- <div class="nav-item-wrapper">
                        <a
                            class="nav-link label-1"
                            href="documentation/gulp.html"
                            role="button"
                            data-bs-toggle=""
                            aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span
                                        class="fa-brands fa-gulp ms-1 me-1 fa-lg"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Gulp</span></span>
                            </div>
                        </a>
                    </div> -->
                    <!-- <div class="nav-item-wrapper">
                        <a
                            class="nav-link label-1"
                            href="documentation/design-file.html"
                            role="button"
                            data-bs-toggle=""
                            aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span data-feather="figma"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Design file</span></span>
                            </div>
                        </a>
                    </div> -->
                    <!-- <div class="nav-item-wrapper">
                        <a
                            class="nav-link label-1"
                            href="changelog.html"
                            role="button"
                            data-bs-toggle=""
                            aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span data-feather="git-merge"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Changelog</span></span>
                            </div>
                        </a>
                    </div> -->
                    <!-- <div class="nav-item-wrapper">
                        <a
                            class="nav-link label-1"
                            href="showcase.html"
                            role="button"
                            data-bs-toggle=""
                            aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span data-feather="monitor"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Showcase</span></span>
                            </div>
                        </a>
                    </div> -->
                </li>
            </ul>
        </div>
    </div>
    <div class="navbar-vertical-footer">
        <button
            class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center">
            <span class="uil uil-left-arrow-to-left fs-8"></span><span class="uil uil-arrow-from-right fs-8"></span><span class="navbar-vertical-footer-text ms-2">Collapsed View</span>
        </button>
    </div>
</nav>