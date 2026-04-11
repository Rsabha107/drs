@extends('drs.layout.admin_template')
@section('main')

    <style>
        .filter-bar {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 14px 18px;
        }

        .sheet-header-table {
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        .sheet-header-table td {
            padding: 4px 10px;
            border: 1px solid #bbb;
        }

        .sheet-header-table td:first-child {
            font-weight: 600;
            background: #d9e1f2;
            white-space: nowrap;
        }

        .sheet-header-table td:nth-child(3) {
            font-weight: 500;
            background: #d9e1f2;

        }

        .sheet-title-row td {
            background: #1f3864 !important;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            padding: 8px 12px;
        }

        .run-sheet-table thead th {
            background-color: #305496;
            color: #ffffff;
            font-size: 0.80rem;
            padding: 7px 9px;
            white-space: nowrap;
            border: 1px solid #254282;
        }

        .run-sheet-table td {
            font-size: 0.80rem;
            padding: 5px 9px;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }

        .run-sheet-table tr.color-red td {
            background-color: #ff0000 !important;
            color: #fff;
            font-weight: bold;
        }

        .run-sheet-table tr.color-yellow td {
            background-color: #ffff00 !important;
            color: #000;
            font-weight: bold;
        }

        .run-sheet-table tr.color-green td {
            background-color: #00b050 !important;
            color: #fff;
            font-weight: bold;
        }

        .run-sheet-table tbody tr:hover td {
            filter: brightness(0.95);
        }

        @media print {
            .no-print { display: none !important; }
            .card { border: none !important; box-shadow: none !important; }
        }
    </style>

    {{-- Breadcrumb & actions --}}
    <div class="d-flex justify-content-between align-items-center m-2 mb-3 no-print">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('drs.admin.drs') }}">Daily Run Sheets</a></li>
                <li class="breadcrumb-item active">Combined Run Sheet</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            @if ($matchHeader)
                <button type="button" class="btn btn-sm btn-subtle-success" data-bs-toggle="modal" data-bs-target="#add_item_modal">
                    <i class="fa-solid fa-plus me-1"></i>Add Item
                </button>
                <button onclick="window.print()" class="btn btn-sm btn-subtle-secondary">
                    <i class="fa-solid fa-print me-1"></i>Print
                </button>
            @endif
        </div>
    </div>

    <div class="card mx-2">
        <div class="card-body p-3">

            {{-- Filter bar --}}
            <form method="GET" action="{{ route('drs.admin.flat.list') }}" id="filter_form" class="filter-bar mb-4 no-print">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-1">Venue</label>
                        <select name="venue_id" id="venue_select" class="form-select form-select-sm">
                            <option value="">— Select Venue —</option>
                            @foreach ($venues as $v)
                                <option value="{{ $v->id }}" {{ $venueId == $v->id ? 'selected' : '' }}>
                                    {{ $v->short_name ?? $v->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-1">Match</label>
                        <select name="match_id" id="match_select" class="form-select form-select-sm"
                            {{ !$venueId ? 'disabled' : '' }}>
                            <option value="">— Select Match —</option>
                            @foreach ($matches as $m)
                                <option value="{{ $m->id }}" {{ $matchId == $m->id ? 'selected' : '' }}>
                                    M{{ $m->match_number }}
                                    @if ($m->pma1 || $m->pma2) — {{ $m->pma1 }} vs {{ $m->pma2 }} @endif
                                    @if ($m->match_date) ({{ \Carbon\Carbon::parse($m->match_date)->format('d/m/Y') }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="fa-solid fa-magnifying-glass me-1"></i>Apply
                        </button>
                    </div>
                    @if ($venueId || $matchId)
                        <div class="col-md-2">
                            <a href="{{ route('drs.admin.flat.list') }}" class="btn btn-sm btn-outline-secondary w-100">
                                <i class="fa-solid fa-xmark me-1"></i>Clear
                            </a>
                        </div>
                    @endif
                </div>
            </form>

            {{-- No selection state --}}
            @if (!$venueId || !$matchId)
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-filter fa-2x mb-3 d-block opacity-40"></i>
                    Select a venue and match to view the combined run sheet.
                </div>

            {{-- Results --}}
            @else
                @php
                    $match       = $matchHeader?->match;
                    $venue       = $matchHeader?->venue;
                    $koFormatted = $matchHeader?->kick_off
                        ? \Carbon\Carbon::parse($matchHeader->kick_off)->format('H:i')
                        : '';
                    $reloadUrl   = route('drs.admin.flat.list') . '?venue_id=' . $venueId . '&match_id=' . $matchId;
                @endphp

                {{-- Excel-style header --}}
                <div class="mb-4">
                    <table class="sheet-header-table">
                        <tr class="sheet-title-row">
                            <td colspan="4">
                                Daily Run Sheet
                                @if ($matchHeader?->sheet_type) ({{ $matchHeader->sheet_type }}) @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Venue</td>
                            <td>{{ $venue?->short_name ?? $venue?->name ?? 'N/A' }}</td>
                            <td>Match No</td>
                            <td>{{ $match ? $match->match_number : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>
                                {{ $match && $match->match_date
                                    ? \Carbon\Carbon::parse($match->match_date)->format('d/m/Y')
                                    : ($matchHeader?->run_date_dmy ?? 'N/A') }}
                            </td>
                            <td>Teams</td>
                            <td>{{ $match ? ($match->pma1 . ' vs ' . $match->pma2) : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Gates Opening</td>
                            <td>
                                {{ $matchHeader?->gates_opening
                                    ? \Carbon\Carbon::parse($matchHeader->gates_opening)->format('H:i')
                                    : 'N/A' }}
                            </td>
                            <td>Kick-Off</td>
                            <td>{{ $koFormatted ?: 'N/A' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2 no-print">
                    <span class="text-muted small">
                        {{ $items->count() }} item(s) across all functional areas — sorted by start time
                    </span>
                </div>

                @if ($items->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fa-solid fa-inbox fa-lg me-1"></i>
                        No items found for this venue / match combination.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered run-sheet-table w-100" id="flat_items_table">
                            <thead>
                                <tr>
                                    <th style="width:34px">#</th>
                                    <th>Title</th>
                                    <th style="width:78px">Start</th>
                                    <th style="width:90px">Countdown</th>
                                    <th style="width:78px">End</th>
                                    <th>Functional Area</th>
                                    <th>Location</th>
                                    <th>Description</th>
                                    <th class="no-print" style="width:80px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    @php
                                        $sheet    = $item->_parentSheet;
                                        $fa       = $sheet?->functionalArea;
                                        $faLabel  = $fa?->title ?? $fa?->name ?? ($item->functional_area ?? '-');

                                        $countdown = $item->countdown_to_ko ?? '';
                                        if ($item->start_time && $koFormatted) {
                                            $koParts = explode(':', $koFormatted);
                                            $koMins  = (int)$koParts[0] * 60 + (int)$koParts[1];
                                            $sParts  = explode(':', \Carbon\Carbon::parse($item->start_time)->format('H:i'));
                                            $sMins   = (int)$sParts[0] * 60 + (int)$sParts[1];
                                            $diff    = $sMins - $koMins;
                                            if ($diff === 0) {
                                                $countdown = 'KO';
                                            } else {
                                                $sign  = $diff > 0 ? '+' : '-';
                                                $abs   = abs($diff);
                                                $h     = intdiv($abs, 60);
                                                $m     = $abs % 60;
                                                $label = 'KO' . $sign;
                                                if ($h > 0) $label .= $h . 'h';
                                                if ($m > 0) $label .= $m . 'm';
                                                $countdown = $label;
                                            }
                                        }

                                        $rowClass = ($item->row_color && $item->row_color !== 'default')
                                            ? 'color-' . $item->row_color : '';
                                    @endphp
                                    <tr class="{{ $rowClass }}" data-id="{{ $item->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->start_time ? \Carbon\Carbon::parse($item->start_time)->format('H:i') : '-' }}</td>
                                        <td>{{ $countdown ?: '-' }}</td>
                                        <td>{{ $item->end_time ? \Carbon\Carbon::parse($item->end_time)->format('H:i') : '-' }}</td>
                                        <td>{{ $faLabel }}</td>
                                        <td>{{ $item->location ?? '-' }}</td>
                                        <td>{{ $item->description ?? '-' }}</td>
                                        <td class="no-print text-nowrap">
                                            <button type="button"
                                                class="btn btn-sm btn-phoenix-warning me-1 item-edit"
                                                data-id="{{ $item->id }}" title="Edit">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-sm btn-phoenix-danger item-delete"
                                                data-id="{{ $item->id }}" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif

        </div>
    </div>

    @if ($matchHeader)
    {{-- ═══════════════════════════════════════════════════════
         Add Item Modal
    ═══════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="add_item_modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content bg-100">
                <div class="modal-header bg-modal-header">
                    <h3 class="mb-0 text-white">Add Run Sheet Item</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="add_item_form" novalidate class="needs-validation">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Functional Area (Sheet) <span class="text-danger">*</span></label>
                                <select name="run_sheet_id" class="form-select" required>
                                    <option value="">— Select —</option>
                                    @foreach ($sheets as $s)
                                        <option value="{{ $s->id }}">
                                            {{ $s->functionalArea?->title ?? $s->functionalArea?->name ?? 'Sheet #' . $s->id }}
                                            ({{ $s->sheet_type }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a functional area.</div>
                            </div>
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
                                <label class="form-label">
                                    KO Offset
                                    @if ($koFormatted)
                                        <span class="text-muted fw-normal">(KO {{ $koFormatted }})</span>
                                    @endif
                                </label>
                                <input type="text" id="add_countdown_to_ko" name="countdown_to_ko"
                                    class="form-control font-monospace"
                                    placeholder="{{ $koFormatted ? 'e.g. KO-5h, KO+30m' : 'No KO set' }}"
                                    {{ $koFormatted ? '' : 'disabled' }}>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">End Time</label>
                                <input type="time" name="end_time" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" placeholder="e.g. PSA G">
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
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Optional"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" min="0" value="0">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div id="add_color_preview" class="w-100 p-2 rounded border text-center fw-bold"
                                    style="font-size:0.85rem;">Color Preview</div>
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

    {{-- ═══════════════════════════════════════════════════════
         Edit Item Modal
    ═══════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="edit_item_modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content bg-100">
                <div class="modal-header bg-modal-header">
                    <h3 class="mb-0 text-white">Edit Run Sheet Item</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                                <label class="form-label">
                                    KO Offset
                                    @if ($koFormatted)
                                        <span class="text-muted fw-normal">(KO {{ $koFormatted }})</span>
                                    @endif
                                </label>
                                <input type="text" id="edit_countdown_to_ko" name="countdown_to_ko"
                                    class="form-control font-monospace"
                                    placeholder="{{ $koFormatted ? 'e.g. KO-5h, KO+30m' : 'No KO set' }}"
                                    {{ $koFormatted ? '' : 'disabled' }}>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">End Time</label>
                                <input type="time" name="end_time" id="edit_item_end_time" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" id="edit_item_location" class="form-control">
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
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="edit_item_description" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" id="edit_item_sort_order" class="form-control" min="0">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div id="edit_color_preview" class="w-100 p-2 rounded border text-center fw-bold"
                                    style="font-size:0.85rem;">Color Preview</div>
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
    @endif

    <script>
        var koTime      = '{{ $koFormatted ?? '' }}';
        var reloadUrl   = '{!! $reloadUrl ?? '' !!}';
        var csrfToken   = '{{ csrf_token() }}';

        var colorStyles = {
            'default': { bg: '#ffffff', text: '#000000' },
            'red':     { bg: '#ff0000', text: '#ffffff' },
            'yellow':  { bg: '#ffff00', text: '#000000' },
            'green':   { bg: '#00b050', text: '#ffffff' },
        };

        // ── KO offset helpers ────────────────────────────────────────────
        function parseKoOffset(text) {
            if (!koTime) return null;
            text = text.trim();
            if (/^KO$/i.test(text)) return koTime;
            var m = text.match(/^KO([+-])(?:(\d+)h)?(?:(\d+)m)?$/i);
            if (!m || (!m[2] && !m[3])) {
                var s = text.match(/^KO([+-])(\d+)$/i);
                if (!s) return null;
                m = [null, s[1], s[2], null];
            }
            var sign  = m[1] === '+' ? 1 : -1;
            var hours = m[2] ? parseInt(m[2], 10) : 0;
            var mins  = m[3] ? parseInt(m[3], 10) : 0;
            var offset = sign * (hours * 60 + mins);
            var koParts = koTime.split(':');
            var koMins  = parseInt(koParts[0], 10) * 60 + parseInt(koParts[1], 10);
            var result  = ((koMins + offset) % 1440 + 1440) % 1440;
            return String(Math.floor(result / 60)).padStart(2, '0') + ':' + String(result % 60).padStart(2, '0');
        }

        function calcKoOffset(timeStr) {
            if (!koTime || !timeStr) return '';
            var koParts = koTime.split(':');
            var koMins  = parseInt(koParts[0], 10) * 60 + parseInt(koParts[1], 10);
            var tParts  = timeStr.split(':');
            var tMins   = parseInt(tParts[0], 10) * 60 + parseInt(tParts[1], 10);
            var diff = tMins - koMins;
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

        // ── Venue → Match dynamic loader ────────────────────────────────
        document.getElementById('venue_select').addEventListener('change', function () {
            var venueId     = this.value;
            var matchSelect = document.getElementById('match_select');
            matchSelect.innerHTML = '<option value="">— Loading… —</option>';
            matchSelect.disabled  = true;
            if (!venueId) {
                matchSelect.innerHTML = '<option value="">— Select Match —</option>';
                return;
            }
            fetch('/drs/venue/' + venueId + '/matches')
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    matchSelect.innerHTML = '<option value="">— Select Match —</option>';
                    data.forEach(function(m) {
                        var date  = m.match_date ? new Date(m.match_date).toLocaleDateString('en-GB') : '';
                        var teams = (m.pma1 || m.pma2) ? ' — ' + m.pma1 + ' vs ' + m.pma2 : '';
                        var opt   = document.createElement('option');
                        opt.value       = m.id;
                        opt.textContent = 'M' + m.match_number + teams + (date ? ' (' + date + ')' : '');
                        matchSelect.appendChild(opt);
                    });
                    matchSelect.disabled = false;
                })
                .catch(function() {
                    matchSelect.innerHTML = '<option value="">— Error loading matches —</option>';
                });
        });

        $(function () {

            // ── Add modal: color preview & KO offset ──────────────────
            function updateAddPreview() {
                var s = colorStyles[$('#add_row_color_select').val()] || colorStyles['default'];
                $('#add_color_preview').css({ 'background-color': s.bg, 'color': s.text });
            }
            $('#add_row_color_select').on('change', updateAddPreview);
            updateAddPreview();

            $('#add_start_time').on('change', function () {
                $('#add_countdown_to_ko').val(calcKoOffset($(this).val()));
            });
            $('#add_countdown_to_ko').on('blur', function () {
                var t = parseKoOffset($(this).val());
                if (t) { $('#add_start_time').val(t); $(this).val(calcKoOffset(t)).removeClass('is-invalid'); }
                else if ($(this).val().trim()) { $(this).addClass('is-invalid'); }
            }).on('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); $(this).trigger('blur'); }
            });

            $('#add_item_modal').on('hidden.bs.modal', function () {
                $('#add_item_form')[0].reset();
                $('#add_item_form').removeClass('was-validated');
                $('#add_countdown_to_ko').removeClass('is-invalid');
                updateAddPreview();
            });

            $('#add_item_form').on('submit', function (e) {
                e.preventDefault();
                if (!this.checkValidity()) { $(this).addClass('was-validated'); return; }
                var $btn = $('#add_item_btn');
                var orig = $btn.html();
                $btn.html('<i class="bx bx-loader-alt bx-spin me-1"></i>Adding…').prop('disabled', true);
                $.ajax({
                    url: '{{ route('drs.drs.item.store') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    success: function (res) {
                        if (res.error) { toastr.error(res.message); $btn.html(orig).prop('disabled', false); return; }
                        toastr.success(res.message);
                        $('#add_item_modal').modal('hide');
                        window.location.href = reloadUrl;
                    },
                    error: function (xhr) {
                        var msg = 'An error occurred.';
                        try { msg = JSON.parse(xhr.responseText).message || msg; } catch (ex) {}
                        toastr.error(msg);
                        $btn.html(orig).prop('disabled', false);
                    }
                });
            });

            // ── Edit modal: color preview & KO offset ─────────────────
            function updateEditPreview() {
                var s = colorStyles[$('#edit_row_color_select').val()] || colorStyles['default'];
                $('#edit_color_preview').css({ 'background-color': s.bg, 'color': s.text });
            }
            $('#edit_row_color_select').on('change', updateEditPreview);

            $('#edit_item_start_time').on('change', function () {
                $('#edit_countdown_to_ko').val(calcKoOffset($(this).val()));
            });
            $('#edit_countdown_to_ko').on('blur', function () {
                var t = parseKoOffset($(this).val());
                if (t) { $('#edit_item_start_time').val(t); $(this).val(calcKoOffset(t)).removeClass('is-invalid'); }
                else if ($(this).val().trim()) { $(this).addClass('is-invalid'); }
            }).on('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); $(this).trigger('blur'); }
            });

            $('#edit_item_modal').on('hidden.bs.modal', function () {
                $('#edit_item_form').removeClass('was-validated');
                $('#edit_countdown_to_ko').removeClass('is-invalid');
            });

            // Open edit modal — load item data via AJAX
            $('body').on('click', '.item-edit', function () {
                var id   = $(this).data('id');
                var $btn = $(this);
                var orig = $btn.html();
                $btn.html('<i class="bx bx-loader-alt bx-spin"></i>').prop('disabled', true);
                $.ajax({
                    url: '/drs/drs/items/' + id + '/get',
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#edit_item_id').val(data.id);
                        $('#edit_item_title').val(data.title);
                        $('#edit_item_start_time').val(data.start_time);
                        $('#edit_countdown_to_ko').val(calcKoOffset(data.start_time)).removeClass('is-invalid');
                        $('#edit_item_end_time').val(data.end_time);
                        $('#edit_item_location').val(data.location);
                        $('#edit_item_description').val(data.description);
                        $('#edit_row_color_select').val(data.row_color);
                        $('#edit_item_sort_order').val(data.sort_order);
                        $('#edit_item_form').removeClass('was-validated');
                        updateEditPreview();
                        $('#edit_item_modal').modal('show');
                    },
                    error: function () { toastr.error('Could not load item data.'); },
                    complete: function () { $btn.html(orig).prop('disabled', false); }
                });
            });

            $('#edit_item_form').on('submit', function (e) {
                e.preventDefault();
                if (!this.checkValidity()) { $(this).addClass('was-validated'); return; }
                var $btn = $('#edit_item_btn');
                var orig = $btn.html();
                $btn.html('<i class="bx bx-loader-alt bx-spin me-1"></i>Saving…').prop('disabled', true);
                $.ajax({
                    url: '{{ route('drs.drs.item.update') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    success: function (res) {
                        if (res.error) { toastr.error(res.message); $btn.html(orig).prop('disabled', false); return; }
                        toastr.success(res.message);
                        $('#edit_item_modal').modal('hide');
                        window.location.href = reloadUrl;
                    },
                    error: function (xhr) {
                        var msg = 'An error occurred.';
                        try { msg = JSON.parse(xhr.responseText).message || msg; } catch (ex) {}
                        toastr.error(msg);
                        $btn.html(orig).prop('disabled', false);
                    }
                });
            });

            // ── Delete ────────────────────────────────────────────────
            $('body').on('click', '.item-delete', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Delete this item?',
                    text: 'This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete',
                }).then(function (result) {
                    if (!result.isConfirmed) return;
                    $.ajax({
                        url: '/drs/drs/items/' + id,
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        success: function (res) {
                            toastr.success(res.message || 'Item deleted.');
                            window.location.href = reloadUrl;
                        },
                        error: function () { toastr.error('Could not delete item.'); }
                    });
                });
            });

        });
    </script>

@endsection
