@extends('drs.layout.admin_template')
@section('main')

<div class="d-flex justify-content-between align-items-center m-2 mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('drs.drs.index') }}">Daily Run Sheets</a></li>
            <li class="breadcrumb-item"><a href="{{ route('drs.drs.show', $sheet->id) }}">{{ $sheet->sheet_type }}</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<div class="card mx-2" style="max-width:700px;">
    <div class="card-header">
        <h5 class="mb-0">Edit Daily Run Sheet</h5>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('drs.drs.update') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $sheet->id }}">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Venue <span class="text-danger">*</span></label>
                    <select name="venue_id" class="form-select" required>
                        @foreach($venues as $v)
                            <option value="{{ $v->id }}" {{ $sheet->venue_id == $v->id ? 'selected' : '' }}>
                                {{ $v->short_name }} &mdash; {{ $v->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sheet Type <span class="text-danger">*</span></label>
                    <select name="sheet_type" class="form-select" required>
                        @foreach(['MD-3','MD-2','MD-1','MD','MD FINAL','MD+1'] as $t)
                            <option value="{{ $t }}" {{ $sheet->sheet_type == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="run_date" class="form-control" value="{{ $sheet->run_date }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Match (optional)</label>
                    <select name="match_id" class="form-select">
                        <option value="">N/A</option>
                        @foreach($matches as $m)
                            <option value="{{ $m->id }}" {{ $sheet->match_id == $m->id ? 'selected' : '' }}>
                                M{{ $m->match_number }} &mdash; {{ $m->match_date }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Gates Opening</label>
                    <input type="time" name="gates_opening" class="form-control"
                           value="{{ $sheet->gates_opening ? \Carbon\Carbon::parse($sheet->gates_opening)->format('H:i') : '' }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kick-Off</label>
                    <input type="time" name="kick_off" class="form-control"
                           value="{{ $sheet->kick_off ? \Carbon\Carbon::parse($sheet->kick_off)->format('H:i') : '' }}">
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('drs.drs.show', $sheet->id) }}" class="btn btn-phoenix-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
