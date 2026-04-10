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
    <title>{{ config('settings.site_title') }}</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('fnx/assets/img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/icons/sc_logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/icons/sc_logo.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/icons/sc_logo.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/icons/sc_logo.png') }}">
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.26.0/dist/bootstrap-table.min.css" rel="stylesheet">
    <link
        href="{{ asset('assets/vendors/bootstrap-table-master/dist/extensions/filter-control/bootstrap-table-filter-control.min.css') }}"
        rel="stylesheet">

    <link rel='stylesheet' href="{{ asset('assets/vendors/fontawesome/5.8.1/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatables/DataTables-1.13.8/css/jquery.dataTables.min.css') }}">
    <link rel='stylesheet' href="{{ asset('assets/vendors/bootstrap-5.2.3-dist/css/bootstrap.min.css') }}">

    <link href="{{ asset('fnx/assets/css/theme-rtl.min.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('fnx/assets/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
    <link href="{{ asset('fnx/assets/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet" id="user-style-rtl">
    <link href="{{ asset('fnx/assets/css/user.min.css') }}" type="text/css" rel="stylesheet" id="user-style-default">
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

    <!-- <link href="{{ asset('fnx/vendors/dhtmlx-gantt/dhtmlxgantt.css') }}" rel="stylesheet"> -->
    <!-- dhtmlxgantt -->
    {{-- <script src="{{ asset ('assets/vendors/dhtmlx-gantt/dhtmlxgantt.js') }}"></script> --}}
    {{-- <link href="{{ asset ('assets/vendors/dhtmlx-gantt/dhtmlxgantt.css') }}" type="text/css" rel="stylesheet" id="user-style-rtl"> --}}
    {{-- <link href="{{ asset ('assets/vendors/dhtmlx-gantt/controls_styles.css') }}" type="text/css" rel="stylesheet" id="user-style-rtl"> --}}
    <!-- <link href="{{ asset('assets/css/controls_styles.css?v=8.0.6') }}" type="text/css" rel="stylesheet" id="user-style-rtl"> -->
    <!-- end dhtmlxgantt -->

    <link href="{{ asset('fnx/vendors/glightbox/glightbox.min.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" /> --}}
    <link href="{{ asset('assets/vendors/dropify/dist/css/dropify.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/long_text.css') }}" rel="stylesheet">

    <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"
        rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/branding2.css') }}">
    <link href="{{ asset('assets/css/spinner.css') }}" rel="stylesheet">


</head>

<body class="nav-slim">

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">

        {{-- <x-mds.nav.topmenu /> --}}
        <x-drs.admin.sidemenu />
        @include('drs.admin.body.header')

        {{-- // start of content --}}
        <div class="content">
            @yield('main')
            @include('drs.admin.partials.labels')
            @include('drs.admin.body.footer')
        </div>
        {{-- // end of content --}}

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


    {{-- <script src="{{ asset('assets/jquery/dist/jquery-3.7.0.js') }}"></script> --}}
    <script src="{{ asset('assets/jquery/dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/bundle/js/datatables.min.js') }}"></script>

    <script src="{{ asset('fnx/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-5.2.3-dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>

    <script src="{{ asset('assets/jquery/dist/tableExport.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.26.0/dist/bootstrap-table.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.26.0/dist/extensions/filter-control/bootstrap-table-filter-control.min.js">
    </script>
    {{-- <script src="{{ asset ('assets/vendors/bootstrap-table-master/dist/bootstrap-table.min.js') }}"></script> --}}
    {{-- <script src="{{ asset ('assets/vendors/bootstrap-table-master/dist/extensions/custom-view/bootstrap-table-custom-view.js') }}"></script> --}}
    {{-- <script src="{{ asset ('assets/vendors/bootstrap-table-master/dist/extensions/export/bootstrap-table-export.min.js') }}"></script> --}}
    {{-- <script src="{{ asset ('assets/vendors/bootstrap-table-master/dist/extensions/filter-control/bootstrap-table-filter-control.min.js') }}"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.26.0/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script> --}}

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
    <script src="{{ asset('fnx/vendors/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('fnx/vendors/isotope-packery/packery-mode.pkgd.min.js') }}"></script>
    <script src="{{ asset('fnx/vendors/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>

    <script src="{{ asset('fnx/vendors/glightbox/glightbox.min.js') }}"></script>
    {{-- <script src="{{ asset('fnx/vendors/tinymce/tinymce.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('fnx/vendors/dropzone/dropzone-min.js') }}"></script> --}}
    {{-- <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script> --}}
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

    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js">
    </script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js">
    </script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

    {{-- <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" /> --}}


    <script>
        // showing the offcanvas for the task creation


        $(document).ready(function() {


            function iformat(icon) {
                var originalOption = icon.element;
                return $('<span><i class="far fa-calendar"></i> ' + icon.text + '</span>');
            }

            // showing the offcanvas for the task creation
            // console.log('before select2-with-image')
            // $(".js-example-basic-multiple2").select2();

            // $('#statusSelect104').select2({
            //     width: "100%",
            //     templateSelection: iformat,
            //     templateResult: iformat,
            //     allowHtml: true
            // });

            // console.log(optionFormat)

            // console.log('after select2-with-image')
            // console.log('select2 ok')
            // $('.select2-with-image').select2({
            templateResult: iformat
        });
        // $('.js-example-templating').select2();
        // $('.select2-with-image').select2({
        //     placeholder: "Select coin",
        // });


        // });

        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })

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
    </script>
    
    @stack('style')
    @stack('script')
</body>

</html>
