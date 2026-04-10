<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        /* ===== Page setup like your PDF ===== */
        @page {
            margin: 40mm 12mm 18mm 12mm;
            /* top right bottom left */
        }

        header {
            position: fixed;
            top: -30mm;
            /* put header inside the reserved top margin */
            left: 0;
            right: 0;
            height: 28mm;
        }

        footer {
            position: fixed;
            bottom: -12mm;
            /* put footer inside the reserved bottom margin */
            left: 0;
            right: 0;
            height: 10mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111;
        }

        /* Header + footer fixed
        header {
            position: fixed;
            top: -10mm;
            left: 0;
            right: 0;
            height: 26mm;
        }

        footer {
            position: fixed;
            bottom: -10mm;
            left: 0;
            right: 0;
            height: 12mm;
        } */

        .topline {
            font-size: 10px;
            color: #333;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .banner {
            width: 100%;
            border-collapse: collapse;
        }

        .banner td {
            padding: 0;
            border: 0;
            vertical-align: middle;
        }

        .banner-img {
            width: 100%;
            height: 62px;
            object-fit: cover;
            display: block;
        }

        h1 {
            font-size: 15px;
            margin: 6px 0 6px;
        }

        h2 {
            font-size: 12px;
            margin: 10px 0 6px;
        }

        .hash {
            font-weight: 700;
        }

        /* tables */
        table.tbl {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0 10px;
        }

        .tbl th,
        .tbl td {
            border: 1px solid #bfbfbf;
            padding: 6px 7px;
            vertical-align: top;
        }

        .tbl th {
            background: #f3f3f3;
            font-weight: 700;
            text-align: left;
        }

        .subtle {
            color: #444;
        }

        .no-border td {
            border: 0 !important;
            padding: 0;
        }

        /* section “#” headings like the PDF */
        .sec-title {
            font-weight: 900;
            font-size: 15px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .sec-title::before {
            /* content: "# "; */
            font-weight: 900;
        }

        .sec-title-large {
            font-weight: 900;
            font-size: 18px;
            margin-top: 30px;
            margin-bottom: 12px;
        }

        .sec-title-large::before {
            /* content: "# "; */
            font-weight: 900;
        }

        /* Photos pages */
        .page-break {
            page-break-after: always;
        }

        .photo-wrap {
            margin-top: 6px;
        }

        .photo-box {
            border: 1px solid #cfcfcf;
            padding: 10px;
        }

        .photo-img {
            width: 100%;
            max-height: 510px;
            object-fit: contain;
            display: block;
        }

        .photo-cap {
            text-align: center;
            margin-top: 6px;
            font-size: 10px;
            color: #333;
        }

        .cmb-2 {
            margin-bottom: 5px;
        }

        /* Prevent row splitting awkwardly */
        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    @php
        // top right "MATCH REPORT #17 08.12.2025, 00:45"
        $printStamp = \Carbon\Carbon::parse(now())->format('d.m.Y, H:i');

        $matchStamp = \Carbon\Carbon::parse($op->match_date)->format('d-m-Y');
    @endphp

    <header>
        <table class="banner">
            <tr>
                <td style="width:10%;">
                    {{-- ONE merged banner image to match exactly --}}
                    {{-- Create one PNG like the PDF header (flags + tournament logo) --}}
                    <img class="banner-img" src="{{ public_path('assets/img/logos/sc_logo_gray_blue.png') }}"
                        alt="banner">
                </td>
                <td class="right topline" style="width:50%; padding-right:2px;">
                    <div><b>MATCH REPORT {{ $op->match?->match_number ?? $op->id }}</b> {{ $printStamp }}</div>
                    <div class="subtle">{{ $op->public_url ?? 'https://drs.sc.qa/' }}</div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td class="topline subtle">
                    {{ $op->public_url ?? 'https://drs.sc.qa/' }}
                </td>
                {{-- <td class="topline right subtle">
                    Page <span class="pageNumber"></span> of <span class="pageCount"></span>
                </td> --}}
            </tr>
        </table>
    </footer>

    {{-- DOMPDF page number script --}}
    {{-- <script type="text/php">
if (isset($pdf)) {
    $pdf->page_script('
        $font = $fontMetrics->get_font("helvetica", "normal");
        $pdf->text(520, 820, "Page $PAGE_NUM of $PAGE_COUNT", $font, 9);
    ');
}
</script> --}}

    <main style="margin-top: 22mm;">
        <h1 class="hash cmb-2">MATCH REPORT {{ $op->match?->match_number ?? $op->id }} - {{ $op->event->name ?? '' }}</h1>

        <div class="sec-title">Match Information</div>
        <table class="tbl">
            <tr>
                <th style="width:18%;">Tournament:</th>
                <td colspan="3"style="width:32%;">{{ $op->event->name ?? '' }}</td>
            </tr>
            <tr>
                <th style="width:18%;">Date:</th>
                <td style="width:32%;">{{ $matchStamp }} </td>
                <th style="width:18%;">Time:</th>
                <td style="width:32%;">{{ $op->match_time }}</td>
            </tr>
            <tr>
                <th>Stage:</th>
                <td>{{ $op->stage }}</td>
                <th>Stadium:</th>
                <td>{{ $op->venue->title ?? '' }}</td>
            </tr>
        </table>

        <table class="tbl" style="margin-top: 10mm; font-size: 15px; font-weight: 900; border: 3px solid #333;">
            <tr>
                <th class="hash"
                    style="width:18%; font-size: 15px; font-weight: 900; background: #e6e6e6; padding: 10px 7px;">Teams:
                </th>
                <td class="hash" style="width:32%; font-size: 15px; font-weight: 900; padding: 10px 7px;">
                    {{ $op->team_a_name ?? '' }} vs {{ $op->team_b_name ?? '' }}</td>
                <th class="hash"
                    style="width:18%; font-size: 15px; font-weight: 900; background: #e6e6e6; padding: 10px 7px;">Final
                    Score:</th>
                <td class="hash" style="width:32%; font-size: 15px; font-weight: 900; padding: 10px 7px;">
                    {{ $op->final_score }}</td>
                <th class="hash"
                    style="width:18%; font-size: 15px; font-weight: 900; background: #e6e6e6; padding: 10px 7px;">
                    Official Attendance:</th>
                <td class="hash" style="width:32%; font-size: 15px; font-weight: 900; padding: 10px 7px;">
                    {{ number_format((int) $op->official_attendance) }}</td>
            </tr>
            {{-- Extra time and penalties rows if applicable --}}
            <tr>
                <th style="width:18%; font-size: 15px; font-weight: 900; background: #e6e6e6; padding: 10px 7px;">Extra
                    Time:</th>
                <td style="width:32%; font-size: 15px; font-weight: 900; padding: 10px 7px;">
                    {{ $op->match_extra_time_flag ? 'Yes' : 'No' }}</td>
                <th style="width:18%; font-size: 15px; font-weight: 900; background: #e6e6e6; padding: 10px 7px;">
                    Penalties:</th>
                <td style="width:32%; font-size: 15px; font-weight: 900; padding: 10px 7px;">
                    {{ $op->match_penalties_flag ? 'Yes' : 'No' }}</td>
                <th style="width:18%; font-size: 15px; font-weight: 900; background: #e6e6e6; padding: 10px 7px;">
                    Penalties Final Score:</th>
                <td style="width:32%; font-size: 15px; font-weight: 900; padding: 10px 7px;">
                    {{ $op->penalties_final_score ?? 'N/A' }}</td>
            </tr>
        </table>

        <div class="sec-title">PSA/Turnstiles Operations</div>
        <table class="tbl">
            <tr>
                <th style="width:25%;">PSA Scanned:</th>
                <td style="width:25%;">{{ $op->psa_scanned }}</td>
                <th style="width:25%;">Turnstiles Scanned:</th>
                <td style="width:25%;">{{ $op->turnstiles_scanned }}</td>
            </tr>
            <tr>
                <th>Accreditation Scanned:</th>
                <td colspan="3">{{ $op->accreditation_scanned }}</td>
            </tr>
        </table>

        <div class="sec-title">Actions Taken by VUM (Pre-Match Day)</div>
        <div style="margin:6px 0 10px; line-height:1.35;">
            {!! filled($op->actions_vum) ? $op->actions_vum : 'No incidents reported.' !!}
            {{-- {!! nl2br(e($op->actions_vum)) !!} --}}
        </div>

        <div class="sec-title-large">Client Groups</div>

        <div class="sec-title" style="margin-top:6px;">Spectators</div>
        <table class="tbl">
            <tr>
                <th style="width:25%;">VVIP:</th>
                <td style="width:25%;">{{ $op->spectators_vvip }}</td>
                <th style="width:25%;">Hospitality (Skyboxes):</th>
                <td style="width:25%;">{{ $op->spectators_hospitality_skyboxes }}</td>
            </tr>
            <tr>
                <th>VIP:</th>
                <td>{{ $op->spectators_vip }}</td>
                <th>Hospitality (Lounges):</th>
                <td>{{ $op->spectators_hospitality_lounges }}</td>
            </tr>
        </table>

        <div class="sec-title">Media &amp; Broadcast</div>
        <table class="tbl">
            <tr>
                <th style="width:25%;">Media Tribune:</th>
                <td style="width:25%;">{{ $op->media_tribune }}</td>
                <th style="width:25%;">Photo Tribune:</th>
                <td style="width:25%;">{{ $op->photo_tribune }}</td>
            </tr>
            <tr>
                <th>Photo Pitch:</th>
                <td>{{ $op->photo_pitch }}</td>
                <th>Mixed Zone:</th>
                <td>{{ $op->mixed_zone }}</td>
            </tr>
            <tr>
                <th>Press Conference:</th>
                <td>{{ $op->press_conference }}</td>
                <th>Broadcast Personnel:</th>
                <td>{{ $op->broadcast_personnel }}</td>
            </tr>
        </table>

        <div class="sec-title">Services</div>
        <table class="tbl">
            <tr>
                <th style="width:25%;">Volunteers:</th>
                <td style="width:25%;">{{ $op->volunteers_arrived }} / {{ $op->volunteers_expected }}</td>
                <th style="width:25%;">SPS Staff:</th>
                <td style="width:25%;">{{ $op->sps_staff_arrived }} / {{ $op->sps_staff_expected }}</td>
            </tr>
            <tr>
                <th>F&amp;B Concessions:</th>
                <td>{{ $op->fnb_concessions_arrived }} / {{ $op->fnb_concessions_expected }}</td>
                <th>Medical Staff:</th>
                <td>{{ $op->medical_staff_arrived }} / {{ $op->medical_staff_expected }}</td>
            </tr>
            <tr>
                <th>Cleaning &amp; Waste:</th>
                <td>{{ $op->cleaning_waste_arrived }} / {{ $op->cleaning_waste_expected }}</td>
                <th>Hospitality Services:</th>
                <td>{{ $op->hospitality_services_arrived }} / {{ $op->hospitality_services_expected }}</td>
            </tr>
        </table>

        <div class="sec-title">Mobility Section</div>
        <table class="tbl">
            <tr>
                <th style="width:25%;">Metro Inbound:</th>
                <td style="width:25%;">{{ $op->metro_inbound }}</td>
                <th style="width:25%;">Metro Outbound:</th>
                <td style="width:25%;">{{ $op->metro_outbound }}</td>
            </tr>
            <tr>
                <th style="width:25%;">Taxi Inbound:</th>
                <td style="width:25%;">{{ $op->taxi_inbound }}</td>
                <th style="width:25%;">Taxi Outbound:</th>
                <td style="width:25%;">{{ $op->taxi_outbound }}</td>
            </tr>
            <tr>
                <th style="width:18%;">Parking:</th>
                <td colspan="3"style="width:32%;">{{ $op->parking_count ?? '' }}</td>
            </tr>
        </table>
        <div style="margin:6px 0 10px; line-height:1.35;">
            {!! filled($op->mobility_section) ? $op->mobility_section : 'No incidents reported.' !!}
        </div>

        <div class="sec-title">Shukran Programme</div>
        <table class="tbl">
            <tr>
                <th style="width:25%;">Implemented:</th>
                <td style="width:25%;">{{ $op->shukran_programme == 1 ? 'Yes' : 'No' }}</td>
                <th style="width:25%;">Count:</th>
                <td style="width:25%;">{{ $op->shukran_count ?? '' }}</td>
            </tr>
        </table>

        <div class="sec-title">Venue Manager General Comments</div>
        <div style="margin:6px 0 10px; line-height:1.35;">
            {!! filled($op->general_issues) ? $op->general_issues : 'No incidents reported.' !!}
            {{-- {!! nl2br(e($op->actions_vum)) !!} --}}
        </div>

        <div class="sec-title">VOC Issue Log Inputs</div>
        <table class="tbl">
            <thead>
                <tr>
                    <th style="width:6%;">Issue ID</th>
                    <th style="width:10%;">Raised By</th>
                    <th style="width:18%;">Responsible FA</th>
                    <th style="width:10%;">Category</th>
                    <th style="width:10%;">Status</th>
                    <th style="width:30%;">Title / Description</th>
                    <th style="width:10%;">Location</th>
                    <th style="width:6%;">Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($op->vocIssuesCritical as $issue)
                    <tr>
                        <td>{{ $issue->issue_id }}</td>
                        <td>{{ $issue->raised_by }}</td>
                        <td>{{ $issue->responsible_fa }}</td>
                        <td>{{ $issue->category }}</td>
                        <td>{{ $issue->status }}</td>
                        <td>{{ $issue->description }}</td>
                        <td>{{ $issue->location }}</td>
                        <td>{{ $issue->time_raised }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Attached photos section like the PDF: photo pages after table --}}
        @if ($op->photos->count())
            <div class="page-break"></div>
            <div class="sec-title">Attached Photos</div>
            <div style="margin:6px 0 10px;">
                {{ $op->photos->count() }} photo(s) included in this report:
            </div>

            @foreach ($op->photos as $i => $photo)
                <div class="photo-wrap">
                    <div class="photo-box">
                        <img class="photo-img" src="{{ storage_path('app/private/' . $photo->path) }}"
                            alt="photo {{ $i + 1 }}">
                    </div>
                    <div class="photo-cap">
                        Photo {{ $i + 1 }}: {{ $photo->original_name }}
                    </div>
                </div>
                <div class="page-break"></div>
            @endforeach
        @endif

        {{-- Last line like “Report prepared by” --}}
        {{-- <div class="page-break"></div> --}}
        <div style="margin-top: 10px;">
            Report prepared by: {{ $op->reportedBy->name ?? 'N/A' }}
        </div>
    </main>

</body>

</html>
