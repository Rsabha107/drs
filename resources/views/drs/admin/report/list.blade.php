@extends('drs.layout.admin_template')
@section('main')
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <div class="d-flex justify-content-between m-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('daily_run_sheets', 'Daily Run Sheets') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            @if (getEventLabelById(session()->get('EVENT_ID')) != 'Admin')
            <x-formy.button url="{{ route('drs.report.create') }}" title="New Report" icon="fa-solid fa-plus"
                class="btn btn-subtle-primary px-3 px-sm-5 me-2" />
            @endif
            <button class="btn px-3 btn-phoenix-secondary" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#wdrfilterOffcanvas" aria-haspopup="true" aria-expanded="false"
                data-bs-reference="parent"><svg class="svg-inline--fa fa-filter text-primary" data-fa-transform="down-3"
                    aria-hidden="true" focusable="false" data-prefix="fas" data-icon="filter" role="img"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""
                    style="transform-origin: 0.5em 0.6875em;">
                    <g transform="translate(256 256)">
                        <g transform="translate(0, 96)  scale(1, 1)  rotate(0 0 0)">
                            <path fill="currentColor"
                                d="M3.9 54.9C10.5 40.9 24.5 32 40 32H472c15.5 0 29.5 8.9 36.1 22.9s4.6 30.5-5.2 42.5L320 320.9V448c0 12.1-6.8 23.2-17.7 28.6s-23.8 4.3-33.5-3l-64-48c-8.1-6-12.8-15.5-12.8-25.6V320.9L9 97.3C-.7 85.4-2.8 68.8 3.9 54.9z"
                                transform="translate(-256 -256)"></path>
                        </g>
                    </g>
                </svg><!-- <span class="fa-solid fa-filter text-primary" data-fa-transform="down-3"></span> Font Awesome fontawesome.com -->
            </button>
            <button class="btn px-3 btn-phoenix-secondary bg-body-emphasis bg-body-hover action-btn" type="button"
                data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false"
                data-bs-reference="parent"><svg class="svg-inline--fa fa-ellipsis" data-fa-transform="shrink-2"
                    aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis" role="img"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""
                    style="transform-origin: 0.4375em 0.5em;">
                    <g transform="translate(224 256)">
                        <g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)">
                            <path fill="currentColor"
                                d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z"
                                transform="translate(-224 -256)"></path>
                        </g>
                    </g>
                </svg><!-- <span class="fas fa-ellipsis-h" data-fa-transform="shrink-2"></span> Font Awesome fontawesome.com -->
            </button>
            <ul class="dropdown-menu dropdown-menu-end" style="">
                {{-- <li><a class="dropdown-item ms-2 text-warning" href="{{ route('mds.setting.schedule.import') }}">
                    <span class="fa-solid fa-upload text-warning me-2"></span>Import</a></li> --}}
                <li>
                    <form method="POST" action="{{ route('drs.admin.report.export') }}" id="filter_export_form">
                        @csrf
                        {{-- <input type="hidden" id="export_client_group_filter" name="export_client_group_filter"
                            value="">
                        <input type="hidden" id="export_status_filter" name="export_status_filter"
                            value=""> --}}
                        <input type="hidden" id="export_event_filter" name="export_event_filter" value="">
                        <input type="hidden" id="export_venue_filter" name="export_venue_filter" value="">
                        {{-- <input type="hidden" id="export_rsp_filter" name="export_rsp_filter" value=""> --}}
                        <input type="hidden" id="export_date_range_filter" name="export_date_range_filter" value="">
                        {{-- <button type="submit">export</button> --}}
                        <button type="submit" class="btn btn-link p-2 m-2 align-baseline">
                            Export Filtered Results
                        </button>
                        {{-- <a class="dropdown-item ms-2 text-success me-2"
                            href="{{ route('drs.admin.participant.test.email') }}">
                            <span class="fa-solid fa-download text-success me-2"></span>Dynamic Email Test
                        </a> --}}
                    </form>
                    {{-- <a class="dropdown-item ms-2 text-success me-2" href="{{ route('drs.admin.booking.export') }}">
                    <span class="fa-solid fa-download text-success me-2"></span>Export
                </a> --}}
                </li>
            </ul>
        </div>
    </div>
    <x-drs.admin.report-card />

    @include('drs.admin.report.modals.drs_modal')

    <script src="{{ asset('assets/js/pages/drs/admin/report.js') }}"></script>
    
@endsection

@push('style')
        <link rel="stylesheet" href="{{ asset('assets/css/custom/datatable_custom.css') }}">
@endpush

@push('script')

@endpush
