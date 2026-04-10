{{-- Shared fields partial for create & edit modals.
     Requires: $prefix ('create' or 'edit'), $event, $matches
--}}
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Venue <span class="text-danger">*</span></label>
        <select name="venue_id" class="form-select" required>
            <option value="">Select venue</option>
            @foreach($event->venues as $v)
                <option value="{{ $v->id }}">{{ $v->short_name }} &mdash; {{ $v->title }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">Please select a venue.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Sheet Type <span class="text-danger">*</span></label>
        <select name="sheet_type" class="form-select" required>
            <option value="">Select type</option>
            @foreach(['MD-3','MD-2','MD-1','MD','MD FINAL','MD+1'] as $t)
                <option value="{{ $t }}">{{ $t }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">Please select a sheet type.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Date <span class="text-danger">*</span></label>
        <input type="date" name="run_date" class="form-control" required>
        <div class="invalid-feedback">Please enter a date.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Match <span class="text-muted small">(optional)</span></label>
        <select name="match_id" class="form-select">
            <option value="">N/A</option>
            @foreach($matches as $m)
                <option value="{{ $m->id }}">M{{ $m->match_number }} &mdash; {{ $m->match_date }}</option>
            @endforeach
        </select>
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
