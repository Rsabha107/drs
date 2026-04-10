<div class="modal fade" id="create_event_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">
                Add Event
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form novalidate="" class="modal-content form-submit-event needs-validation" id="form_submit_event"
                action="{{ route('drs.setting.event.store') }}" method="POST">
                @csrf
                <input type="hidden" name="table" value="event_table">
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('name', 'name') ?> <span
                                class="asterisk">*</span></label>
                        <input required type="text" id="nameBasic" class="form-control" name="name"
                            placeholder="<?= get_label('please_enter_name', 'Please enter name') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <x-formy.select_multiple class="col-md-12 mb-3" name="venue_id[]" elementId="venue_id"
                            label="Venue assignment (multiple)" :forLoopCollection="$venues" itemIdForeach="id"
                            itemTitleForeach="title" required="" style="width: 100%" edit="0" />
                    </div>
                    <input type="file" id="qid_upload_create" name="qid_files" multiple />
                    <input type="hidden" name="qid_server_ids" id="qid_server_ids_create" value="[]">
                    <input type="hidden" name="delete_doc_ids" id="delete_doc_ids_create" value="[]">
                    {{-- <div class="text-center mb-3">
                        <div class="mb-3 text-start">
                            <input type="file" name="file_name" class="dropify" data-height="200"
                                data-default-file="{{ !empty($user->photo) ? url('storage/upload/profile_images/' . $user->photo) : url('storage/storage/upload/avatar-placeholder.webp') }}" />
                        </div>
                    </div> --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <?= get_label('close', 'Close') ?></label>
                        </button>
                        <button type="submit" class="btn btn-primary js-save-btn"
                            id="submit_btn"><?= get_label('save', 'Save') ?></label></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_event_modal" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">
                Edit Event
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form novalidate="" class="modal-content form-submit-event needs-validation" id="edit_form_submit_event"
                action="{{ route('drs.setting.event.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="edit_event_id" name="id" value="">
                <input type="hidden" id="edit_event_table" name="table" value='event_table'>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="nameBasic" class="form-label"><?= get_label('name', 'name') ?> <span
                                    class="asterisk">*</span></label>
                            <input type="text" id="edit_event_name" class="form-control" name="name"
                                placeholder="<?= get_label('please_enter_name', 'Please enter name') ?>" />
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <x-formy.select_multiple class="col-md-12 mb-3" name="venue_id[]" elementId="edit_venue_id"
                            label="Venue assignment (multiple)" :forLoopCollection="$venues" itemIdForeach="id"
                            itemTitleForeach="title" required="" style="width: 100%" edit="0" />
                    </div>
                    <div class="mb-4">
                        <label class="text-1000 fw-bold mb-2">Status</label>
                        <select class="form-select" name="active_flag" id="editActiveFlag" required>
                            <option value="">Select</option>
                            <option value="1" selected>Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </div>
                    <input type="file" id="qid_upload_edit" name="qid_files" multiple />
                    <input type="hidden" name="qid_server_ids" id="qid_server_ids_edit" value="[]">
                    <input type="hidden" name="delete_doc_ids" id="delete_doc_ids_edit" value="[]">

                </div>
                <div class="modal-footer">
                    <button type="button" id="edit_image" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        <?= get_label('close', 'Close') ?></label>
                    </button>
                    <button type="submit" class="btn btn-primary js-save-btn"
                        id="submit_btn"><?= get_label('save', 'Save') ?></label></button>
                </div>
            </form>
        </div>
    </div>
</div>
