@extends('drs.layout.admin_template')
@section('main')

    <style>
        .venue-tab-pane .match-card {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 1.25rem;
        }

        .match-header {
            background-color: #1a3a5c;
            color: #fff;
            padding: 10px 16px;
            border-radius: 6px 6px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .match-header h6 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .match-meta {
            font-size: 0.8rem;
            opacity: 0.85;
        }

        .fa-section-header {
            background-color: #305496;
            color: #fff;
            padding: 7px 14px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .fa-section-header .sheet-meta {
            font-size: 0.78rem;
            font-weight: 400;
            opacity: 0.9;
        }

        .run-sheet-table thead th {
            background-color: #4472c4;
            color: #fff;
            font-size: 0.78rem;
            padding: 6px 8px;
            white-space: nowrap;
        }

        .run-sheet-table td {
            font-size: 0.78rem;
            padding: 5px 8px;
            vertical-align: middle;
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

        .nav-tabs .nav-link.active {
            font-weight: 600;
        }

        .empty-state {
            padding: 24px;
            text-align: center;
            color: #6c757d;
            font-size: 0.88rem;
        }

        @media print {
            .no-print { display: none !important; }
            .card { border: none !important; box-shadow: none !important; }
            .match-card { page-break-inside: avoid; }
        }
    </style>

    {{-- Breadcrumb & actions --}}
    <div class="d-flex justify-content-between align-items-center m-2 mb-3 no-print">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('drs.admin.drs') }}">Daily Run Sheets</a></li>
                <li class="breadcrumb-item active">Venue / Match Overview</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-sm btn-subtle-secondary">
                <i class="fa-solid fa-print me-1"></i>Print
            </button>
        </div>
    </div>

    <div class="card mx-2">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fa-solid fa-table-list me-2 text-primary"></i>
                All Functional Area Items — {{ $event->name ?? 'Current Event' }}
            </h5>
            <span class="badge bg-secondary">{{ $grouped->count() }} venue(s)</span>
        </div>

        <div class="card-body p-3">

            @if ($grouped->isEmpty())
                <div class="empty-state">
                    <i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>
                    No daily run sheets found for this event.
                </div>
            @else

                {{-- Venue tabs --}}
                <ul class="nav nav-tabs mb-3" id="venueTabs" role="tablist">
                    @foreach ($grouped as $venueId => $matchGroups)
                        @php
                            $venue     = $matchGroups->first()->first()->venue;
                            $venueName = $venue->short_name ?? $venue->name ?? 'Venue ' . $venueId;
                            $tabId     = 'venue-' . $venueId;
                        @endphp
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                id="{{ $tabId }}-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#{{ $tabId }}"
                                type="button" role="tab">
                                {{ $venueName }}
                                <span class="badge bg-primary ms-1">{{ $matchGroups->flatten()->count() }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content" id="venueTabContent">
                    @foreach ($grouped as $venueId => $matchGroups)
                        @php $tabId = 'venue-' . $venueId; @endphp
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }} venue-tab-pane"
                            id="{{ $tabId }}" role="tabpanel">

                            @foreach ($matchGroups as $matchId => $sheets)
                                @php
                                    $firstSheet = $sheets->first();
                                    $match      = $firstSheet->match;
                                @endphp

                                <div class="match-card">
                                    {{-- Match header --}}
                                    <div class="match-header">
                                        <div>
                                            <h6 class="text-white mb-1">
                                                @if ($match)
                                                    M{{ $match->match_number }} &mdash;
                                                    {{ $match->pma1 ?? '' }} vs {{ $match->pma2 ?? '' }}
                                                @else
                                                    No Match Assigned
                                                @endif
                                            </h6>
                                            <div class="match-meta">
                                                @if ($match)
                                                    Date: {{ $match->match_date ? \Carbon\Carbon::parse($match->match_date)->format('d/m/Y') : 'N/A' }}
                                                    &nbsp;|&nbsp;
                                                    KO: {{ $firstSheet->kick_off ? \Carbon\Carbon::parse($firstSheet->kick_off)->format('H:i') : 'N/A' }}
                                                    &nbsp;|&nbsp;
                                                    Gates: {{ $firstSheet->gates_opening ? \Carbon\Carbon::parse($firstSheet->gates_opening)->format('H:i') : 'N/A' }}
                                                @endif
                                            </div>
                                        </div>
                                        <span class="badge bg-light text-dark">
                                            {{ $sheets->count() }} functional area(s)
                                        </span>
                                    </div>

                                    {{-- Functional area sections --}}
                                    @foreach ($sheets as $sheet)
                                        @php
                                            $fa          = $sheet->functionalArea;
                                            $faName      = $fa->title ?? $fa->name ?? 'Unnamed Area';
                                            $koFormatted = $sheet->kick_off
                                                ? \Carbon\Carbon::parse($sheet->kick_off)->format('H:i')
                                                : '';
                                        @endphp

                                        <div class="fa-section-header">
                                            <span>
                                                <i class="fa-solid fa-layer-group me-1"></i>
                                                {{ $faName }}
                                            </span>
                                            <span class="sheet-meta">
                                                Type: {{ $sheet->sheetType?->code ?? 'N/A' }}
                                                &nbsp;|&nbsp;
                                                Date: {{ $sheet->run_date_dmy ?? 'N/A' }}
                                                @if ($koFormatted)
                                                    &nbsp;|&nbsp; KO: {{ $koFormatted }}
                                                @endif
                                                &nbsp;|&nbsp;
                                                <a href="{{ route('drs.drs.show', $sheet->id) }}"
                                                    class="text-white text-decoration-underline" target="_blank">
                                                    View Sheet
                                                </a>
                                            </span>
                                        </div>

                                        @if ($sheet->items->isEmpty())
                                            <div class="empty-state py-3">No items in this sheet.</div>
                                        @else
                                            <div class="table-responsive">
                                                <table class="table table-bordered mb-0 run-sheet-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:30px">#</th>
                                                            <th>Title</th>
                                                            <th style="width:80px">Start</th>
                                                            <th style="width:80px">End</th>
                                                            <th style="width:90px">Countdown</th>
                                                            <th>Location</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($sheet->items as $item)
                                                            @php
                                                                // Calculate countdown to KO
                                                                $countdown = '';
                                                                if ($item->start_time && $koFormatted) {
                                                                    $koParts = explode(':', $koFormatted);
                                                                    $koMins  = (int) $koParts[0] * 60 + (int) $koParts[1];
                                                                    $sParts  = explode(':', \Carbon\Carbon::parse($item->start_time)->format('H:i'));
                                                                    $sMins   = (int) $sParts[0] * 60 + (int) $sParts[1];
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
                                                                } elseif ($item->countdown_to_ko) {
                                                                    $countdown = $item->countdown_to_ko;
                                                                }
                                                                $rowClass = $item->row_color && $item->row_color !== 'default'
                                                                    ? 'color-' . $item->row_color
                                                                    : '';
                                                            @endphp
                                                            <tr class="{{ $rowClass }}">
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $item->title }}</td>
                                                                <td>{{ $item->start_time ? \Carbon\Carbon::parse($item->start_time)->format('H:i') : '-' }}</td>
                                                                <td>{{ $item->end_time ? \Carbon\Carbon::parse($item->end_time)->format('H:i') : '-' }}</td>
                                                                <td>{{ $countdown ?: '-' }}</td>
                                                                <td>{{ $item->location ?? '-' }}</td>
                                                                <td>{{ $item->description ?? '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    @endforeach
                                    {{-- end foreach sheets --}}

                                </div>
                                {{-- end .match-card --}}
                            @endforeach
                            {{-- end foreach matchGroups --}}

                        </div>
                        {{-- end .tab-pane --}}
                    @endforeach
                    {{-- end foreach grouped --}}
                </div>
                {{-- end #venueTabContent --}}

            @endif

        </div>
    </div>

@endsection
