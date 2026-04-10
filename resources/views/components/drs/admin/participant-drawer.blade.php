<div class="offcanvas-body">
    <div class="row">
        <div class="col-sm-12">
            <form class="row g-3 needs-validation form-submit-event" id="{{ $formId }}" novalidate=""
                action="{{ $formAction }}" method="POST">
                @csrf
                <input type="hidden" id="add_table" name="table" value="guest_table" />
                <input type="hidden" id="guardian_id" name="guardian_id" value="guest_table" />
                <div class="card">
                    <div class="card-header d-flex align-items-center border-bottom">
                        <div class="ms-3">
                            <h5 class="mb-0 fs-sm">Add A Participant</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <x-formy.form_select
                                class="col-sm-6 col-md-3  mb-3"
                                name="participant_type_id"
                                style=""
                                itemIdForeach="id"
                                selectedValue="title"
                                itemTitleForeach="title"
                                floating='1'
                                elementId="add_participant_type"
                                label="Participant Type"
                                required="required"
                                :forLoopCollection="$participantTypes"
                                addDynamicButton='0'
                                dynamicModal=null />

                            <x-formy.form_select
                                class="col-sm-6 col-md-3  mb-3"
                                floating='1'
                                name="gender_id"
                                style=""
                                itemIdForeach="id"
                                selectedValue="title"
                                itemTitleForeach="title"
                                elementId="add_gender"
                                label="Gender"
                                required="required"
                                :forLoopCollection="$genders"
                                addDynamicButton='0'
                                dynamicModal=null />

                            <x-formy.form_input
                                class="col-sm-6 col-md-3  mb-3"
                                inputType="text"
                                floating='1'
                                inputValue=""
                                name="participant_qid"
                                elementId="qid"
                                label="Participant QID"
                                inputAttributes=""
                                required="required"
                                disabled='' />
                            <x-formy.form_select
                                class="col-sm-6 col-md-3  mb-3"
                                name="nationality_id"
                                style=""
                                itemIdForeach="id"
                                selectedValue="title"
                                itemTitleForeach="title"
                                floating='1'
                                elementId="add_nationality"
                                label="Nationality"
                                required="required"
                                :forLoopCollection="$nationalities"
                                addDynamicButton='0'
                                dynamicModal=null />

                        </div>
                        <div class="row mb-3">
                            <x-formy.form_input
                                class="col-sm-6 col-md-6  mb-3"
                                inputType="text"
                                floating='1'
                                inputValue=""
                                name="full_name"
                                elementId="add_participant_name"
                                label="Participant Full Name"
                                inputAttributes=""
                                required="required"
                                disabled="" />
                            <x-formy.form_input
                                class="col-sm-6 col-md-6  mb-3"
                                inputType="text"
                                floating='1'
                                inputValue=""
                                name="school_name"
                                elementId="add_school_name"
                                label="School Name"
                                inputAttributes=""
                                required=""
                                disabled="" />
                        </div>
                        <div class="row mb-3">
                            <x-formy.form_input
                                class="col-sm-6 col-md-6  mb-3"
                                inputType="text"
                                floating='1'
                                inputValue=""
                                name="participant_qid"
                                elementId="qid"
                                label="Participant QID"
                                inputAttributes=""
                                required="required"
                                disabled='' />
                            <x-formy.form_date_input
                                class="col-sm-6 col-md-6  mb-3"
                                inputType="text"
                                floating='1'
                                inputValue=""
                                name="date_of_birth"
                                elementId="add_date_of_birth"
                                label="Participant Date of Birth"
                                required="required"
                                disabled="" />
                        </div>
                        <div class="row mb-3">
                            {{-- <div class="text-center mb-3"> --}}

                                <div class="col-sm-12 col-md-12 mb-3 text-start">
                                    <label for="formFile" class="form-label">Upload QID (Front Side)</label>
                                    <input type="file" name="file_name" class="dropify"
                                        data-height="100"
                                        data-default-file="{{ !empty($user->photo) ? url('storage/upload/profile_images/' . $user->photo) : url('upload/default.png') }}" />
                                </div>
                            {{-- </div> --}}
                            
                        </div>
                        <div class="row mb-3">
                            <hr />
                        </div>
                        <div class="row mb-3">
                            <x-formy.form_select
                                class="col-sm-6 col-md-3  mb-3"
                                name="pants_size_id"
                                style=""
                                itemIdForeach="id"
                                selectedValue="title"
                                itemTitleForeach="label"
                                floating='1'
                                elementId="add_pants_size"
                                label="Pants Size"
                                required="required"
                                :forLoopCollection="$pantSizes"
                                addDynamicButton='0'
                                dynamicModal=null />
                            <x-formy.form_select
                                class="col-sm-6 col-md-3  mb-3"
                                name="jersey_size_id"
                                style=""
                                itemIdForeach="id"
                                selectedValue="title"
                                itemTitleForeach="label"
                                floating='1'
                                elementId="add_jersey_size"
                                label="Jersey Size"
                                required="required"
                                :forLoopCollection="$jerseySizes"
                                addDynamicButton='0'
                                dynamicModal=null />
                            <x-formy.form_select
                                class="col-sm-6 col-md-3  mb-3"
                                name="jacket_size_id"
                                style=""
                                itemIdForeach="id"
                                selectedValue="title"
                                itemTitleForeach="label"
                                floating='1'
                                elementId="add_jacket_size"
                                label="Jacket Size"
                                required="required"
                                :forLoopCollection="$jacketSizes"
                                addDynamicButton='0'
                                dynamicModal=null />
                            <x-formy.form_select
                                class="col-sm-6 col-md-3  mb-3"
                                name="shoe_size_id"
                                style=""
                                itemIdForeach="id"
                                selectedValue="title"
                                itemTitleForeach="label"
                                floating='1'
                                elementId="add_shoe_size"
                                label="Shoe Size"
                                required="required"
                                :forLoopCollection="$shoeSizes"
                                addDynamicButton='0'
                                dynamicModal=null />
                        </div>
                        <div class="row mb-3">
                            <hr />
                        </div>


                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                    type="checkbox"
                                    id="has_food_allergy"
                                    name="food_allergy"
                                    value="1">

                                <label class="form-check-label fw-semibold"
                                    for="has_food_allergy">
                                    Any food allergies?
                                </label>
                            </div>
                        </div>

                        <div class="mb-3 d-none" id="food_allergy_details_wrap">
                            <label class="form-label">
                                Please specify food allergies
                            </label>
                            <input type="text"
                                class="form-control"
                                name="food_allergy_details"
                                placeholder="e.g. nuts, dairy, shellfish">
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                    type="checkbox"
                                    id="has_health_issues"
                                    name="health_issues"
                                    value="1">

                                <label class="form-check-label fw-semibold"
                                    for="has_health_issues">
                                    Any health issues?
                                </label>
                            </div>
                        </div>

                        <div class="mb-3 d-none" id="health_issues_details_wrap">
                            <label class="form-label">
                                Please specify health issues
                            </label>
                            <input type="text"
                                class="form-control"
                                name="food_allergy_details"
                                placeholder="e.g. nuts, dairy, shellfish">
                        </div>
                        {{-- <div class="row mb-3">
                           <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Does your child have any allergies?
                                </label>

                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="has_food_allergy"
                                            id="allergy_no"
                                            value="0"
                                            checked>
                                        <label class="form-check-label" for="allergy_no">
                                            No
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="has_food_allergy"
                                            id="allergy_yes"
                                            value="1">
                                        <label class="form-check-label" for="allergy_yes">
                                            Yes
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 d-none" id="allergy_details_wrap">
                                <label class="form-label">
                                    Please specify food allergies
                                </label>
                                <input type="text"
                                    class="form-control"
                                    name="food_allergy_details"
                                    placeholder="e.g. nuts, dairy, shellfish">
                            </div>
                        </div> --}}

                        {{-- <div class="row mb-3">
                           <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Does your child have any health issues?
                                </label>

                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="has_health_issues"
                                            id="health_issues_no"
                                            value="0"
                                            checked>
                                        <label class="form-check-label" for="health_issues_no">
                                            No
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="has_health_issues"
                                            id="health_issues_yes"
                                            value="1">
                                        <label class="form-check-label" for="health_issues_yes">
                                            Yes
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 d-none" id="health_issues_details_wrap">
                                <label class="form-label">
                                    Please specify health issues
                                </label>
                                <input type="text"
                                    class="form-control"
                                    name="health_issues_details"
                                    placeholder="e.g. asthma, diabetes, epilepsy">
                            </div>
                        </div> --}}
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