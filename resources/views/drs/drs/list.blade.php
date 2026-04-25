@extends(auth()->user()->hasRole('SuperAdmin') ? 'drs.layout.admin_template' : 'drs.customer.layout.template')
@section('main')
    <div class="d-flex justify-content-between align-items-center m-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Daily Run Sheets</li>
            </ol>
            @if (Auth::user()->hasRole('Customer') && $userFas->isNotEmpty())

                @if ($userFas->isNotEmpty())
                    <div class="mt-1 d-flex flex-wrap gap-1">
                        @foreach ($userFas as $fa)
                            <span class="badge bg-primary">{{ $fa->fa_code }} &mdash; {{ $fa->title }}</span>
                        @endforeach
                    </div>
                @endif
            @endif
        </nav>
        <div class="d-flex gap-2 align-items-center">
            {{-- <select id="filter_venue" class="form-select form-select-sm" style="width:160px;">
                <option value="">All Venues</option>
                @foreach ($event->venues as $v)
                    <option value="{{ $v->id }}">{{ $v->short_name }}</option>
                @endforeach
            </select>
            <select id="filter_type" class="form-select form-select-sm" style="width:140px;">
                <option value="">All Types</option>
                @foreach (['MD-3', 'MD-2', 'MD-1', 'MD', 'MD FINAL', 'MD+1'] as $t)
                    <option>{{ $t }}</option>
                @endforeach
            </select>
            <select id="filter_functional_area" class="form-select form-select-sm" style="width:160px;">
                <option value="">All Func. Areas</option>
                @foreach ($functionalAreas ?? [] as $fa)
                    <option value="{{ $fa->id }}">{{ $fa->title }}</option>
                @endforeach
            </select> --}}

            @unless (auth()->user()->hasRole('Customer'))
                <button type="button" class="btn btn-subtle-primary px-3" data-bs-toggle="modal"
                    data-bs-target="#create_drs_modal">
                    <i class="fa-solid fa-plus me-1"></i>New Run Sheet
                </button>
                <a href="{{ route('drs.admin.venue.match') }}" class="btn btn-success">
                    <i class="fa-solid fa-eye me-1"></i>Admin View
                </a>
                <a href="{{ route('drs.admin.flat.list') }}" class="btn btn-success">
                    <i class="fa-solid fa-eye me-1"></i>Combined List View
                </a>
            @endunless
        </div>
    </div>

    <div class="card mx-2 mb-4">
        <div class="card-header">
            <h5 class="mb-0">Daily Run Sheets &mdash; {{ $event->name }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <div id="toolbar">
                    <div class="d-flex gap-2 align-items-center">
                        {{-- <button type="button" class="btn btn-subtle-primary px-3" data-bs-toggle="modal"
                            data-bs-target="#create_drs_modal">
                            <i class="fa-solid fa-plus me-1"></i>New Run Sheet
                        </button> --}}
                        <select id="filter_venue" class="form-select form-select-sm" style="width:160px;">
                            <option value="">All Venues</option>
                            @foreach ($event->venues as $v)
                                <option value="{{ $v->id }}">{{ $v->short_name }}</option>
                            @endforeach
                        </select>
                        <select id="filter_type" class="form-select form-select-sm" style="width:140px;">
                            <option value="">All Types</option>
                            @foreach ($sheetTypes as $t)
                                <option value="{{ $t->id }}">{{ $t->formatted_title }}</option>
                            @endforeach
                        </select>
                        <select id="filter_functional_area" class="form-select form-select-sm" style="width:160px;">
                            <option value="">All Func. Areas</option>
                            @foreach ($userFas->isNotEmpty() ? $userFas : $functionalAreas ?? collect() as $fa)
                                <option value="{{ $fa->id }}">{{ $fa->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table id="drs_table" data-toggle="table" data-toolbar="#toolbar"
                    data-classes="table table-hover fs-9 mb-0 border-top border-translucent"
                    data-loading-template="loadingTemplate" data-url="{{ route('drs.drs.list') }}" data-icons-prefix="bx"
                    data-icons="icons" data-show-refresh="true" data-show-columns="true" data-show-toggle="true"
                    data-icon-size="sm" data-total-field="total" data-data-field="rows" data-page-list="[10, 20, 50, 100]"
                    data-page-size="50" data-search="true" data-side-pagination="server" data-pagination="true"
                    data-sort-name="run_date" data-sort-order="desc" data-trim-on-search="false"
                    data-mobile-responsive="true" data-buttons-class="secondary" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-visible="false">ID</th>
                            <th data-field="sheet_type" data-sortable="true">Sheet Type</th>
                            <th data-field="venue" data-sortable="false">Venue</th>
                            <th data-field="match" data-sortable="false">Match#</th>
                            <th data-field="match_date" data-sortable="false">Match Date</th>
                            <th data-field="md_date" data-sortable="false">MD-x Date</th>
                            <th data-field="teams" data-sortable="false">Teams</th>
                            <th data-field="functional_area" data-sortable="false">Func. Area</th>
                            <th data-field="run_date" data-sortable="true">Date</th>
                            <th data-field="gates_opening" data-sortable="true">Gates Opening</th>
                            <th data-field="kick_off" data-sortable="true">Kick-Off</th>
                            <th data-field="items_count" data-sortable="false">Items</th>
                            <th data-formatter="drsActionsFormatter" class="text-end">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
     Create Run Sheet Modal
═══════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="create_drs_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-100">
                <div class="modal-header bg-modal-header">
                    <h3 class="mb-0 text-white">New Daily Run Sheet</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="create_drs_form" novalidate class="needs-validation">
                    @csrf
                    <div class="modal-body">
                        @include('drs.drs._sheet_fields', ['prefix' => 'create'])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="create_drs_btn">
                            <i class="fa-solid fa-save me-1"></i>Create &amp; Add Items
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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
        var icons = {
            refresh: 'bx-refresh',
            toggleOn: 'bx-toggle-right',
            toggleOff: 'bx-toggle-left',
            fullscreen: 'bx-fullscreen',
            columns: 'bx-list-ul',
        };
        
        // User role for permission checks
        var userRole = '{{ auth()->user()->roles()->first()?->name ?? "Guest" }}';
        var canEditDeleteDrs = {{ auth()->user()->hasRole('SuperAdmin') ? 'true' : 'false' }};

        function loadingTemplate() {
            return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical"></i>';
        }

        function queryParams(p) {
            return {
                page: p.offset / p.limit + 1,
                limit: p.limit,
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                search: p.search,
                venue_id: $('#filter_venue').val(),
                sheet_type: $('#filter_type').val(),
                functional_area_id: $('#filter_functional_area').val(),
            };
        }

        function drsActionsFormatter(value, row) {
            var actions = [];
            
            if (canEditDeleteDrs) {
                // SuperAdmin: View, Edit, Delete
                actions.push(
                    '<a href="/drs/drs/' + row.id + '" class="btn btn-sm btn-phoenix-secondary me-1" title="View">',
                    '<i class="fa-solid fa-eye"></i>',
                    '</a>',
                    '<button class="btn btn-sm btn-phoenix-warning me-1 drs-edit" data-id="' + row.id + '" title="Edit">',
                    '<i class="fa-solid fa-pen"></i>',
                    '</button>',
                    '<button class="btn btn-sm btn-phoenix-danger drs-delete" data-id="' + row.id + '" title="Delete">',
                    '<i class="fa-solid fa-trash"></i>',
                    '</button>'
                );
            } else {
                // Customer: Add Items button
                actions.push(
                    '<a href="/drs/drs/' + row.id + '" class="btn btn-sm btn-phoenix-primary" title="Add Items">',
                    '<i class="fa-solid fa-plus me-1"></i>Add Items',
                    '</a>'
                );
            }
            
            return actions.join('');
        }

        $(document).ready(function() {

            // ── Filters ────────────────────────────────────────────────
            $('#filter_venue, #filter_type, #filter_functional_area').on('change', function() {
                $('#drs_table').bootstrapTable('refresh');
            });

            // ── Teams helper ───────────────────────────────────────────
            function fillTeams($form, pma1, pma2) {
                var teamsInput = $form.find('[id$="_drs_teams"]');
                // Temporarily enable and remove readonly to set value
                teamsInput.prop('disabled', false).prop('readonly', false);
                if (pma1 || pma2) {
                    teamsInput.val((pma1 || '?') + ' vs ' + (pma2 || '?'));
                } else {
                    teamsInput.val('');
                }
                // Re-disable and set readonly
                teamsInput.prop('disabled', true).prop('readonly', true);
            }

            // ── Helpers ────────────────────────────────────────────────
            function clearMatchSelect($matchSelect, $form) {
                $matchSelect.empty()
                    .append('<option value="">N/A</option>')
                    .prop('disabled', true);
                setMatchDependents($form, true);
                fillTeams($form, '', '');
                $form.find('[name="gates_opening"], [name="kick_off"]').val('');
            }

            // ── Dependent match select ──────────────────────────────────
            function setMatchDependents($form, disabled) {
                $form.find('[name="gates_opening"], [name="kick_off"]').prop('disabled', disabled);
                var $teams = $form.find('[id$="_drs_teams"]');
                $teams.prop('disabled', disabled).prop('readonly', disabled);
            }

            function loadDrsMatches($venueSelect, $matchSelect, selectedMatchId, onComplete) {
                var venueId = $venueSelect.val();
                var $form = $matchSelect.closest('form');
                var dfd = $.Deferred();

                $matchSelect.empty().append('<option value="">N/A</option>').prop('disabled', true);
                setMatchDependents($form, true);

                if (!venueId) {
                    $matchSelect.append('<option value="" disabled selected>— select a venue first —</option>');
                    dfd.resolve();
                    return dfd.promise();
                }

                $matchSelect.append('<option value="" disabled selected>Loading…</option>');

                $.ajax({
                    url: '/drs/venue/' + venueId + '/matches',
                    type: 'GET',
                    dataType: 'json',
                    success: function(matches) {
                        populateMatchSelect($matchSelect, $form, matches, selectedMatchId, onComplete);
                        dfd.resolve();
                    },
                    error: function() {
                        $matchSelect.empty().append(
                            '<option value="">— could not load matches —</option>');
                        dfd.reject();
                    }
                });

                return dfd.promise();
            }

            // Load matches filtered by sheet type (shows only matches for that sheet type)
            function loadDrsMatchesBySheetType($venueSelect, $matchSelect, $sheetTypeSelect, selectedMatchId, onComplete) {
                var venueId = $venueSelect.val();
                var sheetTypeId = $sheetTypeSelect.val();
                var $form = $matchSelect.closest('form');
                var dfd = $.Deferred();

                $matchSelect.empty().append('<option value="">N/A</option>').prop('disabled', true);
                setMatchDependents($form, true);

                if (!venueId || !sheetTypeId) {
                    $matchSelect.append('<option value="" disabled selected>— select venue and sheet type first —</option>');
                    dfd.resolve();
                    return dfd.promise();
                }

                $matchSelect.append('<option value="" disabled selected>Loading…</option>');

                $.ajax({
                    url: '/drs/sheet-type/matches',
                    type: 'GET',
                    dataType: 'json',
                    data: { sheet_type_id: sheetTypeId, venue_id: venueId },
                    success: function(matches) {
                        var sheetTypeCode = $sheetTypeSelect.find('option:selected').data('code');
                        populateMatchSelect($matchSelect, $form, matches, selectedMatchId, onComplete, sheetTypeCode);
                        dfd.resolve();
                    },
                    error: function() {
                        $matchSelect.empty().append(
                            '<option value="">— could not load matches —</option>');
                        dfd.reject();
                    }
                });

                return dfd.promise();
            }

            // Helper function to populate match select options
            function populateMatchSelect($matchSelect, $form, matches, selectedMatchId, onComplete, sheetTypeCode) {
                $matchSelect.empty().append(
                    '<option value="" data-date="" data-pma1="" data-pma2="" data-gates-opening="" data-kick-off="">N/A</option>'
                    );
                $.each(matches, function(i, m) {
                    var label = 'M' + m.match_number + ' — ' + m.match_date;
                    var selected = (selectedMatchId && m.id == selectedMatchId) ?
                        ' selected' : '';
                    $matchSelect.append(
                        '<option value="' + m.id + '"' +
                        ' data-date="' + m.match_date + '"' +
                        ' data-pma1="' + (m.pma1 || '') + '"' +
                        ' data-pma2="' + (m.pma2 || '') + '"' +
                        ' data-gates-opening="' + (m.gates_opening || '') + '"' +
                        ' data-kick-off="' + (m.kick_off || '') + '"' +
                        selected + '>' + label + '</option>'
                    );
                });

                // Enable match and dependent fields
                $matchSelect.prop('disabled', false);
                setMatchDependents($form, false);

                // If a match was preselected, fill date and teams immediately
                if (selectedMatchId) {
                    var $selected = $matchSelect.find('option:selected');
                    var date = $selected.data('date');
                    if (date) $form.find('[name="run_date"]').val(date);
                    fillTeams($form, $selected.data('pma1'), $selected.data('pma2'));
                    
                    // Fill gates opening and kickoff only for MD sheet types
                    if (sheetTypeCode === 'MD') {
                        var gatesOpening = $selected.data('gates-opening');
                        var kickOff = $selected.data('kick-off');
                        if (gatesOpening) $form.find('[name="gates_opening"]').val(gatesOpening);
                        if (kickOff) $form.find('[name="kick_off"]').val(kickOff);
                    }
                }

                // Call completion callback if provided
                if (typeof onComplete === 'function') {
                    onComplete();
                }
            }

            // ── Load Sheet Types by Venue ───────────────────────────────
            function loadSheetTypes($venueSelect, $sheetTypeSelect) {
                var venueId = $venueSelect.val();
                var dfd = $.Deferred();

                $sheetTypeSelect.empty().append('<option value="">Loading…</option>').prop('disabled', true);

                if (!venueId) {
                    $sheetTypeSelect.empty().append('<option value="">Select venue first</option>').prop('disabled', true);
                    dfd.resolve();
                    return dfd.promise();
                }

                $.ajax({
                    url: '{{ route("drs.drs.get-sheet-types") }}',
                    type: 'GET',
                    dataType: 'json',
                    data: { venue_id: venueId },
                    headers: { "X-CSRF-TOKEN": $('input[name="_token"]').val() },
                    success: function(response) {
                        $sheetTypeSelect.empty();
                        
                        if (response.types && response.types.length > 0) {
                            $sheetTypeSelect.append('<option value="">Select type</option>');
                            $.each(response.types, function(i, type) {
                                $sheetTypeSelect.append(
                                    '<option value="' + type.id + '" data-code="' + type.code + '" data-title="' + type.title + '">' + type.title + '</option>'
                                );
                            });
                            $sheetTypeSelect.prop('disabled', false);
                        } else {
                            $sheetTypeSelect.append('<option value="">No types available</option>').prop('disabled', true);
                        }
                        dfd.resolve();
                    },
                    error: function() {
                        $sheetTypeSelect.empty().append('<option value="">— could not load types —</option>').prop('disabled', true);
                        dfd.reject();
                    }
                });

                return dfd.promise();
            }

            // Match change → fill date + teams, or clear times when N/A
            $('body').on('change', '#create_drs_match_id, #edit_drs_match_id', function() {
                var $opt = $(this).find('option:selected');
                var $form = $(this).closest('form');
                var date = $opt.data('date');
                var gatesOpening = $opt.data('gates-opening');
                var kickOff = $opt.data('kick-off');
                var $sheetTypeSelect = $form.find('[name="sheet_type"]');
                var sheetTypeCode = $sheetTypeSelect.find('option:selected').data('code');
                
                if (date) $form.find('[name="run_date"]').val(date);
                
                // Only populate gates opening and kick-off for MD sheet types
                if (sheetTypeCode === 'MD') {
                    $form.find('[name="gates_opening"]').val(gatesOpening || '');
                    $form.find('[name="kick_off"]').val(kickOff || '');
                }
                
                fillTeams($form, $opt.data('pma1'), $opt.data('pma2'));
            });

            // Venue change in create modal
            $('#create_drs_venue_id').on('change', function() {
                var $form = $('#create_drs_form');
                var $sheetTypeSelect = $('#create_drs_sheet_type');
                var $matchSelect = $('#create_drs_match_id');
                
                // Load sheet types for this venue and clear dependent fields
                loadSheetTypes($(this), $sheetTypeSelect);
                clearMatchSelect($matchSelect, $form);
                $sheetTypeSelect.val(''); // Clear sheet type selection
            });

            // Venue change in edit modal
            $('#edit_drs_venue_id').on('change', function() {
                var $form = $('#edit_drs_form');
                var $sheetTypeSelect = $('#edit_drs_sheet_type');
                var $matchSelect = $('#edit_drs_match_id');
                
                // Load sheet types for this venue and clear dependent fields
                loadSheetTypes($(this), $sheetTypeSelect);
                clearMatchSelect($matchSelect, $form);
                $sheetTypeSelect.val(''); // Clear sheet type selection
            });

            // ── Sheet type change: Auto-populate date and teams ───────────────────
            function onSheetTypeChange($sheetTypeSelect, $form) {
                var $opt = $sheetTypeSelect.find('option:selected');
                var sheetTypeCode = $opt.data('code');
                var sheetTypeTitle = $opt.data('title');
                var $runDateInput = $form.find('[name="run_date"]');
                var $matchSelect = $form.find('[name="match_id"]');
                var $venueSelect = $form.find('[name="venue_id"]');
                var $faHint = $form.find('[data-hint]'); // Get hint if it exists

                // Extract date from title (e.g., "27/03/2026 - MD-3" → "27/03/2026")
                if (sheetTypeTitle && sheetTypeTitle.includes(' - ')) {
                    var mdDate = sheetTypeTitle.split(' - ')[0].trim();
                    // Convert d/m/Y to Y-m-d for the date input
                    var parts = mdDate.split('/');
                    if (parts.length === 3) {
                        var isoDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                        $runDateInput.val(isoDate);
                    }
                }

                // Show/hide FA hint based on sheet type
                var formPrefix = $form.attr('id').replace('_form', '');
                if (sheetTypeCode === 'MD') {
                    $('#' + formPrefix + '_drs_fa_hint').removeClass('d-none');
                } else {
                    $('#' + formPrefix + '_drs_fa_hint').addClass('d-none');
                }

                // Load matches filtered by this sheet type and auto-select first match
                loadDrsMatchesBySheetType($venueSelect, $matchSelect, $sheetTypeSelect, null, function() {
                    // After matches load, auto-select the first non-NA match
                    var $firstMatch = $matchSelect.find('option:not([value=""])').first();
                    if ($firstMatch.length) {
                        $matchSelect.val($firstMatch.val());
                        // Trigger change to populate match date, teams, gates opening, kickoff
                        $matchSelect.trigger('change');
                    }
                });
            }

            // Sheet type change in create modal
            $('#create_drs_sheet_type').on('change', function() {
                var $form = $('#create_drs_form');
                onSheetTypeChange($(this), $form);
            });

            // Sheet type change in edit modal
            $('#edit_drs_sheet_type').on('change', function() {
                var $form = $('#edit_drs_form');
                onSheetTypeChange($(this), $form);
            });

            // Reset match select when create modal closes
            $('#create_drs_modal').on('hidden.bs.modal', function() {
                $('#create_drs_sheet_type').empty()
                    .append('<option value="">Select venue first</option>')
                    .prop('disabled', true);
                $('#create_drs_match_id').empty()
                    .append('<option value="">— select a venue first —</option>')
                    .prop('disabled', true);
            });

            // ── Delete ─────────────────────────────────────────────────
            $('body').on('click', '.drs-delete', function() {
                console.log('Delete button clicked');
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Delete this run sheet?',
                    text: 'All items will also be deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/drs/drs/destroy/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                console.log('Delete successful');
                                toastr.success('Run sheet deleted.');
                                $('#drs_table').bootstrapTable('refresh');
                            },
                            error: function() {
                                console.log('Delete failed');
                                toastr.error('Could not delete. Please try again.');
                            }
                        });
                    }
                });
            });

            // ── Open edit modal & populate ──────────────────────────────
            $('body').on('click', '.drs-edit', function() {
                var id = $(this).data('id');
                var $btn = $(this);
                $btn.html('<i class="bx bx-loader-alt bx-spin"></i>').prop('disabled', true);

                $.ajax({
                    url: '/drs/drs/' + id + '/get',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#edit_drs_id').val(data.id);
                        $('#edit_drs_form [name="run_date"]').val(data.run_date);
                        $('#edit_drs_form [name="gates_opening"]').val(data.gates_opening);
                        $('#edit_drs_form [name="kick_off"]').val(data.kick_off);
                        $('#edit_drs_form').removeClass('was-validated');

                        // Set functional area
                        $('#edit_drs_functional_area_id').val(data.functional_area_id || '');

                        // Set venue and load sheet types, then populate sheet_type value
                        var $venueSelect = $('#edit_drs_venue_id');
                        var $sheetTypeSelect = $('#edit_drs_sheet_type');
                        var $matchSelect = $('#edit_drs_match_id');
                        
                        $venueSelect.val(data.venue_id);
                        
                        // Load sheet types for this venue
                        loadSheetTypes($venueSelect, $sheetTypeSelect).done(function() {
                            // After sheet types load, populate the sheet type value
                            $sheetTypeSelect.val(data.sheet_type);
                            var $option = $sheetTypeSelect.find('option:selected');
                            var sheetTypeCode = $option.data('code');
                            
                            // Load matches for both MD and non-MD types
                            loadDrsMatchesBySheetType($venueSelect, $matchSelect, $sheetTypeSelect, data.match_id)
                                .always(function() {
                                    $('#edit_drs_modal').modal('show');
                                });
                        });
                    },
                    error: function() {
                        toastr.error('Could not load run sheet data.');
                    },
                    complete: function() {
                        $btn.html('<i class="fa-solid fa-pen"></i>').prop('disabled', false);
                    }
                });
            });

            // ── Reset modals on close ───────────────────────────────────
            $('#create_drs_modal').on('hidden.bs.modal', function() {
                $('#create_drs_sheet_type').empty()
                    .append('<option value="">Select venue first</option>')
                    .prop('disabled', true);
                $('#create_drs_form')[0].reset();
                $('#create_drs_form').removeClass('was-validated');
                $('#create_drs_fa_hint').addClass('d-none');
            });
            $('#edit_drs_modal').on('hidden.bs.modal', function() {
                $('#edit_drs_sheet_type').empty()
                    .append('<option value="">Select venue first</option>')
                    .prop('disabled', true);
                $('#edit_drs_form').removeClass('was-validated');
                $('#edit_drs_fa_hint').addClass('d-none');
            });

            // ── Create submit ───────────────────────────────────────────
            $('#create_drs_form').on('submit', function(e) {
                e.preventDefault();
                if (!this.checkValidity()) {
                    $(this).addClass('was-validated');
                    return;
                }

                drsAjaxSubmit(
                    '{{ route('drs.drs.store') }}',
                    $(this).serialize(),
                    '#create_drs_btn',
                    '<i class="fa-solid fa-save me-1"></i>Create &amp; Add Items',
                    function(res) {
                        toastr.success(res.message);
                        window.location.href = res.redirect;
                    }
                );
            });

            // ── Edit submit ─────────────────────────────────────────────
            $('#edit_drs_form').on('submit', function(e) {
                e.preventDefault();
                if (!this.checkValidity()) {
                    $(this).addClass('was-validated');
                    return;
                }

                // Disabled fields (e.g. match_id while it's loading) are excluded
                // from .serialize(), so temporarily enable them to capture their values.
                var $form = $(this);
                var $disabledFields = $form.find(':disabled').prop('disabled', false);
                var formData = $form.serialize();
                $disabledFields.prop('disabled', true);

                drsAjaxSubmit(
                    '{{ route('drs.drs.update') }}',
                    formData,
                    '#edit_drs_btn',
                    '<i class="fa-solid fa-save me-1"></i>Save Changes',
                    function(res) {
                        toastr.success(res.message);
                        $('#edit_drs_modal').modal('hide');
                        $('#drs_table').bootstrapTable('refresh');
                        $('#edit_drs_btn').html('<i class="fa-solid fa-save me-1"></i>Save Changes')
                            .prop('disabled', false);
                    }
                );
            });

        });

        function drsAjaxSubmit(url, data, btnSelector, originalHtml, onSuccess) {
            var $btn = $(btnSelector);
            $btn.html('<i class="bx bx-loader-alt bx-spin me-1"></i>Saving…').prop('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(res) {
                    if (res.error) {
                        toastr.error(res.message);
                        $btn.html(originalHtml).prop('disabled', false);
                    } else {
                        onSuccess(res);
                    }
                },
                error: function(xhr) {
                    var msg = 'An error occurred.';
                    try {
                        msg = JSON.parse(xhr.responseText).message || msg;
                    } catch (ex) {}
                    toastr.error(msg);
                    $btn.html(originalHtml).prop('disabled', false);
                }
            });
        }
    </script>
@endpush
