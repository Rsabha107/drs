@extends('drs.layout.admin_template')
@section('main')

@php
$event   = $sheet->event;
$matches = \App\Models\Drs\EventMatch::where('event_id', $event->id)->orderBy('match_date')->get();

$koFormatted = $sheet->kick_off ? \Carbon\Carbon::parse($sheet->kick_off)->format('H:i') : '';

$itemsJson = $sheet->items->map(fn($i) => [
    'id'              => $i->id,
    'title'           => $i->title,
    'start_time'      => $i->start_time ? \Carbon\Carbon::parse($i->start_time)->format('H:i') : '',
    'end_time'        => $i->end_time   ? \Carbon\Carbon::parse($i->end_time)->format('H:i')   : '',
    'functional_area' => $i->functional_area ?? '',
    'location'        => $i->location ?? '',
    'description'     => $i->description ?? '',
    'row_color'       => $i->row_color,
    'sort_order'      => $i->sort_order ?? 0,
])->toJson();

$calcCountdown = function($startTime) use ($koFormatted) {
    if (!$startTime || !$koFormatted) return '';
    $koParts    = explode(':', $koFormatted);
    $koMins     = (int)$koParts[0] * 60 + (int)$koParts[1];
    $sParts     = explode(':', \Carbon\Carbon::parse($startTime)->format('H:i'));
    $sMins      = (int)$sParts[0] * 60 + (int)$sParts[1];
    $diff       = $sMins - $koMins;
    if ($diff === 0) return 'KO';
    $sign = $diff > 0 ? '+' : '-';
    $abs  = abs($diff);
    $h    = intdiv($abs, 60);
    $m    = $abs % 60;
    $label = 'KO' . $sign;
    if ($h > 0) $label .= $h . 'h';
    if ($m > 0) $label .= $m . 'm';
    return $label;
};
@endphp

