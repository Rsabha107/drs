{{--
  Excel export – Combined (flat) run sheet across all functional areas for a venue/match.
  Inline styles required; Excel strips class-based CSS.
--}}
<table>
    {{-- Title row --}}
    <tr>
        <td style="font-weight:bold;font-size:14pt;" colspan="9">
            Daily Run Sheet
            @if($firstSheet?->sheetType) ({{ $firstSheet->sheetType->code }}) @endif
        </td>
    </tr>

    {{-- Event --}}
    <tr>
        <td style="font-weight:bold;">Event</td>
        <td colspan="7">{{ $firstSheet?->event?->name ?? '' }}</td>
    </tr>

    {{-- Venue / Match / Teams --}}
    <tr>
        <td style="font-weight:bold;">Venue</td>
        <td>{{ $firstSheet?->venue?->short_name ?? 'N/A' }}</td>
        <td style="font-weight:bold;">Match No</td>
        <td>{{ $firstSheet?->match ? 'M' . $firstSheet->match->match_number : 'N/A' }}</td>
        <td style="font-weight:bold;">Teams</td>
        <td colspan="3">
            {{ $firstSheet?->match ? $firstSheet->match->pma1 . ' vs ' . $firstSheet->match->pma2 : 'N/A' }}
        </td>
    </tr>

    {{-- Date / Gates / KO --}}
    <tr>
        <td style="font-weight:bold;">Date</td>
        <td>
            {{ $firstSheet?->match?->match_date
                ? \Carbon\Carbon::parse($firstSheet->match->match_date)->format('d/m/Y')
                : ($firstSheet?->run_date_dmy ?? 'N/A') }}
        </td>
        <td style="font-weight:bold;">Gates Opening</td>
        <td>
            {{ $firstSheet?->gates_opening
                ? \Carbon\Carbon::parse($firstSheet->gates_opening)->format('H:i') : 'N/A' }}
        </td>
        <td style="font-weight:bold;">Kick-Off</td>
        <td>{{ $koFormatted ?? 'N/A' }}</td>
        <td></td><td></td>
    </tr>

    {{-- Spacer --}}
    <tr><td colspan="9"></td></tr>

    {{-- Column headers --}}
    <tr>
        <td style="background-color:#305496;color:#ffffff;font-weight:bold;">Title</td>
        <td style="background-color:#305496;color:#ffffff;font-weight:bold;">Start Time</td>
        <td style="background-color:#305496;color:#ffffff;font-weight:bold;">Countdown</td>
        <td style="background-color:#305496;color:#ffffff;font-weight:bold;">End Time</td>
        {{-- <td style="background-color:#305496;color:#ffffff;font-weight:bold;">Sheet Type</td> --}}
        <td style="background-color:#305496;color:#ffffff;font-weight:bold;">Functional Area</td>
        <td style="background-color:#305496;color:#ffffff;font-weight:bold;">Location</td>
        <td style="background-color:#305496;color:#ffffff;font-weight:bold;">Description</td>
    </tr>

    {{-- Items --}}
    @foreach($items as $item)
        @php
            $sheet   = $item->_parentSheet;
            $fa      = $sheet?->functionalArea;
            $faLabel = $fa?->title ?? $fa?->name ?? ($item->functional_area ?? '');

            // Countdown
            $countdown = $item->countdown_to_ko ?? '';
            $startFmt  = $item->start_time ? \Carbon\Carbon::parse($item->start_time)->format('H:i') : null;
            if ($startFmt && $koFormatted) {
                [$kh, $km] = explode(':', $koFormatted);
                [$sh, $sm] = explode(':', $startFmt);
                $diff = ((int)$sh * 60 + (int)$sm) - ((int)$kh * 60 + (int)$km);
                if ($diff === 0) {
                    $countdown = 'KO';
                } else {
                    $sign  = $diff > 0 ? '+' : '-';
                    $abs   = abs($diff);
                    $label = 'KO' . $sign;
                    if (intdiv($abs, 60) > 0) $label .= intdiv($abs, 60) . 'h';
                    if ($abs % 60 > 0)        $label .= ($abs % 60) . 'm';
                    $countdown = $label;
                }
            }

            $bgMap = ['red' => '#FF0000', 'yellow' => '#FFFF00', 'green' => '#00B050', 'default' => '#FFFFFF'];
            $fgMap = ['red' => '#FFFFFF', 'yellow' => '#000000', 'green' => '#FFFFFF',  'default' => '#000000'];
            $bg    = $bgMap[$item->row_color] ?? '#FFFFFF';
            $fg    = $fgMap[$item->row_color] ?? '#000000';
            $bold  = ($item->row_color && $item->row_color !== 'default') ? 'font-weight:bold;' : '';
            $style = "background-color:{$bg};color:{$fg};{$bold}";
        @endphp
        <tr>
            <td style="{{ $style }}">{{ $item->title }}</td>
            <td style="{{ $style }}">{{ $startFmt ?? '' }}</td>
            <td style="{{ $style }}">{{ $countdown }}</td>
            <td style="{{ $style }}">{{ $item->end_time ? \Carbon\Carbon::parse($item->end_time)->format('H:i') : '' }}</td>
            {{-- <td style="{{ $style }}">{{ $sheet?->sheet_type ?? '' }}</td> --}}
            <td style="{{ $style }}">{{ $faLabel }}</td>
            <td style="{{ $style }}">{{ $item->location ?? '' }}</td>
            <td style="{{ $style }}">{{ $item->description ?? '' }}</td>
        </tr>
    @endforeach
</table>
