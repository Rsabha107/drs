@php


    $current_event_id = session()->get('EVENT_ID');
    $event = App\Models\Drs\Event::find($current_event_id);

    $id = Auth::user()->id;
    $profileData = App\Models\User::find($id);

@endphp

<nav class="navbar navbar-top fixed-top navbar-expand mds_header_background" id="navbarDefault">
    <div class="collapse navbar-collapse justify-content-between">
        <div class="navbar-logo">

            <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse"
                aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span
                        class="toggle-line"></span></span></button>
            <a class="navbar-brand me-1 me-sm-3" href="{{ route('drs.admin.report') }}">
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset(config('settings.website_logo')) }}" alt="{{ config('settings.site_title') }}"
                            width="150" />
                        <h3 class="logo-text ms-2 d-none d-sm-block text-white">{{ config('settings.website_name') }} </h3>
                        <div class="theme-control-toggle fa-icon-wait px-2 d-none d-sm-block">
                            <h6 class="mt-2 d-sm-block d-none text-primary text-white">({{ $event?->name }})</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @php

            $user_events = App\Models\Drs\Event::where('active_flag', 1)->orderBy('name')->get();
        @endphp


        <ul class="navbar-nav navbar-nav-icons flex-row">
            <li class="nav-item">
                <div class="theme-control-toggle fa-icon-wait px-2">
                    <h6 class="mt-2 text-white">{{ $profileData->name }}</h6>
                </div>
                {{-- <div class="theme-control-toggle fa-icon-wait px-2">
                    <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox"
                        data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
                    <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle"
                        data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Switch theme"
                        style="height:32px;width:32px;"><span class="icon" data-feather="moon"></span></label>
                    <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle"
                        data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Switch theme"
                        style="height:32px;width:32px;"><span class="icon" data-feather="sun"></span></label>
                </div> --}}
            </li>

            {{-- // start of notification block --}}



            {{-- // end of notification block --}}

            {{-- // Start of Event switch block --}}
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" style="min-width: 2.25rem" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    data-bs-auto-close="outside"><span class="d-block text-white" style="height:20px;width:20px;"><span
                            data-feather="layers" style="height:20px;width:20px;"></span></span></a>

                <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border navbar-dropdown-caret"
                    id="navbarDropdownNotfication" aria-labelledby="navbarDropdownNotfication">
                    <div class="card position-relative border-0">
                        <div class="card-header p-2">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-body-emphasis mb-0">Events</h5>
                                {{-- <button class="btn btn-secondary p-0 fs-9 fw-normal" type="button">Switch to another event</button> --}}
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="scrollbar-overlay" style="height: 27rem;">
                                @foreach ($user_events as $event)
                                    @if (session()->get('EVENT_ID') == $event->id)
                                        @php
                                            $avatar_status = 'status-online';
                                            $read = 'read';
                                        @endphp
                                    @else
                                        @php
                                            $avatar_status = '';
                                            $read = 'unread';
                                        @endphp
                                    @endif
                                    <a href="{{ route('drs.admin.event.switch', $event->id) }}"
                                        class="text-decoration-none text-body-emphasis">
                                    <div class="px-2 px-sm-3 py-3 notification-card position-relative {{ $read }} border-bottom">
                                        <div
                                            class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="avatar avatar-m {{ $avatar_status }} me-3">
                                                    <img class="rounded-circle"
                                                        src="{{ route('drs.setting.event.file', $event->id) }}"
                                                        alt="" />
                                                </div>
                                                <div class="flex-1 me-sm-3">
                                                    
                                                        {{-- <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span
                                                                class='me-1 fs-10'>💬</span>Event
                                                            <span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10">10m</span>
                                                        </p> --}}
                                                    <h4 class="fs-9 text-body-emphasis">{{ $event?->name }}</h4>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        {{-- <div class="card-footer p-0 border-top border-translucent border-0">
                            <div class="my-2 text-center fw-bold fs-10 text-body-tertiary text-opactity-85"><a
                                    class="fw-bolder" href="pages/notifications.html">Notification history</a></div>
                        </div> --}}
                    </div>
                </div>
            </li>


            <li class="nav-item dropdown"><a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!"
                    role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true"
                    aria-expanded="false">
                    <div class="avatar avatar-l ">
                        <img class="rounded-circle "
                            src="{{ url('storage/upload/default.png') }}"
                            alt="" />

                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border"
                    aria-labelledby="navbarDropdownUser">
                    <div class="card position-relative border-0">
                        <div class="card-body p-0">
                            <div class="text-center pt-4 pb-3">
                                {{-- <div class="avatar avatar-xl ">
                                    <img class="rounded-circle "
                                        src="{{ asset('fnx/assets/img/team/72x72/57.webp') }}" alt="" />

                                </div> --}}
                                {{-- <h6 class="mt-2 text-body-emphasis">{{$profileData->name}}</h6> --}}
                            </div>
                            {{-- <div class="mb-3 mx-3">
                                <input class="form-control form-control-sm" id="statusUpdateInput" type="text"
                                    placeholder="Update your status" />
                            </div> --}}
                        </div>
                        {{-- <div class="overflow-auto scrollbar" style="height: 10rem;">
                            <ul class="nav d-flex flex-column mb-2 pb-1">
                                <li class="nav-item"><a class="nav-link px-3 d-block"
                                        href="{{ route('admin.users.profile') }}"> <span
                                            class="me-2 text-body align-bottom"
                                            data-feather="user"></span><span>Profile</span></a></li>
                                <li class="nav-item"><a class="nav-link px-3 d-block" href="#!"> <span
                                            class="me-2 text-body align-bottom"
                                            data-feather="settings"></span>Settings &amp; Privacy </a></li>
                                <li class="nav-item"><a class="nav-link px-3 d-block" href="#!"> <span
                                            class="me-2 text-body align-bottom" data-feather="help-circle"></span>Help
                                        Center</a></li>
                                <li class="nav-item"><a class="nav-link px-3 d-block" href="#!"> <span
                                            class="me-2 text-body align-bottom"
                                            data-feather="globe"></span>Language</a></li>
                            </ul>
                        </div> --}}
                        <div class="card-footer p-2">
                            {{-- <div class="card-footer p-0 border-top border-translucent"> --}}
                            {{-- <ul class="nav d-flex flex-column my-3">
                                <li class="nav-item"><a class="nav-link px-3 d-block" href="#!"> <span
                                            class="me-2 text-body align-bottom" data-feather="user-plus"></span>Add
                                        another account</a></li>
                            </ul> --}}
                            {{-- <hr /> --}}
                            <div class="px-3">
                                {{-- <li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            Logout
                                        </button>
                                    </form>
                                </li> --}}
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="nav-link text-danger" type="submit">
                                        <span class="me-2 text-danger" data-feather="log-out">
                                        </span>Sign out
                                    </button>
                                </form>
                                {{-- <a class="btn btn-phoenix-secondary d-flex flex-center w-100"
                                    href="{{ route('logout') }}"> <span class="me-2" data-feather="log-out">
                                    </span>Sign out</a> --}}
                            </div>
                            {{-- <div class="my-2 text-center fw-bold fs-10 text-body-quaternary"><a
                                    class="text-body-quaternary me-1" href="#!">Privacy policy</a>&bull;<a
                                    class="text-body-quaternary mx-1" href="#!">Terms</a>&bull;<a
                                    class="text-body-quaternary ms-1" href="#!">Cookies</a></div> --}}
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>
