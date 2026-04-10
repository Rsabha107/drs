<div class="modal fade" id="create_functional_areas_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">
                <h3 class="mb-0" id="staticBackdropLabel"><?= get_label('add_functional_area', 'Add Functional Area') ?></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form novalidate="" class="modal-content form-submit-event needs-validation" id="form_submit_event" action="{{route('drs.setting.functional_area.store')}}" method="POST">
                @csrf
                <input type="hidden" name="table" value="functional_areas_table">
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <label class="form-label"><?= get_label('fa_code', 'FA Code') ?> <span class="asterisk">*</span></label>
                        <input required type="text" class="form-control" name="fa_code" placeholder="<?= get_label('please_enter_fa_code', 'Please enter FA code') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input required type="text" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label"><?= get_label('focal_point_name', 'Focal Point Name') ?></label>
                        <input type="text" class="form-control" name="focal_point_name" placeholder="<?= get_label('please_enter_focal_point_name', 'Please enter focal point name') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label"><?= get_label('focal_point_email', 'Focal Point Email') ?></label>
                        <input type="email" class="form-control" name="focal_point_email" placeholder="<?= get_label('please_enter_focal_point_email', 'Please enter focal point email') ?>" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <?= get_label('close', 'Close') ?>
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_functional_areas_modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">
                <h3 class="mb-0" id="staticBackdropLabel">Edit</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form novalidate="" class="modal-content form-submit-event needs-validation" id="edit_form_submit_event" action="{{route('drs.setting.functional_area.update')}}" method="POST">
                @csrf
                <input type="hidden" id="edit_functional_areas_id" name="id" value="">
                <input type="hidden" id="edit_functional_areas_table" name="table">
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <label class="form-label"><?= get_label('fa_code', 'FA Code') ?> <span class="asterisk">*</span></label>
                        <input required type="text" id="edit_functional_areas_fa_code" class="form-control" name="fa_code" placeholder="<?= get_label('please_enter_fa_code', 'Please enter FA code') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input required type="text" id="edit_functional_areas_title" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label"><?= get_label('focal_point_name', 'Focal Point Name') ?></label>
                        <input type="text" id="edit_functional_areas_focal_point_name" class="form-control" name="focal_point_name" placeholder="<?= get_label('please_enter_focal_point_name', 'Please enter focal point name') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label"><?= get_label('focal_point_email', 'Focal Point Email') ?></label>
                        <input type="email" id="edit_functional_areas_focal_point_email" class="form-control" name="focal_point_email" placeholder="<?= get_label('please_enter_focal_point_email', 'Please enter focal point email') ?>" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <?= get_label('close', 'Close') ?>
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save', 'Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
