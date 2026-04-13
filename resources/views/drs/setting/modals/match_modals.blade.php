<div class="modal fade" id="create_matches_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">
                <h3 class="mb-0 text-white" id="staticBackdropLabel"><?= get_label('add_matches', 'Add Match') ?></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form novalidate="" class="modal-content form-submit-event needs-validation" id="form_submit_event"
                action="{{ route('drs.setting.match.store') }}" method="POST">
                @csrf
                <input type="hidden" name="table" value="matches_table">
                <input type="hidden" name="event_id" value="{{ session()->get('EVENT_ID') }}">
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('venue', 'Venue') ?> <span
                                class="asterisk">*</span></label>
                        <input disabled type="text" id="nameBasic" class="form-control" value="{{ $event->name }}"
                            placeholder="<?= get_label('please_enter_venue', 'Please enter venue') ?>" />
                    </div>
                    <div class="col-12 gy-3 mb-3">
                        <label class="form-label" for="inputAddress2">Venue<span class="asterisk">*</span></label>
                        <select class="form-select" id="add_venue_assigned_to" name="venue_id" data-with="100%"
                            data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                            <!-- <select name="assignment_to_id[]" class="form-select" data-choices="data-choices" size="1" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}' id="floatingSelectRating" required> -->
                            @foreach ($venues as $venue)
                                <option value="{{ $venue->id }}">
                                    {{ $venue->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('match_number', 'Match Number') ?> <span
                                class="asterisk">*</span></label>
                        <input required type="text" id="match_number" class="form-control" name="match_number"
                            placeholder="<?= get_label('please_enter_match_number', 'Please enter match number') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('pma1', 'PMA1') ?> <span
                                class="asterisk">*</span></label>
                        <input required type="text" id="nameBasic" class="form-control" name="pma1"
                            placeholder="<?= get_label('please_enter_pma1', 'Please enter PMA1') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('pma2', 'PMA2') ?> <span
                                class="asterisk">*</span></label>
                        <input required type="text" id="nameBasic" class="form-control" name="pma2"
                            placeholder="<?= get_label('please_enter_pma2', 'Please enter PMA2') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('stage', 'Stage') ?> <span
                                class="asterisk">*</span></label>
                        <input required type="text" id="nameBasic" class="form-control" name="stage"
                            placeholder="<?= get_label('please_enter_stage', 'Please enter stage') ?>" />
                    </div>
                    {{-- date with d/m/yyyy format --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="datepicker">Match Date<span class="asterisk">*</span></label>
                        <input class="form-control datetimepicker flatpickr-input" id="datepicker" name="match_date"
                            type="text" placeholder="dd/mm/yyyy"
                            data-options='{"disableMobile":true,"dateFormat":"d/m/Y"}' readonly="readonly">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Gates Opening</label>
                        <input type="time" name="gates_opening" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Kick-Off</label>
                        <input type="time" name="kick_off" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <?= get_label('close', 'Close') ?></label>
                    </button>
                    <button type="submit" class="btn btn-primary"
                        id="submit_btn"><?= get_label('save', 'Save') ?></label></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_matches_modal" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">
                <h3 class="mb-0 text-white" id="staticBackdropLabel">Edit</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form novalidate="" class="modal-content form-submit-event needs-validation" id="edit_form_submit_event"
                action="{{ route('drs.setting.match.update') }}" method="POST">
                @csrf
                <input type="hidden" id="edit_matches_id" name="id" value="">
                <input type="hidden" id="edit_matches_table" name="table">
                <input type="hidden" id="edit_matches_event_id" name="event_id"
                    value="{{ session()->get('EVENT_ID') }}">

                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <label for="edit_matches_event_name" class="form-label"><?= get_label('event', 'Event') ?>
                            <span class="asterisk">*</span></label>
                        <input disabled type="text" id="edit_matches_event_name" class="form-control"
                            value="{{ $event->name }}"
                            placeholder="<?= get_label('please_enter_event', 'Please enter event') ?>" />
                    </div>
                    <div class="col-12 gy-3 mb-3">
                        <label class="form-label" for="edit_matches_venue_id">Venue<span
                                class="asterisk">*</span></label>
                        <select class="form-select" id="edit_matches_venue_id" name="venue_id" data-with="100%"
                            data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                            <!-- <select name="assignment_to_id[]" class="form-select" data-choices="data-choices" size="1" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}' id="floatingSelectRating" required> -->
                            @foreach ($venues as $venue)
                                <option value="{{ $venue->id }}">
                                    {{ $venue->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="edit_match_number"
                            class="form-label"><?= get_label('match_number', 'Match Number') ?> <span
                                class="asterisk">*</span></label>
                        <input required type="text" id="edit_matches_number" class="form-control"
                            name="match_number"
                            placeholder="<?= get_label('please_enter_match_number', 'Please enter match number') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="edit_matches_pma1" class="form-label"><?= get_label('pma1', 'PMA1') ?> <span
                                class="asterisk">*</span></label>
                        <input required type="text" id="edit_matches_pma1" class="form-control" name="pma1"
                            placeholder="<?= get_label('please_enter_pma1', 'Please enter PMA1') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="edit_matches_pma2" class="form-label"><?= get_label('pma2', 'PMA2') ?> <span
                                class="asterisk">*</span></label>
                        <input required type="text" id="edit_matches_pma2" class="form-control" name="pma2"
                            placeholder="<?= get_label('please_enter_pma2', 'Please enter PMA2') ?>" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="edit_matches_stage" class="form-label"><?= get_label('stage', 'Stage') ?> <span
                                class="asterisk">*</span></label>
                        <input required type="text" id="edit_matches_stage" class="form-control" name="stage"
                            placeholder="<?= get_label('please_enter_stage', 'Please enter stage') ?>" />
                    </div>
                    {{-- date with d/m/yyyy format --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="edit_matches_date">Match Date<span
                                class="asterisk">*</span></label>
                        <input class="form-control datetimepicker flatpickr-input" id="edit_matches_date"
                            name="match_date" type="text" placeholder="dd/mm/yyyy"
                            data-options='{"disableMobile":true,"dateFormat":"d/m/Y"}' readonly="readonly">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="edit_gates_opening">Gates Opening</label>
                        <input type="time" id="edit_gates_opening" name="gates_opening" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="edit_kick_off">Kick-Off</label>
                        <input type="time" id="edit_kick_off" name="kick_off" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <?= get_label('close', 'Close') ?></label>
                    </button>
                    <button type="submit" class="btn btn-primary"
                        id="submit_btn"><?= get_label('save', 'Save') ?></label></button>
                </div>
            </form>
        </div>
    </div>
</div>
