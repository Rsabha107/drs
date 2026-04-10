<div class="offcanvas-body">
    <div class="row">
        <div class="col-sm-12">
            <form class="row g-3 needs-validation form-submit-event" id="{{ $formId }}" novalidate=""
                action="{{ $formAction }}" method="POST">
                @csrf
                <input type="hidden" id="add_table" name="table" value="schedule_table" />
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Schedule Filters</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="card">
                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="py-0 me-2">
                                <input class="form-control datetimepicker mb-3" id="mds_date_range_filter"
                                    type="text" placeholder="d/m/y to d/m/y"
                                    data-options='{"mode":"range","dateFormat":"d/m/y","disableMobile":true}' />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <x-formy.form_select class="col-sm-6 col-md-12 mb-3" floating="1" selectedValue=""
                                name="event_id" elementId="mds_schedule_event_filter" label="Event" required="required"
                                :forLoopCollection="$events" itemIdForeach="id" itemTitleForeach="name" style=""
                                addDynamicButton="0" />
                        </div>
                        <div class="row mb-3">
                            <x-formy.form_select class="col-sm-6 col-md-12 mb-3" floating="1" selectedValue=""
                                name="venue_id" elementId="mds_schedule_venue_filter" label="Venue" required="required"
                                :forLoopCollection="$venues" itemIdForeach="id" itemTitleForeach="title" style=""
                                addDynamicButton="0" />
                        </div>
                        <div class="row mb-3">
                            <x-formy.form_select class="col-sm-6 col-md-12 mb-3" floating="1" selectedValue=""
                                name="rsp_id" elementId="mds_schedule_rsp_filter" label="RSP" required="required"
                                :forLoopCollection="$rsps" itemIdForeach="id" itemTitleForeach="title" style=""
                                addDynamicButton="0" />
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-end align-items-center px-4 pb-4 border-0 pt-3">
                        <div class="col-12 gy-3">
                            <div class="row g-3 justify-content-end">
                                <a href="javascript:void(0)" class="col-auto">
                                    <button type="button" class="btn btn-phoenix-primary px-3 px-sm-5 fs-10 my-0" id="clear_filter_btn"
                                    data-bs-toggle="tooltip" data-bs-placement="right" data-bs-dismiss="offcanvas"> 
                                    <span class="fas fa-arrows-rotate me-2 fs-10"></span>Reset</button>
                                </a>
                                <a href="javascript:void(0)" class="col-auto">
                                    <button type="button" class="btn btn-sm btn-primary px-3"
                                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-dismiss="offcanvas">
                                        Close
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
