<div class="modal fade" id="create_venues_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">
                <h3 class="mb-0" id="staticBackdropLabel"><?= get_label('add_venues', 'Add Venue') ?></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form novalidate="" class="modal-content form-submit-event needs-validation" id="form_submit_event" action="{{route('drs.setting.venue.store')}}" method="POST">
                @csrf
                <input type="hidden" name="table" value="venues_table">
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('short_name', 'Short Name') ?> <span class="asterisk">*</span></label>
                        <input required type="text" id="nameBasic" class="form-control" name="short_name" placeholder="<?= get_label('please_enter_short_name', 'Please enter short name') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input required type="text" id="nameBasic" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <?= get_label('close', 'Close') ?></label>
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save','Save') ?></label></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_venues_modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">
                <h3 class="mb-0" id="staticBackdropLabel">Edit</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form novalidate="" class="modal-content form-submit-event needs-validation" id="edit_form_submit_event" action="{{route('drs.setting.venue.update')}}" method="POST">
                @csrf
                <input type="hidden" id="edit_venues_id" name="id" value="">
                <input type="hidden" id="edit_venues_table" name="table">
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('short_name', 'Short Name') ?> <span class="asterisk">*</span></label>
                        <input required type="text" id="edit_venues_short_name" class="form-control" name="short_name" placeholder="<?= get_label('please_enter_short_name', 'Please enter short name') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="edit_venues_title" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <?= get_label('close', 'Close') ?></label>
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('save','Save') ?></label></button>
                </div>
            </form>
        </div>
    </div>
</div>