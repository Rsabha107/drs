<div class="modal fade" id="create_sheet_type_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">
                Add Sheet Type
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form novalidate="" class="modal-content form-submit-event needs-validation" id="form_submit_event"
                action="{{ route('drs.setting.sheet.type.store') }}" method="POST">
                @csrf
                <input type="hidden" name="table" value="sheet_type_table">
                <div class="modal-body">

                    <div class="col-md-12 mb-3">
                        <label for="create_sheet_type_code" class="form-label"><?= get_label('code', 'Code') ?> <span
                                class="asterisk">*</span></label>
                        <input type="text" id="create_sheet_type_code" class="form-control" name="code"
                            placeholder="<?= get_label('please_enter_code', 'Please enter code') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="create_sheet_type_name" class="form-label"><?= get_label('title', 'Title') ?> <span
                                class="asterisk">*</span></label>
                        <input type="text" id="create_sheet_type_name" class="form-control" name="title"
                            placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="create_sheet_type_description"
                            class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea id="create_sheet_type_description" class="form-control" name="description"
                            placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>" rows="3"></textarea>

                    </div>
                    {{-- // select list of events and venues to choose from for this sheet type --}}
                    <div class="col-md-12 mb-3">
                        <label for="create_event_id" class="form-label"><?= get_label('event', 'Event') ?> <span
                                class="asterisk">*</span></label>
                        <select id="create_event_id" name="event_id" class="form-select" required>
                            <option value="">Select event</option>
                            @foreach ($events as $e)
                                <option value="{{ $e->id }}">{{ $e->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="create_venue_id" class="form-label"><?= get_label('venue', 'Venue') ?> <span
                                class="asterisk">*</span></label>
                        <select id="create_venue_id" name="venue_id" class="form-select" required>
                            <option value="">Select venue</option>
                            @foreach ($venues as $v)
                                <option value="{{ $v->id }}">{{ $v->short_name }} &mdash; {{ $v->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="create_match_id" class="form-label"><?= get_label('match_number', 'Match #') ?></label>
                        <select id="create_match_id" name="match_id" class="form-select">
                            <option value="">Select match</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="create_cutoff_date_time" class="form-label"><?= get_label('cuff_date_time', 'Cutoff Date Time') ?></label>
                        <input type="datetime-local" id="create_cuff_date_time" class="form-control" name="cuff_date_time" />
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <?= get_label('close', 'Close') ?></label>
                    </button>
                    <button type="submit" class="btn btn-primary js-save-btn"
                        id="submit_btn"><?= get_label('save', 'Save') ?></label></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_sheet_type_modal" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">
                Edit Sheet Type
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form novalidate="" class="modal-content form-submit-event needs-validation" id="edit_form_submit_event"
                action="{{ route('drs.setting.sheet.type.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="edit_sheet_type_id" name="id" value="">
                <input type="hidden" id="edit_sheet_type_table" name="table" value='sheet_type_table'>
                <div class="modal-body">

                    <div class="col-md-12 mb-3">
                        <label for="edit_sheet_type_code" class="form-label"><?= get_label('code', 'Code') ?> <span
                                class="asterisk">*</span></label>
                        <input type="text" id="edit_sheet_type_code" class="form-control" name="code"
                            placeholder="<?= get_label('please_enter_code', 'Please enter code') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="edit_sheet_type_name" class="form-label"><?= get_label('title', 'Title') ?> <span
                                class="asterisk">*</span></label>
                        <input type="text" id="edit_sheet_type_name" class="form-control" name="title"
                            placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="edit_sheet_type_description"
                            class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea id="edit_sheet_type_description" class="form-control" name="description"
                            placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>" rows="3"></textarea>

                    </div>
                    {{-- // select list of events and venues to choose from for this sheet type --}}
                    <div class="col-md-12 mb-3">
                        <label for="edit_event_id" class="form-label"><?= get_label('event', 'Event') ?> <span
                                class="asterisk">*</span></label>
                        <select id="edit_event_id" name="event_id" class="form-select" required>
                            <option value="">Select event</option>
                            @foreach ($events as $e)
                                <option value="{{ $e->id }}">{{ $e->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="edit_venue_id" class="form-label"><?= get_label('venue', 'Venue') ?> <span
                                class="asterisk">*</span></label>
                        <select id="edit_venue_id" name="venue_id" class="form-select" required>
                            <option value="">Select venue</option>
                            @foreach ($venues as $v)
                                <option value="{{ $v->id }}">{{ $v->short_name }} &mdash; {{ $v->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="edit_match_id" class="form-label"><?= get_label('match_number', 'Match #') ?></label>
                        <select id="edit_match_id" name="match_id" class="form-select">
                            <option value="">Select match</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="edit_cuff_date_time" class="form-label"><?= get_label('cuff_date_time', 'Cuff Date Time') ?></label>
                        <input type="datetime-local" id="edit_cuff_date_time" class="form-control" name="cuff_date_time" />
                    </div>


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
