<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>VMS</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/icons/sc_logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/icons/sc_logo.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/icons/sc_logo.png') }}">
    <link rel="manifest" href="{{ asset('assets/img/favicons/manifest.json') }}">
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/favicons/mstile-150x150.png') }}">
    <meta name="theme-color" content="#fff00">
    <script src="{{ asset('assets/vendors/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('fnx/vendors/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <link href="{{ asset('fnx/vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href="{{ asset('fnx/assets/css/theme-rtl.min.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('fnx/assets/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
    <link href="{{ asset('fnx/assets/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet" id="user-style-rtl">
    <link href="{{ asset('fnx/assets/css/user.min.css') }}" type="text/css" rel="stylesheet" id="user-style-default">
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/branding.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <script>
        var phoenixIsRTL = window.config.config.phoenixIsRTL;
        if (phoenixIsRTL) {
            var linkDefault = document.getElementById('style-default');
            var userLinkDefault = document.getElementById('user-style-default');
            linkDefault.setAttribute('disabled', true);
            userLinkDefault.setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            var linkRTL = document.getElementById('style-rtl');
            var userLinkRTL = document.getElementById('user-style-rtl');
            linkRTL.setAttribute('disabled', true);
            userLinkRTL.setAttribute('disabled', true);
        }
    </script>
</head>

<body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <div class="container">
            <form method="POST" action="{{ route('admin.signup.store') }}" class="forms-sample">
                @csrf
                <input type="hidden" name="token" value="{{ request('token') }}">
                <input type="hidden" name="event_id[]" value="{{ request('event_id') }}">
                <input type="hidden" name="functional_area_id[]" value="{{ request('functional_area_id') }}">
                <input type="hidden" name="venue_id[]" value="{{ request('venue_id') }}">
                <input type="hidden" name="name" value="{{ request('name') }}">
                <input type="hidden" name="email" value="{{ request('email') }}">
                <div class="row flex-center min-vh-100 py-5">
                    <div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-4">
                        <div class="card shadow-sm">
                            <div class="card-body p-4 p-sm-5">
                                <div class="text-center mb-7">
                                    <h3 class="text-body-highlight">{{ config('settings.website_name') }}
                                    </h3>
                                    <p class="text-body-tertiary">Register</p>
                                    <p class="text-body-tertiary">{{ getEventNameById(request('event_id')) }}</p>
                                    <p class="text-body-tertiary">{{ getVenueNameById(request('venue_id')) }}</p>
                                    <p class="text-body-tertiary">{{ getFunctionalAreaNameById(request('functional_area_id')) }}</p>

                                </div>
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="col mb-3">
                                    <div class="form-floating">
                                        <input class="form-control" id="add_name" type="text" placeholder="Name"
                                            value="{{ request('name') }}" disabled>
                                        <label for="add_name">Name</label>
                                    </div>
                                </div>
                                <div class="col mb-3">
                                    <div class="form-floating">
                                        <input class="form-control" id="add_email" type="text" placeholder="Email"
                                            value="{{ request('email') }}" disabled>
                                        <label for="add_email">Email</label>
                                    </div>
                                </div>
                                {{-- <x-formy.form_input class="col mb-3" floating="1" inputValue="" name="name"
                                        elementId="add_name" inputType="text" inputAttributes="" label="Name" value="{{ request('name') }}"
                                        required="required" disabled="disabled" /> --}}

                                {{-- <x-formy.form_input class="col mb-3" floating="1" inputValue="" name="phone"
                                    elementId="add_phone" inputType="text" inputAttributes="" label="Phone"
                                    required="required" disabled="0" /> --}}
                                {{-- <x-formy.form_select class="col-sm-12 col-md-12 mb-3" floating="1"
                                        selectedValue="" name="client_id" elementId="add_client_id"
                                        label="FA/Project" required="required" :forLoopCollection="$clients" itemIdForeach="id"
                                        itemTitleForeach="title" style="" addDynamicButton="0" /> --}}

                                {{-- <x-formy.form_input class="col mb-3" floating="1" inputValue=""
                                        name="email" elementId="add_email" inputType="email" inputAttributes=""
                                        label="Email" required="required" disabled="0" /> --}}

                                {{-- <x-formy.form_input class="col mb-3" floating="1" inputValue=""
                                        name="password" elementId="add_password" inputType="text" inputAttributes=""
                                        label="Password" required="required" disabled="0" /> --}}

                                <div class="mb-3 text-start">
                                    {{-- <label class="form-label" for="password">Password</label> --}}
                                    <div class="form-icon-container form-floating" data-password="data-password">
                                        <input class="form-control form-icon-input pe-6" name="password"
                                            id="password" type="password" placeholder="Password"
                                            autocomplete="new-password" data-password-input="data-password-input" />
                                        <div class="btn px-3 py-0 h-100 position-absolute top-0 end-0 fs-7 text-body-tertiary mt-1"
                                            data-password-toggle="data-password-toggle"><span
                                                class="uil uil-eye show"></span><span
                                                class="uil uil-eye-slash hide"></span></div>
                                        <label class="form-label" for="password">Password</label>
                                    </div>
                                </div>
                                <div class="mb-3 text-start">
                                    {{-- <label class="form-label" for="password">Password</label> --}}
                                    <div class="form-icon-container form-floating" data-password="data-password">
                                        <input class="form-control form-icon-input pe-6" name="password_confirmation"
                                            id="password_confirmation" type="password" placeholder="Confirm Password"
                                            autocomplete="new-password" data-password-input="data-password-input" />
                                        <div class="btn px-3 py-0 h-100 position-absolute top-0 end-0 fs-7 text-body-tertiary mt-1"
                                            data-password-toggle="data-password-toggle"><span
                                                class="uil uil-eye show"></span><span
                                                class="uil uil-eye-slash hide"></span></div>
                                        <label class="form-label" for="Confirm Password">Confirm Password</label>
                                    </div>
                                </div>
                                <div class="row flex-between-center mb-5">
                                    <div class="col-auto">

                                    </div>
                                    {{-- <div class="col-auto"><a class="fs-9 fw-semibold"
                                            href="{{ route('auth.forgot') }}">Forgot Password?</a>
                                    </div> --}}
                                </div>
                                <button class="btn btn-primary w-100 mb-3">Register</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <script>
            var navbarTopStyle = window.config.config.phoenixNavbarTopStyle;
            var navbarTop = document.querySelector('.navbar-top');
            if (navbarTopStyle === 'darker') {
                navbarTop.setAttribute('data-navbar-appearance', 'darker');
            }

            var navbarVerticalStyle = window.config.config.phoenixNavbarVerticalStyle;
            var navbarVertical = document.querySelector('.navbar-vertical');
            if (navbarVertical && navbarVerticalStyle === 'darker') {
                navbarVertical.setAttribute('data-navbar-appearance', 'darker');
            }
        </script>
    </main>

    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->

    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->

    <script src="{{ asset('assets/jquery/dist/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('assets/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/list.js/list.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/dayjs/dayjs.min.js') }}"></script>
    <script src="{{ asset('fnx/assets/js/phoenix.js') }}"></script>
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script> --}}
    <script src="{{ asset('assets/vendors/select2/js/select2.full.js') }}"></script>
    <script>
        // showing the offcanvas for the task creation

        $(document).ready(function() {
            console.log('ready');
            // $('.dropify').dropify();

            console.log('before toastr');
            @if (Session::has('message'))
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-center",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "200",
                    "hideDuration": "1000",
                    "timeOut": "2000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
                var type = "{{ Session::get('alert-type', 'info') }}"
                switch (type) {
                    case 'info':
                        toastr.info(" {{ Session::get('message') }} ");
                        break;

                    case 'success':
                        toastr.success(" {{ Session::get('message') }} ");
                        break;

                    case 'warning':
                        toastr.warning(" {{ Session::get('message') }} ");
                        break;

                    case 'error':
                        toastr.error(" {{ Session::get('message') }} ");
                        break;
                }
            @endif
        });
    </script>

    <script src="{{ asset('assets/js/pages/sec/users.js') }}"></script>
</body>

</html>
