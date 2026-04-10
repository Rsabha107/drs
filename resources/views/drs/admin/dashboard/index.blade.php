@extends('drs.layout.admin_template')
{{-- @extends('mds.admin.layout.admin_dashboard_template') --}}
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
                        <?= get_label('wdr', 'Workforce Daily Dashboard') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            {{-- <button class="btn px-3 btn-phoenix-secondary" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#wdrFilterOffcanvas" aria-haspopup="true" aria-expanded="false"
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
            </button> --}}
        </div>
    </div>
    <div card class="card mb-5 p-5">
        <div class="mb-9">
            <div class="px-3 mb-5">
                <div class="row justify-content-between">
                    <div
                        class="col-6 col-md-4 col-xxl-2 text-center border-translucent border-start-xxl border-end-xxl-0 border-bottom-xxl-0 border-end border-bottom pb-4 pb-xxl-0 ">
                        <span class="fa-solid fa-clipboard-list text-primary fa-xl"></span>
                        <h1 class="fs-5 pt-3">{{ $totalReports }}</h1>
                        <p class="fs-9 mb-0">Total Reports</p>
                    </div>
                    <div
                        class="col-6 col-md-4 col-xxl-2 text-center border-translucent border-start-xxl border-end-xxl-0 border-bottom-xxl-0 border-end-md border-bottom pb-4 pb-xxl-0">
                        <span class="fa-regular fa-calendar-plus text-info fa-xl"></span>
                        <h1 class="fs-5 pt-3">{{ $todayReports }}</h1>
                        <p class="fs-9 mb-0">Today's Report</p>
                    </div>
                    <div
                        class="col-6 col-md-4 col-xxl-2 text-center border-translucent border-start-xxl border-bottom-xxl-0 border-bottom border-end border-end-md-0 pb-4 pb-xxl-0 pt-4 pt-md-0">
                        <span class="fa-solid fa-people-group text-primary fa-xl"></span>
                        <h1 class="fs-5 pt-3">
                           {{ $avgAttendance }}</h1>
                        <p class="fs-9 mb-0">Avg Attendance %</p>
                    </div>
                    <div
                        class="col-6 col-md-4 col-xxl-2 text-center border-translucent border-start-xxl border-end-md border-end-xxl-0 border-bottom border-bottom-md-0 pb-4 pb-xxl-0 pt-4 pt-xxl-0">
                        <span class="fa-solid fa-utensils text-primary fa-xl"></span>
                        <h1 class="fs-5 pt-3">
                            {{ $avgMealConsumption }}</h1>
                        <p class="fs-9 mb-0">Avg Meal Consumption %</p>
                    </div>
                    <div
                        class="col-6 col-md-4 col-xxl-2 text-center border-translucent border-start-xxl border-end border-end-xxl-0 pb-md-4 pb-xxl-0 pt-4 pt-xxl-0">
                        <span class="fa-solid fa-user-check text-success fa-xl"></span>
                        <h1 class="fs-5 pt-3">
                            0</h1>
                        <p class="fs-9 mb-0">(placeholder)</p>
                    </div>
                    <div
                        class="col-6 col-md-4 col-xxl-2 text-center border-translucent border-start-xxl border-end-xxl pb-md-4 pb-xxl-0 pt-4 pt-xxl-0">
                        <span class="uil fs-5 lh-1 uil-envelope-block text-danger"></span>
                        <h1 class="fs-5 pt-3">
                           0</h1>
                        <p class="fs-9 mb-0">(placeholder)</p>
                    </div>
                </div>
            </div>
            {{-- <div class="mx-n4 px-4  px-lg-6 bg-body-emphasis py-5">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <h3 class="text-body-emphasis text-nowrap">Item Statuses</h3>
                        <p class="text-body-tertiary mb-md-7">Newly found and yet to be solved</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0 fw-bold">Status </p>
                            <p class="mb-0 fs-9">Total count <span class="fw-bold">{{ $stored_items->count() }}</span></p>
                        </div>
                        <hr class="bg-body-secondary mb-2 mt-2">
                        <div class="d-flex align-items-center mb-1">
                            <span class="d-inline-block bullet-item me-2" data-status="registered"></span>
                            <p class="mb-0 fw-semibold text-body lh-sm flex-1">Registered</p>
                            <h5 class="mb-0 text-body"> {{ $stored_items->where('item_status_id', getStatusIdByLabel('registered'))->count() }}</h5>
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <span class="d-inline-block bullet-item me-2" data-status="collected"></span>
                            <p class="mb-0 fw-semibold text-body lh-sm flex-1">Collected</p>
                            <h5 class="mb-0 text-body"> {{ $stored_items->where('item_status_id', getStatusIdByLabel('collected'))->count() }}</h5>
                        </div>
                        <div class="d-flex align-items-center mb-1"><span
                                class="d-inline-block bullet-item me-2" data-status="stored"></span>
                            <p class="mb-0 fw-semibold text-body lh-sm flex-1">Stored</p>
                            <h5 class="mb-0 text-body"> {{ $stored_items->where('item_status_id', getStatusIdByLabel('stored'))->count() }}</h5>
                        </div>
                        <div class="d-flex align-items-center mb-1"><span
                                class="d-inline-block bullet-item me-2" data-status="rejected"></span>
                            <p class="mb-0 fw-semibold text-body lh-sm flex-1">Rejected</p>
                            <h5 class="mb-0 text-body"> {{ $stored_items->where('item_status_id', getStatusIdByLabel('rejected'))->count() }}</h5>
                        </div>
                        <div class="d-flex align-items-center"><span
                                class="d-inline-block bullet-item me-2" data-status="disposed"></span>
                            <p class="mb-0 fw-semibold text-body lh-sm flex-1">Disposed</p>
                            <h5 class="mb-0 text-body"> {{ $stored_items->where('item_status_id', getStatusIdByLabel('disposed'))->count() }}</h5>
                        </div>
                        <div class="d-flex align-items-center"><span
                                class="d-inline-block bullet-item me-2" data-status="transferred"></span>
                            <p class="mb-0 fw-semibold text-body lh-sm flex-1">Transferred</p>
                            <h5 class="mb-0 text-body"> {{ $stored_items->where('item_status_id', getStatusIdByLabel('transferred'))->count() }}</h5>
                        </div>
                        <button class="btn btn-outline-primary mt-5">See Details<svg
                                class="svg-inline--fa fa-angle-right ms-2 fs-10 text-center" aria-hidden="true"
                                focusable="false" data-prefix="fas" data-icon="angle-right" role="img"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z">
                                </path>
                            </svg><!-- <span class="fas fa-angle-right ms-2 fs-10 text-center"></span> Font Awesome fontawesome.com --></button>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="position-relative mb-sm-4 mb-xl-0">
                            <div class="echart-issue-chart"
                                style="min-height: 390px; width: 100%; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); position: relative;"
                                _echarts_instance_="ec_1768839036982">
                                <div
                                    style="position: relative; width: 376px; height: 390px; padding: 0px; margin: 0px; border-width: 0px; cursor: default;">
                                    <canvas data-zr-dom-id="zr_0" width="376" height="390"
                                        style="position: absolute; left: 0px; top: 0px; width: 376px; height: 390px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas>
                                </div>
                                <div class=""
                                    style="position: absolute; display: block; border-style: solid; white-space: nowrap; box-shadow: rgba(0, 0, 0, 0.2) 1px 2px 10px; transition: opacity 0.2s cubic-bezier(0.23, 1, 0.32, 1), visibility 0.2s cubic-bezier(0.23, 1, 0.32, 1), transform 0.4s cubic-bezier(0.23, 1, 0.32, 1); background-color: rgb(255, 255, 255); border-width: 1px; border-radius: 4px; color: rgb(102, 102, 102); font: 14px / 21px &quot;Microsoft YaHei&quot;; padding: 10px; top: 0px; left: 0px; transform: translate3d(86px, 262px, 0px); border-color: rgb(255, 204, 133); z-index: 1000; pointer-events: none; visibility: hidden; opacity: 0;">
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="mx-lg-n4">
                <div class="row g-3 pt-3">
                    <div class="col-xl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="mb-3">Title here</h3>
                                {{-- <p class="text-body-tertiary mb-0">Stages of deals &amp; conversion</p> --}}
                                <div class="echart-today-checked-in-cargo" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="mb-3">Title here</h3>
                                {{-- <p class="text-body-tertiary">Country-wise target fulfilment</p> --}}
                                <div class="echart-tomorrow-scheduled-cargo" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mx-lg-n4">
                <div class="row g-3 pt-3">
                    <div class="col-xl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="mb-3">Title here</h3>
                                {{-- <p class="text-body-tertiary mb-0">Stages of deals &amp; conversion</p> --}}
                                <div class="echart-today-checked-in-venue" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="mb-3">Title here</h3>
                                {{-- <p class="text-body-tertiary">Country-wise target fulfilment</p> --}}
                                <div class="echart-tomorrow-scheduled-venue" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mx-lg-n4">
                <div class="row g-3 pt-3">
                    <div class="col-xl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="mb-3">Title here</h3>
                                {{-- <p class="text-body-tertiary mb-0">Stages of deals &amp; conversion</p> --}}
                                <div class="echart-today-checked-in-rsp" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="mb-3">Title here</h3>
                                {{-- <p class="text-body-tertiary">Country-wise target fulfilment</p> --}}
                                <div class="echart-tomorrow-scheduled-rsp" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="offcanvas offcanvas-end offcanvas-filter-modal in" id="bookingFilterOffcanvas" tabindex="-1"
        aria-labelledby="offcanvasWithBackdropLabel">
        <x-sps.admin.admin-dashboard-filter-drawer id="" formAction="" formId="filter_booking_form"
            :events="$events" :venues="$venues" :locations="$locations" :storageTypes="$storage_types" :statuses="$item_statuses" />
    </div> --}}
@endsection


@push('script')
    {{-- @include('sps.partials.charts-js') --}}
    <script src="{{ asset('fnx/vendors/echarts/echarts.min.js') }}"></script>
    {{-- <script src="{{asset('fnx/assets/js/crm-dashboard.js')}}"></script> --}}
@endpush
