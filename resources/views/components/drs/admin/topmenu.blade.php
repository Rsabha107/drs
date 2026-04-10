<?php

use App\Models\Workspace;
use Illuminate\Support\Facades\Log;

// $user_workspace = auth()->user()->workspaces;
if (auth()->user()->usertype == 'admin'  && !session()->has('workspace_id')) {
    Log::info('no session here.  (topmenu)');
    $all_ws = Workspace::all();
    $current_ws = null;
    $set_ws_message = "All workspaces";
    $badge_color = "primary";
} elseif (!session()->has('workspace_id')) {
    Log::info('no session here.  (topmenu)');
    // $all_ws = Workspace::all();
    $current_ws = null;
    $set_ws_message = "Please choose a workspace first";
    $badge_color = "danger";
} else {
    $current_ws = session()->get('workspace_id');
    $ws = Workspace::findOrFail($current_ws);
    $all_ws = Workspace::all();
    $set_ws_message = $ws->title;
    $badge_color = "success";
}

?>

<nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault">
    <div class="collapse navbar-collapse justify-content-between">
        <div class="navbar-logo">

            <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
            <a class="navbar-brand me-1 me-sm-3" href="{{ route('mds.admin') }}">
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center"><img src="{{ asset ('assets/img/icons/Marktoneems.jpg') }}" alt="phoenix" width="40" height="20" />
                        <p class="logo-text ms-2 d-none d-sm-block">{{__('mds.page_titlexx')}}</p>
                    </div>
                </div>
            </a>
        </div>

        <ul class="navbar-nav navbar-nav-icons flex-row">

            {{-- <div class="btn-group dropdown mt-2">

                <button class="badge badge-phoenix fs-10 badge-phoenix-{{$badge_color}} me-1 mb-1 dropdown-toggle" id="activeLinkExample" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$set_ws_message}}</button>
                <div class="dropdown-menu dropdown-menu-dark" aria-labelledby="activeLinkExample">
                @if (auth()->user()->usertype == 'admin')

                    @foreach ($all_ws as $uws)
                    <!--  logger ('uws id: '. $uws->id. ' ******* '.'current session id: '.$current_ws)  -->
                    <?php
                    ($uws->id == $current_ws) ? $active = "active" : $active = "";
                    ?>
                    <a class="dropdown-item {{$active}} disabled" href="{{route('tracki.setup.workspace.switch',$uws->id)}}">{{ $uws->title}}</a>
                    @endforeach

                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('tracki.setup.workspace.switch',0)}}">Show All</a>
                @else
                    @foreach (auth()->user()->workspaces as $uws)
                    <!-- logger ('uws id: '. $uws->id. ' ******* '.'current session id: '.$current_ws) -->
                    <?php
                    ($uws->id == $current_ws) ? $active = "active" : $active = "";
                    ?>
                    <a class="dropdown-item {{$active}}" href="{{route('tracki.setup.workspace.switch',$uws->id)}}">{{ $uws->title}}</a>
                    @endforeach

                    <!-- <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Separated link</a> -->
                @endif
                </div>
            </div> --}}
            <li class="nav-item">
                <div class="theme-control-toggle fa-icon-wait px-2">
                    <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
                    <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme"><span class="icon" data-feather="moon"></span></label>
                    <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme"><span class="icon" data-feather="sun"></span></label>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link" href="#" style="min-width: 2.5rem" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside"><span data-feather="bell" style="height:20px;width:20px;"></span></a>

                <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border border-300 navbar-dropdown-caret" id="navbarDropdownNotfication" aria-labelledby="navbarDropdownNotfication">
                    <div class="card position-relative border-0">
                        <div class="card-header p-2">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-black mb-0">Notificatons</h5>
                                <button class="btn btn-link p-0 fs--1 fw-normal" type="button">Mark all as
                                    read</button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="scrollbar-overlay" style="height: 27rem;">
                                <div class="border-300">
                                    <div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative read border-bottom">
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="{{ asset ('assets/img/team/40x40/30.webp') }}" alt="" />
                                                </div>
                                                <div class="flex-1 me-sm-3">
                                                    <h4 class="fs--1 text-black">Jessie Samson</h4>
                                                    <p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>💬</span>Mentioned you in a
                                                        comment.<span class="ms-2 text-400 fw-bold fs--2">10m</span>
                                                    </p>
                                                    <p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:41 AM </span>August 7,2021</p>
                                                </div>
                                            </div>
                                            <div class="font-sans-serif d-none d-sm-block">
                                                <button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
                                                <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative unread border-bottom">
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="avatar avatar-m status-online me-3">
                                                    <div class="avatar-name rounded-circle"><span>J</span></div>
                                                </div>
                                                <div class="flex-1 me-sm-3">
                                                    <h4 class="fs--1 text-black">Jane Foster</h4>
                                                    <p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>📅</span>Created an event.<span class="ms-2 text-400 fw-bold fs--2">20m</span></p>
                                                    <p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:20 AM </span>August 7,2021</p>
                                                </div>
                                            </div>
                                            <div class="font-sans-serif d-none d-sm-block">
                                                <button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
                                                <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative unread border-bottom">
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="avatar avatar-m status-online me-3"><img class="rounded-circle avatar-placeholder" src="{{ asset ('assets/img/team/40x40/avatar.webp') }}" alt="" />
                                                </div>
                                                <div class="flex-1 me-sm-3">
                                                    <h4 class="fs--1 text-black">Jessie Samson</h4>
                                                    <p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>👍</span>Liked your comment.<span class="ms-2 text-400 fw-bold fs--2">1h</span></p>
                                                    <p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">9:30 AM </span>August 7,2021</p>
                                                </div>
                                            </div>
                                            <div class="font-sans-serif d-none d-sm-block">
                                                <button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
                                                <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-300">
                                    <div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative unread border-bottom">
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="{{ asset ('assets/img/team/40x40/57.webp') }}" alt="" />
                                                </div>
                                                <div class="flex-1 me-sm-3">
                                                    <h4 class="fs--1 text-black">Kiera Anderson</h4>
                                                    <p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>💬</span>Mentioned you in a
                                                        comment.<span class="ms-2 text-400 fw-bold fs--2"></span>
                                                    </p>
                                                    <p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">9:11 AM </span>August 7,2021</p>
                                                </div>
                                            </div>
                                            <div class="font-sans-serif d-none d-sm-block">
                                                <button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
                                                <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative unread border-bottom">
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="{{ asset ('assets/img/team/40x40/59.webp') }}" alt="" />
                                                </div>
                                                <div class="flex-1 me-sm-3">
                                                    <h4 class="fs--1 text-black">Herman Carter</h4>
                                                    <p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>👤</span>Tagged you in a
                                                        comment.<span class="ms-2 text-400 fw-bold fs--2"></span>
                                                    </p>
                                                    <p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:58 PM </span>August 7,2021</p>
                                                </div>
                                            </div>
                                            <div class="font-sans-serif d-none d-sm-block">
                                                <button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
                                                <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative read ">
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="{{ asset ('assets/img/team/40x40/58.webp') }}" alt="" />
                                                </div>
                                                <div class="flex-1 me-sm-3">
                                                    <h4 class="fs--1 text-black">Benjamin Button</h4>
                                                    <p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>👍</span>Liked your comment.<span class="ms-2 text-400 fw-bold fs--2"></span></p>
                                                    <p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:18 AM </span>August 7,2021</p>
                                                </div>
                                            </div>
                                            <div class="font-sans-serif d-none d-sm-block">
                                                <button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
                                                <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-0 border-top border-0">
                            <div class="my-2 text-center fw-bold fs--2 text-600"><a class="fw-bolder" href="pages/notifications.html">Notification history</a></div>
                        </div>
                    </div>
                </div>
            </li>
            <!-- <li class="nav-item dropdown">
                    <a class="nav-link" id="navbarDropdownNindeDots" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" data-bs-auto-close="outside" aria-expanded="false">
                        <svg width="16" height="16" viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
                            <circle cx="2" cy="8" r="2" fill="currentColor"></circle>
                            <circle cx="2" cy="14" r="2" fill="currentColor"></circle>
                            <circle cx="8" cy="8" r="2" fill="currentColor"></circle>
                            <circle cx="8" cy="14" r="2" fill="currentColor"></circle>
                            <circle cx="14" cy="8" r="2" fill="currentColor"></circle>
                            <circle cx="14" cy="14" r="2" fill="currentColor"></circle>
                            <circle cx="8" cy="2" r="2" fill="currentColor"></circle>
                            <circle cx="14" cy="2" r="2" fill="currentColor"></circle>
                        </svg></a>

                    <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-nide-dots shadow border border-300" aria-labelledby="navbarDropdownNindeDots">
                        <div class="card bg-white position-relative border-0">
                            <div class="card-body pt-3 px-3 pb-0 overflow-auto scrollbar" style="height: 20rem;">
                                <div class="row text-center align-items-center gx-0 gy-0">
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/behance.webp') }}" alt="" width="30" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Behance</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/google-cloud.webp') }}" alt="" width="30" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Cloud</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/slack.webp') }}" alt="" width="30" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Slack</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/gitlab.webp') }}" alt="" width="30" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Gitlab</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/bitbucket.webp') }}" alt="" width="30" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">BitBucket</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/google-drive.webp') }}" alt="" width="30" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Drive</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/trello.webp') }}" alt="" width="30" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Trello</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/figma.webp') }}" alt="" width="20" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Figma</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/twitter.webp') }}" alt="" width="30" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Twitter</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/pinterest.webp') }}" alt="" width="30" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Pinterest</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/ln.webp') }}" alt="" width="30" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Linkedin</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/google-maps.webp') }}" alt="" width="30" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Maps</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/google-photos.webp') }}" alt="" width="30" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Photos</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="{{ asset ('assets/img/nav-icons/spotify.webp') }}" alt="" width="30" />
                                            <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Spotify</p>
                                        </a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li> -->

            @php
            $id = Auth::user()->id;
            $profileData = App\Models\User::find($id);
            @endphp
            <li class="nav-item dropdown"><a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-l ">
                        <img class="rounded-circle " src="{{(!empty($profileData->photo)) ? url('upload/admin_images/'.$profileData->photo) : url('upload/no_image.jpg')}}" alt="" />

                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border border-300" aria-labelledby="navbarDropdownUser">
                    <div class="card position-relative border-0">
                        <div class="card-body p-0">
                            <div class="text-center pt-4 pb-3">
                                <div class="avatar avatar-xl ">
                                    <img class="rounded-circle " src="{{(!empty($profileData->photo)) ? url('upload/admin_images/'.$profileData->photo) : url('upload/no_image.jpg')}}" alt="" />

                                </div>
                                <h6 class="mt-2 text-black">{{$profileData->name}}</h6>
                            </div>
                            <div class="mb-3 mx-3">
                                <input class="form-control form-control-sm" id="statusUpdateInput" type="text" placeholder="Update your status" />
                            </div>
                        </div>
                        <div class="overflow-auto scrollbar" style="height: 10rem;">
                            <ul class="nav d-flex flex-column mb-2 pb-1">
                                <li class="nav-item"><a class="nav-link px-3" href="{{route('mds.admin')}}"> <span class="me-2 text-900" data-feather="user"></span><span>Profile</span></a></li>
                                <li class="nav-item"><a class="nav-link px-3" href="{{ route('mds.admin')}}"><span class="me-2 text-900" data-feather="pie-chart"></span>Dashboard</a></li>
                                <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="lock"></span>Posts &amp; Activity</a></li>
                                <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="settings"></span>Settings &amp; Privacy </a></li>
                                <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="help-circle"></span>Help Center</a></li>
                                <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="globe"></span>Language</a></li>
                            </ul>
                        </div>
                        <div class="card-footer p-0 border-top">
                            <ul class="nav d-flex flex-column my-3">
                                <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="user-plus"></span>Add another account</a></li>
                            </ul>
                            <hr />
                            <div class="px-3"> <a class="btn btn-phoenix-secondary d-flex flex-center w-100" href="{{ route('mds.logout')}}"> <span class="me-2" data-feather="log-out"> </span>Sign out</a></div>
                            <div class="my-2 text-center fw-bold fs--2 text-600"><a class="text-600 me-1" href="#!">Privacy policy</a>&bull;<a class="text-600 mx-1" href="#!">Terms</a>&bull;<a class="text-600 ms-1" href="#!">Cookies</a></div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>
