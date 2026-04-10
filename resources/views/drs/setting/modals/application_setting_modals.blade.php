<div class="modal fade" id="create_setting_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">Add Setting Key
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form novalidate="" class="modal-content form-submit-event needs-validation" id="form_submit_application_setting" action="{{route('drs.setting.application.store')}}" method="POST">
                @csrf
                <input type="hidden" name="table" value="application_table">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="key" class="form-label">Key <span class="asterisk">*</span></label>
                            <input required type="text" id="key" class="form-control" name="key" placeholder="<?= get_label('please_enter_name', 'Please enter key') ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="value" class="form-label">Value <span class="asterisk">*</span></label>
                            <input required type="text" id="value" class="form-control" name="value" placeholder="<?= get_label('please_enter_name', 'Please enter value') ?>" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <?= get_label('close', 'Close') ?></label>
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></label></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_app_setting_modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">Edit Setting Key
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form novalidate="" class="modal-content form-submit-event needs-validation" id="edit_form_submit_app_setting" action="{{route('drs.setting.application.update')}}" method="POST">
                @csrf
                <input type="hidden" id="edit_app_setting_id" name="id" value="">
                <input type="hidden" id="edit_app_setting_table" name="table" value='app_setting_table'>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="key" class="form-label">key<span class="asterisk">*</span></label>
                            <input type="text" id="edit_key" class="form-control" name="key" placeholder="Please enter key" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="value" class="form-label">Value<span class="asterisk">*</span></label>
                            <input type="text" id="edit_value" class="form-control" name="value" placeholder="Please enter value" />
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <?= get_label('close', 'Close') ?></label>
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></label></button>
                </div>
            </form>
        </div>
    </div>
</div>