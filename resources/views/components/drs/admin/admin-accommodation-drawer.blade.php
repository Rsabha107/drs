<div class="offcanvas-body">
    <div class="row">
        <div class="col-sm-12">
            <form class="row g-3 needs-validation form-submit-event" id="{{ $formId }}" novalidate=""
                action="{{ $formAction }}" method="POST">
                @csrf
                <input type="hidden" id="add_table" name="table" value="flight_table" />
                <input type="hidden" id="guest_id" name="guest_id" value="{{ $guestData->id }}" />
                <div class="card">
                    <div class="card-header d-flex align-items-center border-bottom">
                        <div class="ms-3">
                            <h5 class="mb-0 fs-sm">Add Accommodation</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <x-formy.form_select
                                class="col-sm-6 col-md-12  mb-3"
                                name="status_id"
                                elementId="add_status"
                                floating='1'
                                style=""
                                itemIdForeach="id"
                                selectedValue="Select Flight Status"
                                itemTitleForeach="status_name"
                                label="status"
                                required="required"
                                :forLoopCollection="$flightStatuses"
                                addDynamicButton='0'
                                dynamicModal=null />
                        </div>
                        <div class="row mb-3">
                            <x-formy.form_input
                                class="col-sm-6 col-md-4  mb-3"
                                inputType="text"
                                floating='1'
                                inputValue=""
                                name="flight_number"
                                elementId="add_flight_number"
                                label="Flight Number"
                                inputAttributes=""
                                required="required"
                                disabled="" />

                            <x-formy.form_select
                                class="col-sm-6 col-md-4  mb-3"
                                name="airline_id"
                                elementId="add_airline"
                                floating='1'
                                style=""
                                itemIdForeach="id"
                                selectedValue="Select Airline"
                                itemTitleForeach="name"
                                label="Airline"
                                required="required"
                                :forLoopCollection="$airlines"
                                addDynamicButton='0'
                                dynamicModal=null />

                            <x-formy.form_select
                                class="col-sm-6 col-md-4  mb-3"
                                name="flight_cabin_id"
                                elementId="add_flight_cabin"
                                floating='1'
                                style=""
                                itemIdForeach="id"
                                selectedValue="Select Cabin Type"
                                itemTitleForeach="cabin_name"
                                label="Cabin Type"
                                required="required"
                                :forLoopCollection="$cabins"
                                addDynamicButton='0'
                                dynamicModal=null />
                        </div>
                        <div class="row mb-3">
                            <x-formy.form_select
                                class="col-sm-6 col-md-4  mb-3"
                                name="flight_type_id"
                                elementId="add_flight_type"
                                floating='1'
                                style=""
                                itemIdForeach="id"
                                selectedValue="Select flight type"
                                itemTitleForeach="title"
                                label="Flight Type"
                                required="required"
                                :forLoopCollection="$flightTypes"
                                addDynamicButton='0'
                                dynamicModal=null />


                            <x-formy.form_select
                                class="col-sm-6 col-md-4  mb-3"
                                name="departure_airport_id"
                                elementId="add_departure_airport_id"
                                floating='1'
                                style=""
                                itemIdForeach="id"
                                selectedValue="Select departure airport"
                                itemTitleForeach="airport_name"
                                label="Departure Airport"
                                required="required"
                                :forLoopCollection="$airports"
                                addDynamicButton='0'
                                dynamicModal=null />

                            <x-formy.form_select
                                class="col-sm-6 col-md-4  mb-3"
                                name="arrival_airport_id"
                                elementId="add_arrival_airport_id"
                                floating='1'
                                style=""
                                itemIdForeach="id"
                                selectedValue="Select arrival airport"
                                itemTitleForeach="airport_name"
                                label="Arrival Airport"
                                required="required"
                                :forLoopCollection="$airports"
                                addDynamicButton='0'
                                dynamicModal=null />
                        </div>
                        <div class="row mb-3">
                            <x-formy.form_date_input
                                class="col-sm-6 col-md-4  mb-3"
                                name="departure_time"
                                elementId="add_departure_time"
                                floating='1'
                                inputType="date"
                                dataOptions='{"disableMobile":true,"dateFormat":"d/m/Y"}'
                                label="Departure Date"
                                required="required"
                                inputValue=""
                                disabled="" />

                            <x-formy.form_date_input
                                class="col-sm-6 col-md-4  mb-3"
                                name="arrival_time"
                                elementId="add_arrival_time"
                                floating='1'
                                inputType="date"
                                dataOptions='{"disableMobile":true,"dateFormat":"d/m/Y"}'
                                label="Arrival Date"
                                required="required"
                                inputValue=""
                                disabled="" />

                            <x-formy.form_input
                                class="col-sm-6 col-md-4  mb-3"
                                inputType="number"
                                floating='1'
                                inputValue=""
                                name="duration_minutes"
                                elementId="add_duration_minutes"
                                label="Flight Duration (Minutes)"
                                inputAttributes=""
                                required="required"
                                disabled="" />

                        </div>
                        <div class="text-center mb-3">
                            <x-formy.form_textarea
                                class="col-sm-12 col-md-12  mb-3"
                                inputType="text"
                                floating='1'
                                inputValue=""
                                name="flight_notes"
                                elementId="add_flight_notes"
                                label="Flight Notes"
                                inputAttributes=""
                                required="required"
                                disabled="" />
                        </div>
                        <div class="col-12 gy-3">
                            <div class="row g-3 justify-content-end">
                                <a href="javascript:void(0)" class="col-auto">
                                    <button type="button" class="btn btn-phoenix-danger px-5"
                                        data-bs-toggle="tooltip" data-bs-placement="right"
                                        data-bs-dismiss="offcanvas">
                                        Cancel
                                    </button>
                                </a>
                                <div class="col-auto">
                                    <button class="btn btn-primary px-5 px-sm-15" id="submit_btn">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>