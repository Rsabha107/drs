{{-- Shared fields partial for create & edit modals.
     Requires: $prefix ('create' or 'edit'), $event
--}}
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Venue <span class="text-danger">*</span></label>
        <select id="{{ $prefix }}_drs_venue_id" name="venue_id" class="form-select" required>
            <option value="">Select venue</option>
            @foreach ($event->venues as $v)
                <option value="{{ $v->id }}">{{ $v->short_name }} &mdash; {{ $v->title }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">Please select a venue.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Match <span class="text-danger">*</span></label>
        <select id="{{ $prefix }}_drs_match_id" name="match_id" class="form-select" disabled>
            <option value="">— select a venue first —</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Sheet Type <span class="text-danger">*</span></label>
        <select name="sheet_type" class="form-select" required>
            <option value="">Select type</option>
            @foreach (['MD-3', 'MD-2', 'MD-1', 'MD', 'MD FINAL', 'MD+1'] as $t)
                <option value="{{ $t }}">{{ $t }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">Please select a sheet type.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Functional Area <span class="text-muted small">(optional)</span></label>
        <select id="{{ $prefix }}_drs_functional_area_id" name="functional_area_id" class="form-select">
            <option value="">N/A</option>
            @foreach ($userFas ?? [] as $fa)
                <option value="{{ $fa->id }}">{{ $fa->fa_code }} &mdash; {{ $fa->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Date <span class="text-danger">*</span></label>
        <input type="date" id="{{ $prefix }}_drs_run_date" name="run_date" class="form-control" required>
        <div class="invalid-feedback">Please enter a date.</div>
    </div>


    <div class="col-md-6">
        <label class="form-label">Teams</label>
        <input type="text" id="{{ $prefix }}_drs_teams" class="form-control" readonly
            placeholder="— select a match —">
    </div>

    <div class="col-md-6">
        <label class="form-label">Gates Opening</label>
        <input type="time" name="gates_opening" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Kick-Off</label>
        <input type="time" name="kick_off" class="form-control">
    </div>
</div>
