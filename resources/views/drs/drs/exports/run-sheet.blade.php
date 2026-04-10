{{--
  Excel export view for Daily Run Sheet.
  Uses Maatwebsite/Excel FromView approach.
  Inline styles are used because Excel strips class-based CSS.
--}}
<table>
    {{-- Header meta rows --}}
    <tr>
        <td style="font-weight:bold;font-size:14pt;" colspan="6">Daily Run Sheet ({{ $sheet->sheet_type }})</td>
    </tr>
    <tr>
        <td style="font-weight:bold;">Event</td>
        <td colspan="5">{{ $sheet->event->name ?? '' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold;">Venue</td>
        <td>{{ $sheet->venue->short_name ?? 'N/A' }}</td>
        <td style="font-weight:bold;">Date</td>
        <td>{{ $sheet->run_date_dmy }}</td>
        <td style="font-weight:bold;">Match No</td>
        <td>{{ $sheet->match ? 'M' . $sheet->match->match_number : 'N/A' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold;">Gates Opening</td>
        <td>{{ $sheet->gates_opening ? \Carbon\Carbon::parse($sheet->gates_opening)->format('H:i') : 'N/A' }}</td>
        <td style="font-weight:bold;">Kick-Off</td>
        <td>{{ $sheet->kick_off ? \Carbon\Carbon::parse($sheet->kick_off)->format('H:i') : 'N/A' }}</td>
        <td></td><td></td>
    </tr>
    <tr><td colspan="6"></td></tr>

    {{-- Column headers --}}
    <tr>
        <td style="background-color:#305496;color:#ffffff;font-weight:bold;">Title</td>
        <td style="background-color:#305496;color:#ffffff;font-weight:bold;">Start Time</td>
        <td style="background-color:#305496;color:#ffffff;font-weight:bold;">End Time</td>
        <td style="background-color:#305496;color:#ffffff;font-weight:bold;">Functional Area</td>
        <td style="background-color:#305496;color:#ffffff;font-weight:bold;">Location</td>
        <td style="background-color:#305496;color:#ffffff;font-weight:bold;">Description</td>
    </tr>

    {{-- Items --}}
    @foreach($sheet->items as $item)
    @php
        $bgMap   = ['red' => '#FF0000', 'yellow' => '#FFFF00', 'green' => '#00B050', 'default' => '#FFFFFF'];
        $fgMap   = ['red' => '#FFFFFF', 'yellow' => '#000000', 'green' => '#FFFFFF',  'default' => '#000000'];
        $bg      = $bgMap[$item->row_color] ?? '#FFFFFF';
        $fg      = $fgMap[$item->row_color] ?? '#000000';
        $bold    = $item->row_color !== 'default' ? 'font-weight:bold;' : '';
        $style   = "background-color:{$bg};color:{$fg};{$bold}";
    @endphp
    <tr>
        <td style="{{ $style }}">{{ $item->title }}</td>
        <td style="{{ $style }}">{{ $item->start_time ? \Carbon\Carbon::parse($item->start_time)->format('H:i') : '' }}</td>
        <td style="{{ $style }}">{{ $item->end_time ? \Carbon\Carbon::parse($item->end_time)->format('H:i') : '' }}</td>
        <td style="{{ $style }}">{{ $item->functional_area }}</td>
        <td style="{{ $style }}">{{ $item->location }}</td>
        <td style="{{ $style }}">{{ $item->description }}</td>
    </tr>
    @endforeach
</table>
