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

        .sheet-header-table td:first-child,
        .sheet-header-table td:nth-child(3) {
            font-weight: 600;
            background: #d9e1f2;
            white-space: nowrap;
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
        }

        .run-sheet-table tr.color-red td {
            background-color: #ff0000 !important;
            color: #fff !important;
            font-weight: bold;
        }

        .run-sheet-table tr.color-yellow td {
            background-color: #ffff00 !important;
            color: #000 !important;
            font-weight: bold;
        }

        .run-sheet-table tr.color-green td {
            background-color: #00b050 !important;
            color: #fff !important;
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
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
                <button type="button" class="btn btn-sm btn-subtle-success" data-bs-toggle="modal"
                    data-bs-target="#add_item_modal">
                    <i class="fa-solid fa-plus me-1"></i>Add Item
                </button>
                <a href="{{ route('drs.admin.flat.list.export', ['event_id' => session()->get('EVENT_ID'), 'venue_id' => $venueId, 'match_id' => $matchId, 'sheet_type' => $sheetType]) }}"
                    class="btn btn-sm btn-subtle-success">
                    <i class="fa-solid fa-file-excel me-1"></i>Export Excel
                </a>
                <button onclick="window.print()" class="btn btn-sm btn-subtle-secondary">
                    <i class="fa-solid fa-print me-1"></i>Print
                </button>
            @endif
        </div>
    </div>

    <div class="card mx-2 mb-5">
        <div class="card-body p-3">

            {{-- Filter bar --}}
            <form method="GET" action="{{ route('drs.admin.flat.list') }}" id="filter_form"
                class="filter-bar mb-4 no-print">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label class="form-label fw-semibold mb-1">Match</label>
                        <select name="match_id" id="match_select" class="form-select form-select-sm"
                            {{ !$venueId ? 'disabled' : '' }}>
                            <option value="">— Select Match —</option>
                            @foreach ($matches as $m)
                                <option value="{{ $m->id }}" {{ $matchId == $m->id ? 'selected' : '' }}>
                                    M{{ $m->match_number }}
                                    @if ($m->pma1 || $m->pma2)
                                        — {{ $m->pma1 }} vs {{ $m->pma2 }}
                                    @endif
                                    @if ($m->match_date)
                                        ({{ \Carbon\Carbon::parse($m->match_date)->format('d/m/Y') }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold mb-1">Sheet Type</label>
                        <select name="sheet_type" id="sheet_type_select" class="form-select form-select-sm"
                            {{ !$matchId ? 'disabled' : '' }}>
                            <option value="">— All Types —</option>
                            @foreach ($sheetTypes ?? [] as $st)
                                <option value="{{ $st }}" {{ ($sheetType ?? '') == $st ? 'selected' : '' }}>
                                    {{ $st }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="fa-solid fa-magnifying-glass me-1"></i>Apply
                        </button>
                    </div>
                    @if ($venueId || $matchId || ($sheetType ?? null))
                        <div class="col-md-1">
                            <a href="{{ route('drs.admin.flat.list', ['event_id' => session()->get('EVENT_ID')]) }}" class="btn btn-sm btn-outline-secondary w-100"
                                title="Clear filters">
                                <i class="fa-solid fa-xmark"></i>
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
            @else
                @php
                    $match = $matchHeader?->match;
                    $venue = $matchHeader?->venue;
                    $koFormatted = $matchHeader?->kick_off
                        ? \Carbon\Carbon::parse($matchHeader->kick_off)->format('H:i')
                        : '';
                @endphp

                {{-- Excel-style header --}}
                <div class="mb-4">
                    <table class="sheet-header-table">
                        <tr class="sheet-title-row">
                            <td colspan="4">
                                Daily Run Sheet
                                @if ($matchHeader?->sheet_type)
                                    ({{ $matchHeader->sheet_type }})
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Venue</td>
                            <td>{{ $venue?->short_name ?? ($venue?->name ?? 'N/A') }}</td>
                            <td>Match No</td>
                            <td>{{ $match?->match_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>
                                {{ $match?->match_date
                                    ? \Carbon\Carbon::parse($match->match_date)->format('d/m/Y')
                                    : $matchHeader?->run_date_dmy ?? 'N/A' }}
                            </td>
                            <td>Teams</td>
                            <td>{{ $match ? $match->pma1 . ' vs ' . $match->pma2 : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Gates Opening</td>
                            <td>
                                {{ $matchHeader?->gates_opening ? \Carbon\Carbon::parse($matchHeader->gates_opening)->format('H:i') : 'N/A' }}
                            </td>
                            <td>Kick-Off</td>
                            <td>{{ $koFormatted ?: 'N/A' }}</td>
                        </tr>
                    </table>
                </div>

                {{-- Bootstrap Table --}}
                <div class="table-responsive">
                    <table id="flat_items_table" data-toggle="table"
                        data-classes="table table-hover fs-9 mb-0 border-top border-translucent run-sheet-table"
                        data-loading-template="loadingTemplate" data-url="{{ route('drs.admin.flat.list.data') }}"
                        data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-show-columns="true"
                        data-show-toggle="true" data-show-fullscreen="true" data-fixed-scroll="true"
                        data-total-field="total" data-data-field="rows" data-page-list="[25, 50, 100, 200]"
                        data-search="true" data-side-pagination="server" data-pagination="true" data-sort-name="start_time"
                        data-sort-order="asc" data-trim-on-search="false" data-mobile-responsive="true"
                        data-buttons-class="secondary" data-row-style="itemRowStyle" data-height="660"
                        data-fixed-scroll="true" data-show-pagination-switch="true" data-query-params="queryParams">
                        <thead>
                            <tr>
                                <th data-field="id" data-sortable="true" data-visible="false">ID</th>
                                <th data-field="title" data-sortable="true">Title</th>
                                <th data-field="start_time" data-sortable="true" style="width:85px">Start</th>
                                <th data-field="countdown_to_ko" data-sortable="false" style="width:95px">Countdown</th>
                                <th data-field="end_time" data-sortable="true" style="width:75px">End</th>
                                <th data-field="functional_area" data-sortable="true">Functional Area</th>
                                <th data-field="location" data-sortable="true">Location</th>
                                <th data-field="description" data-sortable="false">Description</th>
                                <th data-formatter="itemActionsFormatter" class="no-print text-end" style="width:90px">
                                    Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            @endif

        </div>
    </div>

    @if ($matchHeader)
        {{-- ═══════════════════════════════════════════ Add Item Modal ═══ --}}
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
                                    <label class="form-label">Functional Area (Sheet) <span
                                            class="text-danger">*</span></label>
                                    <select name="run_sheet_id" class="form-select" required>
                                        <option value="">— Select —</option>
                                        @foreach ($sheets as $s)
                                            <option value="{{ $s->id }}">
                                                {{ $s->functionalArea?->title ?? ($s->functionalArea?->name ?? 'Sheet #' . $s->id) }}
                                                ({{ $s->sheet_type }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a functional area.</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" required
                                        placeholder="Item title">
                                    <div class="invalid-feedback">Title is required.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" name="start_time" id="add_start_time" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">KO Offset
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
                                    <textarea name="description" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" min="0"
                                        value="0">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div id="add_color_preview" class="w-100 p-2 rounded border text-center fw-bold"
                                        style="font-size:0.85rem;">Color Preview</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add_item_btn">
                                <i class="fa-solid fa-plus me-1"></i>Add Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════ Edit Item Modal ═══ --}}
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
                                    <input type="text" name="title" id="edit_item_title" class="form-control"
                                        required>
                                    <div class="invalid-feedback">Title is required.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" name="start_time" id="edit_item_start_time"
                                        class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">KO Offset
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
                                    <input type="number" name="sort_order" id="edit_item_sort_order"
                                        class="form-control" min="0">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div id="edit_color_preview" class="w-100 p-2 rounded border text-center fw-bold"
                                        style="font-size:0.85rem;">Color Preview</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="edit_item_btn">
                                <i class="fa-solid fa-save me-1"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ── Venue → Match → Sheet Type dynamic loaders ─────────────────── --}}
    <script>
        function resetSheetTypeSelect() {
            var stSelect = document.getElementById('sheet_type_select');
            stSelect.innerHTML = '<option value="">— All Types —</option>';
            stSelect.disabled = true;
        }

        function loadSheetTypes(venueId, matchId) {
            var stSelect = document.getElementById('sheet_type_select');
            stSelect.innerHTML = '<option value="">— Loading… —</option>';
            stSelect.disabled = true;
            fetch('/drs/admin/flat-list/sheet-types?venue_id=' + venueId + '&match_id=' + matchId)
                .then(function(r) {
                    return r.json();
                })
                .then(function(types) {
                    stSelect.innerHTML = '<option value="">— All Types —</option>';
                    types.forEach(function(t) {
                        var opt = document.createElement('option');
                        opt.value = t;
                        opt.textContent = t;
                        stSelect.appendChild(opt);
                    });
                    stSelect.disabled = types.length === 0;
                })
                .catch(function() {
                    stSelect.innerHTML = '<option value="">— All Types —</option>';
                    stSelect.disabled = true;
                });
        }

        document.getElementById('venue_select').addEventListener('change', function() {
            var venueId = this.value;
            var matchSelect = document.getElementById('match_select');
            matchSelect.innerHTML = '<option value="">— Loading… —</option>';
            matchSelect.disabled = true;
            resetSheetTypeSelect();
            if (!venueId) {
                matchSelect.innerHTML = '<option value="">— Select Match —</option>';
                return;
            }
            fetch('/drs/venue/' + venueId + '/matches')
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    matchSelect.innerHTML = '<option value="">— Select Match —</option>';
                    data.forEach(function(m) {
                        var date = m.match_date ? new Date(m.match_date).toLocaleDateString('en-GB') :
                            '';
                        var teams = (m.pma1 || m.pma2) ? ' — ' + m.pma1 + ' vs ' + m.pma2 : '';
                        var opt = document.createElement('option');
                        opt.value = m.id;
                        opt.textContent = 'M' + m.match_number + teams + (date ? ' (' + date + ')' :
                        '');
                        matchSelect.appendChild(opt);
                    });
                    matchSelect.disabled = false;
                })
                .catch(function() {
                    matchSelect.innerHTML = '<option value="">— Error loading matches —</option>';
                });
        });

        document.getElementById('match_select').addEventListener('change', function() {
            var matchId = this.value;
            var venueId = document.getElementById('venue_select').value;
            if (!matchId) {
                resetSheetTypeSelect();
                return;
            }
            loadSheetTypes(venueId, matchId);
        });
    </script>

@endsection

@push('script')
    <script>
        var venueId = '{{ $venueId ?? '' }}';
        var matchId = '{{ $matchId ?? '' }}';
        var sheetType = '{{ $sheetType ?? '' }}';
        var koTime = '{{ $koFormatted ?? '' }}';
        var csrfToken = '{{ csrf_token() }}';

        // ── Bootstrap Table config ──────────────────────────────────────────
        var icons = {
            refresh: 'bx-refresh',
            toggleOn: 'bx-toggle-right',
            toggleOff: 'bx-toggle-left',
            fullscreen: 'bx-fullscreen',
            columns: 'bx-list-ul',
            paginationSwitchDown: 'bx-caret-down',
            paginationSwitchUp: 'bx-caret-up'
        };

        function loadingTemplate() {
            return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical"></i>';
        }

        var paginationHidden = false;

        function queryParams(p) {
            return {
                page: paginationHidden ? 1 : (p.offset / p.limit + 1),
                limit: paginationHidden ? 10000 : p.limit,
                sort: p.sort,
                order: p.order,
                offset: paginationHidden ? 0 : p.offset,
                search: p.search,
                venue_id: venueId,
                match_id: matchId,
                sheet_type: $('#sheet_type_select').val() || sheetType,
            };
        }


        function itemRowStyle(row) {
            var map = {
                red: {
                    classes: 'color-red'
                },
                yellow: {
                    classes: 'color-yellow'
                },
                green: {
                    classes: 'color-green'
                },
            };
            return map[row.row_color] || {};
        }

        function itemActionsFormatter(value, row) {
            return '<button type="button" class="btn btn-sm btn-phoenix-warning me-1 item-edit" data-id="' + row.id +
                '" title="Edit"><i class="fa-solid fa-pen"></i></button>' +
                '<button type="button" class="btn btn-sm btn-phoenix-danger item-delete" data-id="' + row.id +
                '" title="Delete"><i class="fa-solid fa-trash"></i></button>';
        }

        // ── KO offset helpers ───────────────────────────────────────────────
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
            var sign = m[1] === '+' ? 1 : -1;
            var hours = m[2] ? parseInt(m[2], 10) : 0;
            var mins = m[3] ? parseInt(m[3], 10) : 0;
            var offset = sign * (hours * 60 + mins);
            var kp = koTime.split(':');
            var koMins = parseInt(kp[0], 10) * 60 + parseInt(kp[1], 10);
            var result = ((koMins + offset) % 1440 + 1440) % 1440;
            return String(Math.floor(result / 60)).padStart(2, '0') + ':' + String(result % 60).padStart(2, '0');
        }

        function calcKoOffset(timeStr) {
            if (!koTime || !timeStr) return '';
            var kp = koTime.split(':');
            var koMins = parseInt(kp[0], 10) * 60 + parseInt(kp[1], 10);
            var tp = timeStr.split(':');
            var tMins = parseInt(tp[0], 10) * 60 + parseInt(tp[1], 10);
            var diff = tMins - koMins;
            if (diff === 0) return 'KO';
            var sign = diff > 0 ? '+' : '-';
            var abs = Math.abs(diff);
            var label = 'KO' + sign;
            if (Math.floor(abs / 60) > 0) label += Math.floor(abs / 60) + 'h';
            if (abs % 60 > 0) label += (abs % 60) + 'm';
            return label;
        }

        var colorStyles = {
            'default': {
                bg: '#ffffff',
                text: '#000000'
            },
            'red': {
                bg: '#ff0000',
                text: '#ffffff'
            },
            'yellow': {
                bg: '#ffff00',
                text: '#000000'
            },
            'green': {
                bg: '#00b050',
                text: '#ffffff'
            },
        };

        $(function() {

            // ── Add modal ───────────────────────────────────────────────────
            function updateAddPreview() {
                var s = colorStyles[$('#add_row_color_select').val()] || colorStyles['default'];
                $('#add_color_preview').css({
                    'background-color': s.bg,
                    'color': s.text
                });
            }
            $('#add_row_color_select').on('change', updateAddPreview);
            updateAddPreview();

            $('#add_start_time').on('change', function() {
                $('#add_countdown_to_ko').val(calcKoOffset($(this).val()));
            });
            $('#add_countdown_to_ko').on('blur', function() {
                var t = parseKoOffset($(this).val());
                if (t) {
                    $('#add_start_time').val(t);
                    $(this).val(calcKoOffset(t)).removeClass('is-invalid');
                } else if ($(this).val().trim()) {
                    $(this).addClass('is-invalid');
                }
            }).on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $(this).trigger('blur');
                }
            });

            $('#add_item_modal').on('hidden.bs.modal', function() {
                $('#add_item_form')[0].reset();
                $('#add_item_form').removeClass('was-validated');
                $('#add_countdown_to_ko').removeClass('is-invalid');
                updateAddPreview();
            });

            $('#add_item_form').on('submit', function(e) {
                e.preventDefault();
                if (!this.checkValidity()) {
                    $(this).addClass('was-validated');
                    return;
                }
                var $btn = $('#add_item_btn'),
                    orig = $btn.html();
                $btn.html('<i class="bx bx-loader-alt bx-spin me-1"></i>Adding…').prop('disabled', true);
                $.ajax({
                    url: '{{ route('drs.drs.item.store') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(res) {
                        if (res.error) {
                            toastr.error(res.message);
                            $btn.html(orig).prop('disabled', false);
                            return;
                        }
                        toastr.success(res.message);
                        $('#add_item_modal').modal('hide');
                        $('#flat_items_table').bootstrapTable('refresh');
                    },
                    error: function(xhr) {
                        var msg = 'An error occurred.';
                        try {
                            msg = JSON.parse(xhr.responseText).message || msg;
                        } catch (ex) {}
                        toastr.error(msg);
                        $btn.html(orig).prop('disabled', false);
                    },
                    complete: function() {
                        $btn.html(orig).prop('disabled', false);
                    }
                });
            });

            // ── Edit modal ──────────────────────────────────────────────────
            function updateEditPreview() {
                var s = colorStyles[$('#edit_row_color_select').val()] || colorStyles['default'];
                $('#edit_color_preview').css({
                    'background-color': s.bg,
                    'color': s.text
                });
            }
            $('#edit_row_color_select').on('change', updateEditPreview);

            $('#edit_item_start_time').on('change', function() {
                $('#edit_countdown_to_ko').val(calcKoOffset($(this).val()));
            });
            $('#edit_countdown_to_ko').on('blur', function() {
                var t = parseKoOffset($(this).val());
                if (t) {
                    $('#edit_item_start_time').val(t);
                    $(this).val(calcKoOffset(t)).removeClass('is-invalid');
                } else if ($(this).val().trim()) {
                    $(this).addClass('is-invalid');
                }
            }).on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $(this).trigger('blur');
                }
            });

            $('#edit_item_modal').on('hidden.bs.modal', function() {
                $('#edit_item_form').removeClass('was-validated');
                $('#edit_countdown_to_ko').removeClass('is-invalid');
            });

            $('body').on('click', '.item-edit', function() {
                var id = $(this).data('id'),
                    $btn = $(this),
                    orig = $btn.html();
                $btn.html('<i class="bx bx-loader-alt bx-spin"></i>').prop('disabled', true);
                $.ajax({
                    url: '/drs/drs/items/' + id + '/get',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#edit_item_id').val(data.id);
                        $('#edit_item_title').val(data.title);
                        $('#edit_item_start_time').val(data.start_time);
                        $('#edit_countdown_to_ko').val(calcKoOffset(data.start_time))
                            .removeClass('is-invalid');
                        $('#edit_item_end_time').val(data.end_time);
                        $('#edit_item_location').val(data.location);
                        $('#edit_item_description').val(data.description);
                        $('#edit_row_color_select').val(data.row_color);
                        $('#edit_item_sort_order').val(data.sort_order);
                        $('#edit_item_form').removeClass('was-validated');
                        updateEditPreview();
                        $('#edit_item_modal').modal('show');
                    },
                    error: function() {
                        toastr.error('Could not load item data.');
                    },
                    complete: function() {
                        $btn.html(orig).prop('disabled', false);
                    }
                });
            });

            $('#edit_item_form').on('submit', function(e) {
                e.preventDefault();
                if (!this.checkValidity()) {
                    $(this).addClass('was-validated');
                    return;
                }
                var $btn = $('#edit_item_btn'),
                    orig = $btn.html();
                $btn.html('<i class="bx bx-loader-alt bx-spin me-1"></i>Saving…').prop('disabled', true);
                $.ajax({
                    url: '{{ route('drs.drs.item.update') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(res) {
                        if (res.error) {
                            toastr.error(res.message);
                            $btn.html(orig).prop('disabled', false);
                            return;
                        }
                        toastr.success(res.message);
                        $('#edit_item_modal').modal('hide');
                        $('#flat_items_table').bootstrapTable('refresh');
                    },
                    error: function(xhr) {
                        var msg = 'An error occurred.';
                        try {
                            msg = JSON.parse(xhr.responseText).message || msg;
                        } catch (ex) {}
                        toastr.error(msg);
                    },
                    complete: function() {
                        $btn.html(orig).prop('disabled', false);
                    }
                });
            });

            // ── Pagination switch: fetch all rows when pagination is hidden ──
            $('#flat_items_table').on('toggle-pagination.bs.table', function(e, hasPagination) {
                paginationHidden = !hasPagination;
                $(this).bootstrapTable('refresh');
            });

            // ── Delete ──────────────────────────────────────────────────────
            $('body').on('click', '.item-delete', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Delete this item?',
                    text: 'This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete',
                }).then(function(result) {
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
                        success: function(res) {
                            toastr.success(res.message || 'Item deleted.');
                            $('#flat_items_table').bootstrapTable('refresh');
                        },
                        error: function() {
                            toastr.error('Could not delete item.');
                        }
                    });
                });
            });

        });
    </script>
@endpush
