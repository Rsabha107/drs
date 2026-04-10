<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- ===============================================-->
        <!--    Document Title-->
        <!-- ===============================================-->
        <title>MDS</title>

        <!-- ===============================================-->
        <!--    Favicons-->
        <!-- ===============================================-->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('fnx/assets/img/favicons/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32"
            href="{{ asset('fnx/assets/img/favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16"
            href="{{ asset('fnx/assets/img/favicons/favicon-16x16.png') }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('fnx/assets/img/favicons/favicon.ico') }}">
        <link rel="manifest" href="{{ asset('fnx/assets/img/favicons/manifest.json') }}">
        <meta name="msapplication-TileImage" content="{{ asset('fnx/assets/img/favicons/mstile-150x150.png') }}">
        <meta name="theme-color" content="#ffffff">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ asset('fnx/vendors/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('fnx/assets/js/config.js') }}"></script>

        <!-- ===============================================-->
        <!--    Stylesheets-->
        <!-- ===============================================-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap"
            rel="stylesheet">
        <link href="{{ asset('fnx/vendors/simplebar/simplebar.min.css" rel="stylesheet') }}">
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
        <link
            href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/rr-1.4.1/sc-2.3.0/sb-1.6.0/datatables.min.css"
            rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.4.1/css/rowGroup.dataTables.min.css">

        <link href="{{ asset('fnx/vendors/choices/choices.min.css') }}" rel="stylesheet">
        <link href="{{ asset('fnx/vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet">

        <link rel='stylesheet'
            href="{{ asset('assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.4/dist/bootstrap-table.min.css" rel="stylesheet">
        <link rel='stylesheet' href="{{ asset('assets/vendors/fontawesome/5.8.1/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/datatables/DataTables-1.13.8/css/jquery.dataTables.min.css') }}">
        <link rel='stylesheet' href="{{ asset('assets/vendors/bootstrap-5.2.3-dist/css/bootstrap.min.css') }}">

        <link href="{{ asset('fnx/assets/css/theme-rtl.min.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
        <link href="{{ asset('fnx/assets/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
        <link href="{{ asset('fnx/assets/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet"
            id="user-style-rtl">
        <link href="{{ asset('fnx/assets/css/user.min.css') }}" type="text/css" rel="stylesheet"
            id="user-style-default">
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

        <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet">
        <script src="{{ asset('assets/jquery/dist/jquery-3.7.0.js') }}"></script>
        <link href="{{ asset('assets/vendors/select2/css/select2.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/vendors/fonts/boxicons.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
        <link href="{{ asset('fnx/vendors/glightbox/glightbox.min.css') }}" rel="stylesheet">
        <link href="{{ asset('fnx/vendors/dropzone/dropzone.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/vendors/dropify/dist/css/demo.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/vendors/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/long_text.css') }}" rel="stylesheet">

        <!-- <link rel="stylesheet" href="{{ asset('assets/css/wizard.css') }}"> -->

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

    </head>

    <body>

        <!-- ===============================================-->
        <!--    Main Content-->
        <!-- ===============================================-->
        <main class="main" id="top">
            <div class="container-fluid bg-body-tertiary dark__bg-gray-1200">
                <div class="bg-holder bg-auth-card-overlay"
                    style="background-image:url(../../../assets/img/bg/37.png);">
                </div>
                <!--/.bg-holder-->

                <div class="row flex-center position-relative min-vh-100 g-0 py-5">
                    <div class="col-11 col-sm-10 col-xl-8">
                        <div class="card border border-translucent auth-card">
                            <div class="card-body pe-md-0">
                                <div class="row align-items-center gx-0 gy-7">
                                    <div
                                        class="col-auto bg-body-highlight dark__bg-gray-1100 rounded-3 position-relative overflow-hidden auth-title-box">
                                        <div class="bg-holder"
                                            style="background-image:url({{ asset('assets/img/bg/38.png') }});">
                                        </div>
                                        <!--/.bg-holder-->

                                        <div
                                            class="position-relative px-4 px-lg-7 pt-7 pb-7 pb-sm-5 text-center text-md-start pb-lg-7 card-sign-up">
                                            <h3 class="mb-3 text-body-emphasis fs-7">Authentication</h3>
                                            <p class="text-body-tertiary">Give yourself some hassle-free delivery
                                                process with the uniqueness of Tracki!</p>
                                            <ul class="list-unstyled mb-0 w-max-content w-md-auto">
                                                <li class="d-flex align-items-center"><span
                                                        class="uil uil-check-circle text-success me-2"></span><span
                                                        class="text-body-tertiary fw-semibold">Fast</span></li>
                                                <li class="d-flex align-items-center"><span
                                                        class="uil uil-check-circle text-success me-2"></span><span
                                                        class="text-body-tertiary fw-semibold">Simple</span></li>
                                                <li class="d-flex align-items-center"><span
                                                        class="uil uil-check-circle text-success me-2"></span><span
                                                        class="text-body-tertiary fw-semibold">Responsive</span></li>
                                            </ul>
                                        </div>
                                        <div
                                            class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-15">
                                            <img class="auth-title-box-img d-dark-none"
                                                src="../../../assets/img/spot-illustrations/auth.png"
                                                alt="" /><img class="auth-title-box-img d-light-none"
                                                src="../../../assets/img/spot-illustrations/auth-dark.png"
                                                alt="" />
                                        </div>
                                    </div>
                                    <div class="col mx-auto">
                                        <div class="auth-form-box">
                                            <div class="text-center mb-5"><a
                                                    class="d-flex flex-center text-decoration-none mb-4"
                                                    href="#">
                                                    {{-- <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block">
                                                    <img src="../../../assets/img/icons/LogoPrintemps_2022_vert.png"
                                                        alt="phoenix" width="58" />
                                                </div> --}}
                                                </a>
                                                <h3 class="text-body-highlight">Sign Up</h3>
                                                <p class="text-body-tertiary">Create your account today</p>
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
                                            <form method="POST" action="{{ route('admin.signup.store') }} "
                                                enctype="multipart/form-data" class="needs-validation validatedForm"
                                                novalidate>
                                                @csrf

                                                <input type="hidden" name="model_name" value="users">
                                                <div class="mb-3 text-start">
                                                    <input type="file" name="file_name" class="dropify" data-height="200" />
                                                </div>

                                                <div class="mb-3 text-start">
                                                    <label class="form-label" for="name">Name</label>
                                                    <input class="form-control" id="name" name="name"
                                                        type="text" placeholder="Name"
                                                        value="{{ old('name') }}" required>
                                                </div>
                                                <div class="mb-3 text-start">
                                                    <label class="form-label" for="email">Email address</label>
                                                    <input class="form-control" id="email" name="email"
                                                        type="email" placeholder="name@example.com"
                                                        value="{{ old('email') }}" required>
                                                </div>
                                                <div class="mb-3 text-start">
                                                    <label class="form-label" for="phone">Phone</label>
                                                    <input class="form-control" id="phone" name="phone"
                                                        type="phone" placeholder="phone number"
                                                        value="{{ old('phone') }}" required>
                                                </div>
                                                <div class="col-12 gy-3 mb-3">
                                                    <label class="form-label" for="inputAddress2">Assigned to
                                                        (multiple)</label>
                                                    <select class="form-select js-select-event-assign-multiple"
                                                        id="add_event_assigned_to" name="event_id[]"
                                                        multiple="multiple" data-with="100%"
                                                        data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                                        @foreach ($events as $event)
                                                            <option value="{{ $event->id }}">{{ $event->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="row g-3 mb-3">
                                                    <div class="col-xl-6">
                                                        <label class="form-label" for="password">Password</label>
                                                        <input class="form-control form-icon-input" name="password"
                                                            id="password" type="password" placeholder="Password"
                                                            required>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <label class="form-label" for="password_confirmation">Confirm
                                                            Password</label>
                                                        <input class="form-control form-icon-input" type="password"
                                                            id="password_confirmation" name="password_confirmation"
                                                            placeholder="Confirm Password" required>
                                                    </div>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" id="termsService" type="checkbox"
                                                        required>
                                                    <label class="form-label fs-9 text-transform-none"
                                                        for="termsService">I
                                                        accept the <a href="#!">terms </a>and <a
                                                            href="#!">privacy
                                                            policy</a></label>
                                                </div>
                                                <button class="btn btn-primary w-100 mb-3" type="submit">Sign
                                                    up</button>
                                                {{-- <div class="text-center"><a class="fs-9 fw-bold"
                                                        href="{{ route('mds') }}">Go back to dashboard</a></div> --}}
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        <script src="{{ asset('assets/jquery/dist/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/moment/min/moment.min.js') }}"></script>
        <script src="{{ asset('assets/datatables/bundle/js/datatables.min.js') }}"></script>

        <script src="{{ asset('fnx/vendors/popper/popper.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/bootstrap-5.2.3-dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>

        <script src="{{ asset('assets/jquery/dist/tableExport.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/bootstrap-table-master/dist/bootstrap-table.min.js') }}"></script>
        <script
            src="{{ asset('assets/vendors/bootstrap-table-master/dist/extensions/custom-view/bootstrap-table-custom-view.js') }}">
        </script>
        <script
            src="{{ asset('assets/vendors/bootstrap-table-master/dist/extensions/export/bootstrap-table-export.min.js') }}">
        </script>
        <script src="{{ asset('assets/datatables/pdfmake-0.2.7/pdfmake.min.js') }}"></script>
        <script src="{{ asset('assets/datatables/pdfmake-0.2.7/vfs_fonts.js') }}"></script>

        <script src="{{ asset('assets/vendors/choices/choices.min.js') }}"></script>
        <script src="{{ asset('assets/js/pace.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/flatpickr/flatpickr.min.js') }}"></script>

        <script src="{{ asset('fnx/vendors/anchorjs/anchor.min.js') }}"></script>
        <script src="{{ asset('fnx/vendors/is/is.min.js') }}"></script>
        <script src="{{ asset('fnx/vendors/fontawesome/all.min.js') }}"></script>
        <script src="{{ asset('fnx/vendors/lodash/lodash.min.js') }}"></script>
        <script src="{{ asset('fnx/vendors/list.js/list.min.js') }}"></script>
        <script src="{{ asset('fnx/vendors/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('fnx/vendors/dayjs/dayjs.min.js') }}"></script>
        <!-- <script src="{{ asset('fnx/vendors/bootstrap/bootstrap.min.js') }}"></script> -->

        <script src="{{ asset('fnx/assets/js/phoenix.js') }}"></script>
        <script src="{{ asset('fnx/vendors/echarts/echarts.min.js') }}"></script>

        <!-- <script src="{{ asset('fnx/vendors/dhtmlx-gantt/dhtmlxgantt.js') }}"></script> -->
        <script src="{{ asset('fnx/vendors/glightbox/glightbox.min.js') }}"></script>
        <script src="{{ asset('fnx/vendors/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ asset('fnx/vendors/dropzone/dropzone-min.js') }}"></script>
        <script src="{{ asset('assets/vendors/dropify/src/js/dropify.js') }}"></script>
        <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
        <script src="{{ asset('assets/jquery/dist/jquery.form.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/select2/js/select2.full.js') }}"></script>

        <script src="{{ asset('fnx/vendors/leaflet/leaflet.js') }}"></script>
        <script src="{{ asset('fnx/vendors/leaflet.markercluster/leaflet.markercluster.js') }}"></script>
        <script src="{{ asset('fnx/vendors/leaflet.tilelayer.colorfilter/leaflet-tilelayer-colorfilter.min.js') }}"></script>

        <script src="{{ asset('assets/js/code/sweetalert2.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>
        <script src="{{ asset('fnx/vendors/sortablejs/Sortable.min.js') }}"></script>
        <script src="{{ asset('assets/js/code/code.js') }}"></script>
        <script src="{{ asset('assets/vendors/spin/spin.js') }}"></script>
        <script src="{{ asset('assets/vendors/clipboard/clipboard.min.js') }}"></script>

        {{-- <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" /> --}}

        <script>
            // showing the offcanvas for the task creation

            $(document).ready(function() {
                console.log('ready');
                $('.dropify').dropify();

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
