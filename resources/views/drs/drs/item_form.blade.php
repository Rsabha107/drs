@extends('drs.layout.admin_template')
@section('main')

@php $isEdit = isset($item); @endphp

<div class="d-flex justify-content-between align-items-center m-2 mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('drs.drs.index') }}">Daily Run Sheets</a></li>
            <li class="breadcrumb-item"><a href="{{ route('drs.drs.show', $sheet->id) }}">{{ $sheet->sheet_type }}</a></li>
            <li class="breadcrumb-item active">{{ $isEdit ? 'Edit Item' : 'Add Item' }}</li>
        </ol>
    </nav>
</div>

<div class="card mx-2" style="max-width:720px;">
    <div class="card-header">
        <h5 class="mb-0">{{ $isEdit ? 'Edit' : 'Add' }} Run Sheet Item</h5>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ $isEdit ? route('drs.drs.item.update') : route('drs.drs.item.store') }}" method="POST">
            @csrf
            <input type="hidden" name="run_sheet_id" value="{{ $sheet->id }}">
            @if($isEdit)
                <input type="hidden" name="id" value="{{ $item->id }}">
            @endif

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control"
                           value="{{ old('title', $item->title ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Start Time</label>
                    <input type="time" name="start_time" class="form-control"
                           value="{{ old('start_time', isset($item->start_time) ? \Carbon\Carbon::parse($item->start_time)->format('H:i') : '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">End Time</label>
                    <input type="time" name="end_time" class="form-control"
                           value="{{ old('end_time', isset($item->end_time) ? \Carbon\Carbon::parse($item->end_time)->format('H:i') : '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Functional Area</label>
                    <input type="text" name="functional_area" class="form-control"
                           value="{{ old('functional_area', $item->functional_area ?? '') }}"
                           placeholder="e.g. SEC - Security">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control"
                           value="{{ old('location', $item->location ?? '') }}"
                           placeholder="e.g. PSA G">
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description ?? '') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Row Color <span class="text-danger">*</span></label>
                    <select name="row_color" class="form-select" id="row_color_select">
                        @php
                        $colors = ['default' => 'Default (White)', 'red' => 'Red', 'yellow' => 'Yellow', 'green' => 'Green'];
                        $current = old('row_color', $item->row_color ?? 'default');
                        @endphp
                        @foreach($colors as $val => $label)
                            <option value="{{ $val }}" {{ $current == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" min="0"
                           value="{{ old('sort_order', $item->sort_order ?? 0) }}">
                </div>
            </div>

            {{-- Color preview --}}
            <div id="color-preview" class="mt-3 p-2 rounded border text-center fw-bold" style="font-size:0.9rem;">
                Color Preview
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Item' : 'Add Item' }}</button>
                <a href="{{ route('drs.drs.show', $sheet->id) }}" class="btn btn-phoenix-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('script')
<script>
var colorStyles = {
    'default': { bg: '#ffffff', text: '#000000' },
    'red':     { bg: '#ff0000', text: '#ffffff' },
    'yellow':  { bg: '#ffff00', text: '#000000' },
    'green':   { bg: '#00b050', text: '#ffffff' },
};

function updatePreview() {
    var val = $('#row_color_select').val();
    var s = colorStyles[val] || colorStyles['default'];
    $('#color-preview').css({ 'background-color': s.bg, 'color': s.text });
}

$(document).ready(function() {
    updatePreview();
    $('#row_color_select').on('change', updatePreview);
});
</script>
@endpush
