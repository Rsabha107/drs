<!-- meetings -->
<style>
    th[colspan] {
        border-bottom: 2px solid #dee2e6;
    }

    th.bg-light {
        background-color: #e7e997 !important;
    }

    th.bg-light-green {
        background-color: #d4edda !important;
    }
</style>
<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            {{ $slot }}
            <div class="mx-2 mb-2">
                <table id="participant_table" data-toggle="table"
                    data-classes="table table-hover  fs-9 mb-0 border-top border-translucent"
                    data-loading-template="loadingTemplate" data-url="{{ route('drs.customer.guardian.list') }}"
                    data-icons-prefix="bx" data-icons="icons" data-show-export="true"
                    data-export-types="['csv', 'txt', 'doc', 'excel', 'xlsx', 'pdf']"
                    data-show-columns-toggle-all="true" data-show-refresh="true" data-show-toggle="true"
                    data-total-field="total" data-trim-on-search="false" data-data-field="rows"
                    data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-searchable="true"
                    data-strict-search="true" data-side-pagination="server" data-show-columns="true"
                    data-pagination="true" data-filter-control="true" data-filter-control-visible="true"
                    data-show-search-clear-button="true" data-sort-name="id" data-sort-order="desc"
                    data-mobile-responsive="true" data-buttons-class="secondary" data-query-params="guestQueryParams">

                    <thead>
                        {{-- <tr>
                            <th rowspan="2"></th>

                            <th colspan="2" class="text-center bg-light fw-semibold">

                            </th>

                            <th colspan="3" class="text-center bg-light-green fw-semibold">
                                Guardian Details
                            </th>

                            <th colspan="10" class="text-center bg-light fw-semibold">
                                Participant Details
                            </th> --}}

                        {{-- <th rowspan="2" class="text-end">
                                Actions
                            </th> --}}
                        {{-- </tr> --}}
                        {{-- <tr> --}}
                        <th data-field="image"></th>
                        <th data-field="participant_status">Participant Status</th>
                        <th data-field="full_name">Participant Name</th>
                        <th data-field="participant_type">Participant Type</th>
                        <th data-field="qid">QID</th>
                        <th data-field="date_of_birth">Date of Birth</th>
                        <th data-field="gender">Gender</th>
                        <th data-field="nationality">Nationality</th>
                        <th data-field="pants_size">Pants Size</th>
                        <th data-field="jersey_size">Jersey Size</th>
                        <th data-field="jacket_size">Jacket Size</th>
                        <th data-field="shoe_size">Shoes Size(EUR)</th>
                        <th data-field="food_allergies">Food Allergies</th>
                        <th data-field="health_issues">Medical Conditions</th>
                        <th data-field="created_at" data-visible="false">Created At</th>
                        <th data-field="updated_at" data-visible="false">Updated At</th>

                        <th data-field="action" class="text-end">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    ("use strict");

    function guestQueryParams(p) {
        return {
            page: p.offset / p.limit + 1,
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search,
            filter: p.filter ? p.filter : '',
        };
    }

    window.icons = {
        refresh: "bx-refresh",
        toggleOn: "bx-toggle-right",
        toggleOff: "bx-toggle-left",
        fullscreen: "bx-fullscreen",
        columns: "bx-list-ul",
        export_data: "bx-list-ul",
        clearSearch: "bx-x-circle",
    };

    $('#participant_table').on('post-header.bs.table', function() {
        $('#participant_table').bootstrapTable('initFilterControls');
    });

    function loadingTemplate(message) {
        return '<i class="bx bx-loader-circle bx-spin bx-flip-vertical" ></i>';
    }

    $("#mds_schedule_event_filter,#mds_schedule_venue_filter,#mds_schedule_rsp_filter").on("change", function(e) {
        e.preventDefault();
        console.log("tasks.js on change");
        $("#participant_table").bootstrapTable("refresh");
    });
</script>
