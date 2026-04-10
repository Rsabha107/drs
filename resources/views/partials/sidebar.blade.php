<nav class="navbar navbar-vertical navbar-expand-lg">
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
                <li class="nav-item">
                    <!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-home" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-home">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div>
                                <span class="nav-link-icon"><span data-feather="home"></span></span><span class="nav-link-text">Home</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent show" data-bs-parent="#navbarVerticalCollapse" id="nv-home">
                                <li class="collapsed-nav-item-title d-none">Home
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('main.dashboard') }}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"><i class='bx bxs-dashboard text-primary' ></i><span class="nav-link-text">Dashboard</span>
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
                    <!-- <p class="navbar-vertical-label">Apps
                    </p> -->
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1 active" href="#nv-marketing" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-marketing">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div>
                                <span class="nav-link-icon"><span data-feather="briefcase"></span></span><span class="nav-link-text">Project Management</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent show" data-bs-parent="#navbarVerticalCollapse" id="nv-marketing">
                                <li class="collapsed-nav-item-title d-none">Project Management
                                </li>

                                <li class="nav-item"><a class="nav-link dropdown-indicator" href="#nv-events" data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-events">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><i class='bx bx-cube-alt text-danger-light' ></i><span class="nav-link-text">Projects</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                    <div class="parent-wrapper">
                                        <ul class="nav collapse parent shw" data-bs-parent="#marketing" id="nv-events">
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.project.show.card','') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Show</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            @if (Auth::user()->can('project.create'))
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.project.add') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Create</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            @endif
                                            @if (Auth::user()->can('project.archive'))
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.project.show.archive') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text text-danger">Archived</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            @endif
                                            @if (Auth::user()->can('gantt.menu'))
                                            <li class="nav-item"><a class="nav-link" href="{{ route('gantt') }}" target="_blank" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Gantt Chart</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>

                                <li class="nav-item"><a class="nav-link dropdown-indicator" href="#nv-tasks" data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-tasks">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><i class="fa-solid fa-list"></i><span class="nav-link-text">Tasks</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                    <div class="parent-wrapper">
                                        <ul class="nav collapse parent shw" data-bs-parent="#marketing" id="nv-tasks">
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.task.manage') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="menu-icon tf-icons bx bx-briefcase-alt-2 text-success"></span><span class="nav-link-text">All Tasks</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.task.lt') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Late</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.task.est') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Ending Soon</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.task.sst') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Starting Soon</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.kanban.show.task',11) }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Kanban</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @if (Auth::user()->can('budget.all'))
                                <li class="nav-item"><a class="nav-link dropdown-indicator" href="#nv-budget" data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-budget">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-text">Budget</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                    <div class="parent-wrapper">
                                        <ul class="nav collapse parent shw" data-bs-parent="#marketing" id="nv-budget">
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.budget.utilization') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Utilization</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.budget') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Budget Names</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.budget.fam.list') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Budget/FA Mapping</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.budget.list') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Setup Budget</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @endif
                                @if (Auth::user()->can('attendance.menu'))
                                <li class="nav-item"><a class="nav-link dropdown-indicator" href="#nv-attendance" data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-attendance">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-text">Attendance</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                    <div class="parent-wrapper">
                                        <ul class="nav collapse parent shw" data-bs-parent="#marketing" id="nv-attendance">
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.attendance.list') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Master list</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.attendance.assignment') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Assign to event</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.attendance.checkin') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Check in</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @endif
                                <!-- ******************************** this is the setup menu ******************************* -->

                                @if (Auth::user()->can('setup.menu'))

                                <li class="nav-item"><a class="nav-link dropdown-indicator" href="#nv-setup" data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-setup">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-text">Setup</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                    <div class="parent-wrapper">
                                        <ul class="nav collapse parent shw" data-bs-parent="#marketing" id="nv-setup">
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.category') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Event category</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.audience') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Audience</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.planner') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Planner</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.venue') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Venue</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.location') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Location</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>

                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.department') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Departments</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.person') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Person</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.priority.manage') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Priority</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.projecttype') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Project Type</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.status.manage') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Status</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.fundcategory') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Fund Category</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.fa') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Functional Area</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.workspace') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Workspace</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.operation') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Operation Type</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.segment') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Segment</span>
                                                    </div>
                                                </a>
                                                <!-- more inner pages-->
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="{{ route('main.setup.color') }}" data-bs-toggle="" aria-expanded="false">
                                                    <div class="d-flex align-items-center"><span class="nav-link-text">Color</span>
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
                    <div class="nav-item-wrapper"><a class="nav-link label-1" href="{{route('main.todo.manage')}}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="check-square"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Todo</span></span>
                            </div>
                        </a>
                    </div>
                    <!-- parent pages-->
                    <!-- ******************************** Role and Permission ******************************** -->
                    @if (Auth::user()->can('roles.permissions.menu'))
                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-roleperm" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-roleperm">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div>
                                <span class="nav-link-icon"><span data-feather="trello"></span></span><span class="nav-link-text">Role & Permission</span><span class="fa-solid fa-circle text-info ms-1 new-page-indicator" style="font-size: 6px"></span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-roleperm">
                                <li class="collapsed-nav-item-title d-none">All Permissions
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{route('main.sec.perm.list')}}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">All Permissions</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{route('main.sec.roles.list')}}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">All Roles</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{route('main.sec.rolesetup.list')}}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">List Roles in Permission</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{route('main.sec.rolesetup.add')}}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Role in Permission</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{route('main.sec.groups.list')}}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Groups</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
                    <!-- ******************************** Manage Admin User ******************************** -->
                    @if (Auth::user()->can('manage.admin.users.menu'))
                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-adminuser" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-adminuser">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div>
                                <span class="nav-link-icon"><span data-feather="trello"></span></span><span class="nav-link-text">Manage Users</span><span class="fa-solid fa-circle text-info ms-1 new-page-indicator" style="font-size: 6px"></span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-adminuser">
                                <li class="collapsed-nav-item-title d-none">Manage Users
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{route('main.sec.adminuser.list')}}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">All Users</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{route('main.sec.adminuser.add')}}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Add User</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{route('main.setup.usertype')}}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">User Type</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
                    @if (Auth::user()->can('user.addx'))
                    <!-- parent pages-->
                    <!-- ******************************** Kanbad and Calendar  ******************************** -->
                    <!-- <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-kanban" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-kanban">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div>
                                <span class="nav-link-icon"><span data-feather="trello"></span></span><span class="nav-link-text">Kanban</span><span class="fa-solid fa-circle text-info ms-1 new-page-indicator" style="font-size: 6px"></span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-kanban">
                                <li class="collapsed-nav-item-title d-none">Kanban
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/kanban/kanban.html" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Kanban</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/kanban/boards.html" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Boards</span><span class="badge ms-2 badge badge-phoenix badge-phoenix-info ">New</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/kanban/create-kanban-board.html" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Create
                                                board</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>  -->
                    <div class="nav-item-wrapper"><a class="nav-link label-1" href="{{route('main.auth.signup')}}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="user-plus"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Add Users</span></span>
                            </div>
                        </a>
                    </div>
                    @endif
                    @if (Auth::user()->can('calendar.menu'))
                    <div class="nav-item-wrapper"><a class="nav-link label-1" href="{{route('main.project.show.calendar')}}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="calendar"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Calendar</span></span>
                            </div>
                        </a>
                    </div>
                    @endif
                </li>

            </ul>
        </div>
    </div>
    <div class="navbar-vertical-footer">
        <button class="btn navbar-vertical-toggle border-0 fw-semi-bold w-100 white-space-nowrap d-flex align-items-center"><span class="uil uil-left-arrow-to-left fs-0"></span><span class="uil uil-arrow-from-right fs-0"></span><span class="navbar-vertical-footer-text ms-2">Collapsed View</span></button>
    </div>
</nav>