<style>
.drs-header-table td { padding: 4px 12px; font-size: 0.9rem; }
.drs-header-table td:first-child { font-weight: 600; width: 140px; }
.run-sheet-table thead th {
    background-color: #305496;
    color: #ffffff;
    font-size: 0.82rem;
    padding: 8px 10px;
    white-space: nowrap;
}
.run-sheet-table td {
    font-size: 0.82rem;
    padding: 6px 10px;
    vertical-align: middle;
}
.run-sheet-table tr.color-red    td { background-color: #ff0000 !important; color: #fff; font-weight: bold; }
.run-sheet-table tr.color-yellow td { background-color: #ffff00 !important; color: #000; font-weight: bold; }
.run-sheet-table tr.color-green  td { background-color: #00b050 !important; color: #fff; font-weight: bold; }
@media print {
    .no-print { display: none !important; }
    .card { border: none !important; box-shadow: none !important; }
}
</style>

{{-- Action bar --}}
<div class="d-flex justify-content-between align-items-center m-2 mb-3 no-print">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('drs.drs.index') }}">Daily Run Sheets</a></li>
            <li class="breadcrumb-item active">{{ $sheet->sheet_type }} &mdash; {{ $sheet->run_date_dmy }}</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-subtle-success" data-bs-toggle="modal" data-bs-target="#add_item_modal">
            <i class="fa-solid fa-plus me-1"></i>Add Item
        </button>
        <button type="button" class="btn btn-subtle-warning drs-edit" data-id="{{ $sheet->id }}">
            <i class="fa-solid fa-pen me-1"></i>Edit Header
        </button>
        <a href="{{ route('drs.drs.export', $sheet->id) }}" class="btn btn-subtle-primary">
            <i class="fa-solid fa-file-excel me-1"></i>Export Excel
        </a>
        <button onclick="window.print()" class="btn btn-subtle-secondary">
            <i class="fa-solid fa-print me-1"></i>Print
        </button>
    </div>
</div>

<div class="card mx-2">
    <div class="card-body pb-0">

        {{-- Sheet header --}}
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h4 class="fw-bold mb-1" id="show-sheet-type">Daily Run Sheet ({{ $sheet->sheet_type }})</h4>
                <div class="text-muted small">{{ $event->name ?? '' }}</div>
            </div>
            <table class="drs-header-table table table-bordered mb-0" style="width:340px;">
                <tr>
                    <td>Venue</td>
                    <td class="fw-semibold" id="show-venue">{{ $sheet->venue->short_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td id="show-run-date">{{ $sheet->run_date_dmy }}</td>
                </tr>
                <tr>
                    <td>Match No</td>
                    <td id="show-match">{{ $sheet->match ? 'M' . $sheet->match->match_number : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Gates Opening</td>
                    <td id="show-gates">{{ $sheet->gates_opening ? \Carbon\Carbon::parse($sheet->gates_opening)->format('H:i') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Kick-Off</td>
                    <td id="show-kickoff">{{ $sheet->kick_off ? \Carbon\Carbon::parse($sheet->kick_off)->format('H:i') : 'N/A' }}</td>
                </tr>
            </table>
        </div>

        {{-- Items table --}}
        {{-- <table id="items_table"></table> --}}


<div class="card mx-2 mb-4 px-3">

    {{-- <div class="card-body"> --}}
        <div class="table-responsive">
            <table id="items_table"
                data-toggle="table"
                data-classes="table table-hover fs-9 mb-0 border-top border-translucent run-sheet-table"
                data-loading-template="loadingTemplate"
                data-url="{{ route('drs.show.list', $sheet->id) }}"
                data-icons-prefix="bx"
                data-icons="icons"
                data-show-refresh="true"
                data-show-columns="true"
                data-show-toggle="true"
                data-total-field="total"
                data-height="500"
                data-show-fullscreen="true"
                data-fixed-scroll="true"
                data-data-field="rows"
                data-page-list="[10, 20, 50, 100]"
                data-search="true"
                data-side-pagination="server"
                data-pagination="true"
                data-sort-name="start_time"
                data-sort-order="desc"
                data-trim-on-search="false"
                data-mobile-responsive="true"
                data-buttons-class="secondary"
                data-query-params="queryParams">
                <thead>
                    <tr>
                        <th data-field="id" data-sortable="true" data-visible="false">ID</th>
                        <th data-field="title" data-sortable="true">Title</th>
                        <th data-field="start_time" data-sortable="false">Start Time</th>
                        <th data-field="ko_offset" data-sortable="true">KO Offset</th>
                        <th data-field="end_time" data-sortable="true">End Time</th>
                        <th data-field="functional_area" data-sortable="true">Functional Area</th>
                        <th data-field="location" data-sortable="false">Location</th>
                        <th data-field="description" data-sortable="false">Description</th>
                        <th data-formatter="itemActionsFormatter" class="text-end">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    {{-- </div> --}}
</div>





    </div>
    <div class="card-footer no-print d-flex justify-content-end">
        <button type="button" class="btn btn-sm btn-phoenix-danger" id="delete_sheet_btn">
            <i class="fa-solid fa-trash me-1"></i>Delete Run Sheet
        </button>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     Add Item Modal
═══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="add_item_modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">
                <h3 class="mb-0 text-white">Add Run Sheet Item</h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="add_item_form" novalidate class="needs-validation">
                @csrf
                <input type="hidden" name="run_sheet_id" value="{{ $sheet->id }}">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required placeholder="Item title">
                            <div class="invalid-feedback">Title is required.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="start_time" id="add_start_time" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">KO Offset
                                @if($koFormatted)
                                    <span class="text-muted fw-normal">(KO {{ $koFormatted }})</span>
                                @endif
                            </label>
                            <input type="text" id="add_ko_offset" class="form-control font-monospace"
                                   placeholder="{{ $koFormatted ? 'e.g. KO-5h, KO+30m' : 'No KO set' }}"
                                   {{ $koFormatted ? '' : 'disabled' }}>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Time</label>
                            <input type="time" name="end_time" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Functional Area</label>
                            <input type="text" name="functional_area" class="form-control" placeholder="e.g. SEC - Security">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="e.g. PSA G">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Optional description"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Row Color <span class="text-danger">*</span></label>
                            <select name="row_color" class="form-select" id="add_row_color_select" required>
                                <option value="default" selected>Default (White)</option>
                                <option value="red">Red</option>
                                <option value="yellow">Yellow</option>
                                <option value="green">Green</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" min="0" value="0">
                        </div>
                        <div class="col-12">
                            <div id="add_color_preview" class="p-2 rounded border text-center fw-bold" style="font-size:0.9rem;">
                                Color Preview
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="add_item_btn">
                        <i class="fa-solid fa-plus me-1"></i>Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     Edit Item Modal
═══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="edit_item_modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">
                <h3 class="mb-0 text-white">Edit Run Sheet Item</h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit_item_form" novalidate class="needs-validation">
                @csrf
                <input type="hidden" name="id" id="edit_item_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edit_item_title" class="form-control" required>
                            <div class="invalid-feedback">Title is required.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="start_time" id="edit_item_start_time" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">KO Offset
                                @if($koFormatted)
                                    <span class="text-muted fw-normal">(KO {{ $koFormatted }})</span>
                                @endif
                            </label>
                            <input type="text" id="edit_ko_offset" class="form-control font-monospace"
                                   placeholder="{{ $koFormatted ? 'e.g. KO-5h, KO+30m' : 'No KO set' }}"
                                   {{ $koFormatted ? '' : 'disabled' }}>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Time</label>
                            <input type="time" name="end_time" id="edit_item_end_time" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Functional Area</label>
                            <input type="text" name="functional_area" id="edit_item_functional_area" class="form-control" placeholder="e.g. SEC - Security">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" id="edit_item_location" class="form-control" placeholder="e.g. PSA G">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit_item_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Row Color <span class="text-danger">*</span></label>
                            <select name="row_color" class="form-select" id="edit_row_color_select" required>
                                <option value="default">Default (White)</option>
                                <option value="red">Red</option>
                                <option value="yellow">Yellow</option>
                                <option value="green">Green</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" id="edit_item_sort_order" class="form-control" min="0">
                        </div>
                        <div class="col-12">
                            <div id="edit_color_preview" class="p-2 rounded border text-center fw-bold" style="font-size:0.9rem;">
                                Color Preview
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="edit_item_btn">
                        <i class="fa-solid fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     Edit Run Sheet Modal
═══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="edit_drs_modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-100">
            <div class="modal-header bg-modal-header">
                <h3 class="mb-0 text-white">Edit Daily Run Sheet</h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit_drs_form" novalidate class="needs-validation">
                @csrf
                <input type="hidden" id="edit_drs_id" name="id">
                <div class="modal-body">
                    @include('drs.drs._sheet_fields', ['prefix' => 'edit'])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="edit_drs_btn">
                        <i class="fa-solid fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
// ── Bootstrap-table setup ───────────────────────────────────────
var itemsData = {!! $itemsJson !!};

var icons = {
    refresh: 'bx-refresh',
    toggleOn: 'bx-toggle-right',
    toggleOff: 'bx-toggle-left',
    fullscreen: 'bx-fullscreen',
    columns: 'bx-list-ul',
};

function loadingTemplate() {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical"></i>';
}

function itemRowStyle(row) {
    var map = {
        'red':    { classes: 'color-red' },
        'yellow': { classes: 'color-yellow' },
        'green':  { classes: 'color-green' },
    };
    return map[row.row_color] || {};
}

function koOffsetFormatter(value, row) {
    var label = calcKoOffset(row.start_time);
    return label ? '<span class="text-nowrap fst-italic">' + label + '</span>' : '';
}

function itemActionsFormatter(value, row) {
    return [
        '<button type="button" class="btn btn-sm btn-phoenix-warning me-1 item-edit" data-id="' + row.id + '" title="Edit">',
            '<i class="fa-solid fa-pen"></i>',
        '</button>',
        '<button type="button" class="btn btn-sm btn-phoenix-danger item-delete" data-id="' + row.id + '" title="Delete">',
            '<i class="fa-solid fa-trash"></i>',
        '</button>',
    ].join('');
}

$(function () {
    $('#items_table').bootstrapTable({
        uniqueId:        'id',
        classes:         'table table-hover fs-9 mb-0 border-top border-translucent run-sheet-table',
        rowStyle:        itemRowStyle,
        loadingTemplate: loadingTemplate,
        iconsPrefix:     'bx',
        icons:           {refresh: 'bx-refresh', toggleOn: 'bx-toggle-right', toggleOff: 'bx-toggle-left', columns: 'bx-list-ul' },
        search:          true,
        pagination:      true,
        showToggle:      true,
        showRefresh:     true,
        pageSize:        25,
        pageList:        [10, 25, 50, 100, 'All'],
        sortName:        'sort_order',
        mobileResponsive: true,
        trimOnSearch:     true,
        sortOrder:       'asc',
        data:            itemsData,
        columns: [
            { field: 'id',              visible: false },
            { field: 'title',           title: 'Title',           sortable: true, class: 'ps-3' },
            { field: 'start_time',      title: 'Start Time',      sortable: true },
            { field: 'ko_offset',       title: 'KO Offset',       sortable: false,  formatter: koOffsetFormatter },
            { field: 'end_time',        title: 'End Time',        sortable: true },
            { field: 'functional_area', title: 'Functional Area', sortable: true },
            { field: 'location',        title: 'Location',        sortable: true },
            { field: 'description',     title: 'Description',     sortable: false },
            { field: 'actions',         title: 'Actions',         sortable: false, formatter: itemActionsFormatter, class: 'no-print' },
        ],
    });
});

// ── KO Offset helpers ───────────────────────────────────────────
var koTime = '{{ $koFormatted }}'; // "HH:MM" or ""

/**
 * Parse a KO offset string into a "HH:MM" start time, or null on failure.
 * Accepts: "KO", "KO-5h", "KO+30m", "KO-1h30m", "KO-5" (treated as hours)
 */
function parseKoOffset(text) {
    if (!koTime) return null;
    text = text.trim();
    if (/^KO$/i.test(text)) return koTime;

    var m = text.match(/^KO([+-])(?:(\d+)h)?(?:(\d+)m)?$/i);
    if (!m || (!m[2] && !m[3])) {
        // fallback: "KO±N" → N hours
        var s = text.match(/^KO([+-])(\d+)$/i);
        if (!s) return null;
        m = [null, s[1], s[2], null];
    }

    var sign     = m[1] === '+' ? 1 : -1;
    var hours    = m[2] ? parseInt(m[2], 10) : 0;
    var mins     = m[3] ? parseInt(m[3], 10) : 0;
    var offset   = sign * (hours * 60 + mins);
    var koParts  = koTime.split(':');
    var koMins   = parseInt(koParts[0], 10) * 60 + parseInt(koParts[1], 10);
    var result   = ((koMins + offset) % 1440 + 1440) % 1440;

    return String(Math.floor(result / 60)).padStart(2, '0') + ':' + String(result % 60).padStart(2, '0');
}

/**
 * Given a "HH:MM" time, return the KO offset label (e.g. "KO-5h"), or "".
 */
function calcKoOffset(timeStr) {
    if (!koTime || !timeStr) return '';
    var koParts  = koTime.split(':');
    var koMins   = parseInt(koParts[0], 10) * 60 + parseInt(koParts[1], 10);
    var tParts   = timeStr.split(':');
    var tMins    = parseInt(tParts[0], 10) * 60 + parseInt(tParts[1], 10);
    var diff     = tMins - koMins;
    if (diff === 0) return 'KO';
    var sign = diff > 0 ? '+' : '-';
    var abs  = Math.abs(diff);
    var h    = Math.floor(abs / 60);
    var m    = abs % 60;
    var label = 'KO' + sign;
    if (h > 0) label += h + 'h';
    if (m > 0) label += m + 'm';
    return label;
}

// ── Shared color styles ─────────────────────────────────────────
var colorStyles = {
    'default': { bg: '#ffffff', text: '#000000' },
    'red':     { bg: '#ff0000', text: '#ffffff' },
    'yellow':  { bg: '#ffff00', text: '#000000' },
    'green':   { bg: '#00b050', text: '#ffffff' },
};

// ── Add Item modal ──────────────────────────────────────────────
function updateAddPreview() {
    var s = colorStyles[$('#add_row_color_select').val()] || colorStyles['default'];
    $('#add_color_preview').css({ 'background-color': s.bg, 'color': s.text });
}

$('#add_row_color_select').on('change', updateAddPreview);
updateAddPreview();

// Bidirectional: start time ↔ KO offset (Add modal)
$('#add_start_time').on('change', function () {
    $('#add_ko_offset').val(calcKoOffset($(this).val()));
});

$('#add_ko_offset').on('blur', function () {
    var t = parseKoOffset($(this).val());
    if (t) {
        $('#add_start_time').val(t);
        $(this).val(calcKoOffset(t)).removeClass('is-invalid');
    } else if ($(this).val().trim() !== '') {
        $(this).addClass('is-invalid');
    }
}).on('keydown', function (e) {
    if (e.key === 'Enter') { e.preventDefault(); $(this).trigger('blur'); }
});

$('#add_item_modal').on('hidden.bs.modal', function () {
    $('#add_item_form')[0].reset();
    $('#add_item_form').removeClass('was-validated');
    $('#add_ko_offset').val('').removeClass('is-invalid');
    updateAddPreview();
});

$('#add_item_form').on('submit', function (e) {
    e.preventDefault();
    if (!this.checkValidity()) { $(this).addClass('was-validated'); return; }

    var $btn = $('#add_item_btn');
    var originalHtml = $btn.html();
    $btn.html('<i class="bx bx-loader-alt bx-spin me-1"></i>Adding…').prop('disabled', true);

    $.ajax({
        url: '{{ route('drs.drs.item.store') }}',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        success: function (res) {
            if (res.error) {
                toastr.error(res.message);
                $btn.html(originalHtml).prop('disabled', false);
                return;
            }
            toastr.success(res.message);
            $('#add_item_modal').modal('hide');

            var item = res.item;
            $('#items_table').bootstrapTable('append', [{
                id:              item.id,
                title:           item.title,
                start_time:      item.start_time,
                end_time:        item.end_time,
                functional_area: item.functional_area,
                location:        item.location,
                description:     item.description,
                row_color:       item.row_color,
                sort_order:      0,
            }]);

            $('#items_table').bootstrapTable('refresh');
            // $('#items_table').bootstrapTable('scrollTo', 'bottom');

        },
        error: function (xhr) {
            var msg = 'An error occurred.';
            try { msg = JSON.parse(xhr.responseText).message || msg; } catch (ex) {}
            toastr.error(msg);
            $btn.html(originalHtml).prop('disabled', false);
        },
        complete: function () {
            $btn.html(originalHtml).prop('disabled', false);
        }
    });
});

// ── Edit Item modal ─────────────────────────────────────────────
function updateEditItemPreview() {
    var s = colorStyles[$('#edit_row_color_select').val()] || colorStyles['default'];
    $('#edit_color_preview').css({ 'background-color': s.bg, 'color': s.text });
}

$('#edit_row_color_select').on('change', updateEditItemPreview);

// Bidirectional: start time ↔ KO offset (Edit modal)
$('#edit_item_start_time').on('change', function () {
    $('#edit_ko_offset').val(calcKoOffset($(this).val()));
});

$('#edit_ko_offset').on('blur', function () {
    var t = parseKoOffset($(this).val());
    if (t) {
        $('#edit_item_start_time').val(t);
        $(this).val(calcKoOffset(t)).removeClass('is-invalid');
    } else if ($(this).val().trim() !== '') {
        $(this).addClass('is-invalid');
    }
}).on('keydown', function (e) {
    if (e.key === 'Enter') { e.preventDefault(); $(this).trigger('blur'); }
});

$('#edit_item_modal').on('hidden.bs.modal', function () {
    $('#edit_item_form').removeClass('was-validated');
    $('#edit_ko_offset').val('').removeClass('is-invalid');
});

$('body').on('click', '.item-edit', function () {
    var id = $(this).data('id');
    var $btn = $(this);
    var originalHtml = $btn.html();
    $btn.html('<i class="bx bx-loader-alt bx-spin"></i>').prop('disabled', true);

    $.ajax({
        url: '/drs/drs/items/' + id + '/get',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#edit_item_id').val(data.id);
            $('#edit_item_title').val(data.title);
            $('#edit_item_start_time').val(data.start_time);
            $('#edit_ko_offset').val(calcKoOffset(data.start_time)).removeClass('is-invalid');
            $('#edit_item_end_time').val(data.end_time);
            $('#edit_item_functional_area').val(data.functional_area);
            $('#edit_item_location').val(data.location);
            $('#edit_item_description').val(data.description);
            $('#edit_row_color_select').val(data.row_color);
            $('#edit_item_sort_order').val(data.sort_order);
            $('#edit_item_form').removeClass('was-validated');
            updateEditItemPreview();
            $('#edit_item_modal').modal('show');
        },
        error: function () {
            toastr.error('Could not load item data.');
        },
        complete: function () {
            $btn.html(originalHtml).prop('disabled', false);
        }
    });
});

$('#edit_item_form').on('submit', function (e) {
    e.preventDefault();
    if (!this.checkValidity()) { $(this).addClass('was-validated'); return; }

    var $btn = $('#edit_item_btn');
    var originalHtml = $btn.html();
    $btn.html('<i class="bx bx-loader-alt bx-spin me-1"></i>Saving…').prop('disabled', true);

    $.ajax({
        url: '{{ route('drs.drs.item.update') }}',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        success: function (res) {
            if (res.error) {
                toastr.error(res.message);
                $btn.html(originalHtml).prop('disabled', false);
                return;
            }
            toastr.success(res.message);
            $('#edit_item_modal').modal('hide');

            var item = res.item;
            $('#items_table').bootstrapTable('updateByUniqueId', {
                id: item.id,
                row: {
                    title:           item.title,
                    start_time:      item.start_time,
                    end_time:        item.end_time,
                    functional_area: item.functional_area,
                    location:        item.location,
                    description:     item.description,
                    row_color:       item.row_color,
                },
            });
        },
        error: function (xhr) {
            var msg = 'An error occurred.';
            try { msg = JSON.parse(xhr.responseText).message || msg; } catch (ex) {}
            toastr.error(msg);
            $btn.html(originalHtml).prop('disabled', false);
        },
        complete: function () {
            $btn.html(originalHtml).prop('disabled', false);
        }
    });
});

// ── Delete item ─────────────────────────────────────────────────
$('body').on('click', '.item-delete', function () {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Delete this item?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
    }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url: '/drs/drs/items/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function (res) {
                if (res.error) { toastr.error(res.message); return; }
                toastr.success(res.message);
                $('#items_table').bootstrapTable('removeByUniqueId', id);
            },
            error: function () { toastr.error('Could not delete item.'); }
        });
    });
});

