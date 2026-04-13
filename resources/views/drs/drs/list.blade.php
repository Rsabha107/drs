@extends(auth()->user()->hasRole('SuperAdmin') ? 'drs.layout.admin_template' : 'drs.customer.layout.template')
@section('main')
    <div class="d-flex justify-content-between align-items-center m-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Daily Run Sheets</li>
            </ol>
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
        </div>
    </div>

    <div class="card mx-2">
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
                            @foreach (['MD-3', 'MD-2', 'MD-1', 'MD', 'MD FINAL', 'MD+1'] as $t)
                                <option>{{ $t }}</option>
                            @endforeach
                        </select>
                        <select id="filter_functional_area" class="form-select form-select-sm" style="width:160px;">
                            <option value="">All Func. Areas</option>
                            @foreach ($functionalAreas ?? [] as $fa)
                                <option value="{{ $fa->id }}">{{ $fa->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table id="drs_table" data-toggle="table" data-toolbar="#toolbar"
                    data-classes="table table-hover fs-9 mb-0 border-top border-translucent"
                    data-loading-template="loadingTemplate" data-url="{{ route('drs.drs.list') }}" data-icons-prefix="bx"
                    data-icons="icons" data-show-refresh="true" data-show-columns="true" data-show-toggle="true"
                    data-total-field="total" data-data-field="rows" data-page-list="[10, 20, 50, 100]" data-search="true"
                    data-side-pagination="server" data-pagination="true" data-sort-name="run_date" data-sort-order="desc"
                    data-trim-on-search="false" data-mobile-responsive="true" data-buttons-class="secondary"
                    data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-visible="false">ID</th>
                            <th data-field="sheet_type" data-sortable="true">Sheet Type</th>
                            <th data-field="match" data-sortable="false">Match#</th>
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
            return [
                '<a href="/drs/drs/' + row.id + '" class="btn btn-sm btn-phoenix-secondary me-1" title="View">',
                '<i class="fa-solid fa-eye"></i>',
                '</a>',
                '<button class="btn btn-sm btn-phoenix-warning me-1 drs-edit" data-id="' + row.id + '" title="Edit">',
                '<i class="fa-solid fa-pen"></i>',
                '</button>',
                '<button class="btn btn-sm btn-phoenix-danger drs-delete" data-id="' + row.id + '" title="Delete">',
                '<i class="fa-solid fa-trash"></i>',
                '</button>',
            ].join('');
        }

        $(document).ready(function() {

            // ── Filters ────────────────────────────────────────────────
            $('#filter_venue, #filter_type, #filter_functional_area').on('change', function() {
                $('#drs_table').bootstrapTable('refresh');
            });

            // ── Teams helper ───────────────────────────────────────────
            function fillTeams($form, pma1, pma2) {
                var teamsInput = $form.find('[id$="_drs_teams"]');
                if (pma1 || pma2) {
                    teamsInput.val((pma1 || '?') + ' vs ' + (pma2 || '?'));
                } else {
                    teamsInput.val('');
                }
            }

            // ── Dependent match select ──────────────────────────────────
            function loadDrsMatches($venueSelect, $matchSelect, selectedMatchId) {
                var venueId = $venueSelect.val();
                $matchSelect.empty().append('<option value="">N/A</option>').prop('disabled', true);

                if (!venueId) {
                    $matchSelect.append('<option value="" disabled selected>— select a venue first —</option>');
                    return;
                }

                $matchSelect.append('<option value="" disabled selected>Loading…</option>');

                $.ajax({
                    url: '/drs/venue/' + venueId + '/matches',
                    type: 'GET',
                    dataType: 'json',
                    success: function(matches) {
                        $matchSelect.empty().append(
                            '<option value="" data-date="" data-pma1="" data-pma2="">N/A</option>');
                        $.each(matches, function(i, m) {
                            var label = 'M' + m.match_number + ' — ' + m.match_date;
                            var selected = (selectedMatchId && m.id == selectedMatchId) ?
                                ' selected' : '';
                            $matchSelect.append(
                                '<option value="' + m.id + '"' +
                                ' data-date="' + m.match_date + '"' +
                                ' data-pma1="' + (m.pma1 || '') + '"' +
                                ' data-pma2="' + (m.pma2 || '') + '"' +
                                selected + '>' + label + '</option>'
                            );
                        });
                        $matchSelect.prop('disabled', false);
                        // If a match was preselected, fill date and teams immediately
                        if (selectedMatchId) {
                            var $selected = $matchSelect.find('option:selected');
                            var $form = $matchSelect.closest('form');
                            var date = $selected.data('date');
                            if (date) $form.find('[name="run_date"]').val(date);
                            fillTeams($form, $selected.data('pma1'), $selected.data('pma2'));
                        }
                    },
                    error: function() {
                        $matchSelect.empty().append(
                            '<option value="">— could not load matches —</option>');
                    }
                });
            }

            // Match change → fill date + teams
            $('body').on('change', '#create_drs_match_id, #edit_drs_match_id', function() {
                var $opt = $(this).find('option:selected');
                var $form = $(this).closest('form');
                var date = $opt.data('date');
                if (date) $form.find('[name="run_date"]').val(date);
                fillTeams($form, $opt.data('pma1'), $opt.data('pma2'));
            });

            // Venue change in create modal
            $('#create_drs_venue_id').on('change', function() {
                fillTeams($('#create_drs_form'), '', '');
                loadDrsMatches($(this), $('#create_drs_match_id'), null);
            });

            // Venue change in edit modal
            $('#edit_drs_venue_id').on('change', function() {
                fillTeams($('#edit_drs_form'), '', '');
                loadDrsMatches($(this), $('#edit_drs_match_id'), null);
            });

            // Reset match select when create modal closes
            $('#create_drs_modal').on('hidden.bs.modal', function() {
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
                        $('#edit_drs_form [name="sheet_type"]').val(data.sheet_type);
                        $('#edit_drs_form [name="run_date"]').val(data.run_date);
                        $('#edit_drs_form [name="gates_opening"]').val(data.gates_opening);
                        $('#edit_drs_form [name="kick_off"]').val(data.kick_off);
                        $('#edit_drs_form').removeClass('was-validated');

                        // Set venue then load dependent matches with current match preselected
                        $('#edit_drs_venue_id').val(data.venue_id);
                        loadDrsMatches($('#edit_drs_venue_id'), $('#edit_drs_match_id'), data
                            .match_id);

                        // Set functional area
                        $('#edit_drs_functional_area_id').val(data.functional_area_id || '');

                        $('#edit_drs_modal').modal('show');
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
                $('#create_drs_form')[0].reset();
                $('#create_drs_form').removeClass('was-validated');
            });
            $('#edit_drs_modal').on('hidden.bs.modal', function() {
                $('#edit_drs_form').removeClass('was-validated');
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

                drsAjaxSubmit(
                    '{{ route('drs.drs.update') }}',
                    $(this).serialize(),
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