// ── Delete run sheet ────────────────────────────────────────────
$('#delete_sheet_btn').on('click', function () {
    Swal.fire({
        title: 'Delete this entire run sheet?',
        text: 'All items will also be deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
    }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url: '{{ route('drs.drs.destroy', $sheet->id) }}',
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                window.location.href = '{{ route('drs.drs.index') }}';
            },
            error: function () { toastr.error('Could not delete run sheet.'); }
        });
    });
});

// ── Open edit modal ─────────────────────────────────────────────
$('body').on('click', '.drs-edit', function () {
    var id = $(this).data('id');
    var $btn = $(this);
    var originalHtml = $btn.html();
    $btn.html('<i class="bx bx-loader-alt bx-spin"></i>').prop('disabled', true);

    $.ajax({
        url: '/drs/drs/' + id + '/get',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#edit_drs_id').val(data.id);
            $('#edit_drs_form [name="venue_id"]').val(data.venue_id);
            $('#edit_drs_form [name="sheet_type"]').val(data.sheet_type);
            $('#edit_drs_form [name="run_date"]').val(data.run_date);
            $('#edit_drs_form [name="match_id"]').val(data.match_id || '');
            $('#edit_drs_form [name="gates_opening"]').val(data.gates_opening);
            $('#edit_drs_form [name="kick_off"]').val(data.kick_off);
            $('#edit_drs_form').removeClass('was-validated');
            $('#edit_drs_modal').modal('show');
        },
        error: function () {
            toastr.error('Could not load run sheet data.');
        },
        complete: function () {
            $btn.html(originalHtml).prop('disabled', false);
        }
    });
});

$('#edit_drs_modal').on('hidden.bs.modal', function () {
    $('#edit_drs_form').removeClass('was-validated');
});

// ── Edit submit — refresh header info in-place ──────────────────
$('#edit_drs_form').on('submit', function (e) {
    e.preventDefault();
    if (!this.checkValidity()) { $(this).addClass('was-validated'); return; }

    var $btn = $('#edit_drs_btn');
    var originalHtml = $btn.html();
    $btn.html('<i class="bx bx-loader-alt bx-spin me-1"></i>Saving…').prop('disabled', true);

    $.ajax({
        url: '{{ route('drs.drs.update') }}',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (res) {
            if (res.error) {
                toastr.error(res.message);
                $btn.html(originalHtml).prop('disabled', false);
            } else {
                toastr.success(res.message);
                $('#edit_drs_modal').modal('hide');
                // Reload page so the header table reflects changes
                window.location.reload();
            }
        },
        error: function (xhr) {
            var msg = 'An error occurred.';
            try { msg = JSON.parse(xhr.responseText).message || msg; } catch (ex) {}
            toastr.error(msg);
            $btn.html(originalHtml).prop('disabled', false);
        }
    });
});
</script>
@endpush
