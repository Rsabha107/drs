<table>
    <tr>
        <td colspan="5" rowspan="4" style="font-weight: bold; font-size: 16px; vertical-align: middle; border: 1px solid #404040;">
            {{ $meta['event'] }}
        </td>
        <td colspan="3" rowspan="4" style="background-color: #D9E7F5; text-align: center; vertical-align: middle; font-weight: bold; font-size: 14px; border: 1px solid #404040;">
            VENUE: {{ $meta['venue'] }}
        </td>
    </tr>
    <tr></tr><tr></tr><tr></tr>

    <tr>
        <td colspan="5" rowspan="2" style="text-align: center; vertical-align: middle; font-size: 18px; font-weight: bold; border: 1px solid #404040;">ISSUE LOG</td>
        <td style="background-color: #D9E7F5; border: 1px solid #404040;">Date</td>
        <td style="background-color: #D9E7F5; border: 1px solid #404040; text-align: center;">{{ $meta['date'] }}</td>
        <td style="background-color: #D9E7F5; border: 1px solid #404040;"></td>
    </tr>
    <tr>
        <td style="background-color: #D9E7F5; border: 1px solid #404040;">Match</td>
        <td style="background-color: #D9E7F5; border: 1px solid #404040; text-align: center;">{{ $meta['match'] }}</td>
        <td style="background-color: #D9E7F5; border: 1px solid #404040;"></td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td style="background-color: #D9E7F5; border: 1px solid #404040;">KO Time</td>
        <td style="background-color: #D9E7F5; border: 1px solid #404040; text-align: center;">{{ $meta['ko_time'] }}</td>
        <td style="background-color: #D9E7F5; border: 1px solid #404040;"></td>
    </tr>
    <tr>
        <td colspan="5" style="text-align: left;">EV (90')</td>
        <td style="background-color: #D9E7F5; border: 1px solid #404040;">Half Time</td>
        <td style="background-color: #D9E7F5; border: 1px solid #404040; text-align: center;">{{ $meta['half_time'] }}</td>
        <td style="background-color: #D9E7F5; border: 1px solid #404040;"></td>
    </tr>

    <tr>
        <th style="background-color: #2F75B5; color: #FFFFFF; border: 1px solid #404040;">Issue ID</th>
        <th style="background-color: #2F75B5; color: #FFFFFF; border: 1px solid #404040;">Raised By</th>
        <th style="background-color: #2F75B5; color: #FFFFFF; border: 1px solid #404040;">Responsible FA</th>
        <th style="background-color: #2F75B5; color: #FFFFFF; border: 1px solid #404040;">Category</th>
        <th style="background-color: #2F75B5; color: #FFFFFF; border: 1px solid #404040;">Status</th>
        <th style="background-color: #2F75B5; color: #FFFFFF; border: 1px solid #404040;">Title / Description</th>
        <th style="background-color: #2F75B5; color: #FFFFFF; border: 1px solid #404040;">Location</th>
        <th style="background-color: #2F75B5; color: #FFFFFF; border: 1px solid #404040;">Time Raised</th>
    </tr>

    @foreach($issues as $issue)
        @php
            $categoryColor = match($issue['category']) {
                'Information' => '#C6E0B4',
                'Issue' => '#FFE699',
                default => '#FFF2CC',
            };

            $statusColor = $issue['status'] === 'Open' ? '#F4CCCC' : '#D9D9D9';
            $statusText = $issue['status'] === 'Open' ? '#9C0006' : '#1F1F1F';
        @endphp
        <tr>
            <td style="border: 1px solid #404040; text-align: center;">{{ $issue['id'] }}</td>
            <td style="border: 1px solid #404040;">{{ $issue['raised_by'] }}</td>
            <td style="border: 1px solid #404040;">{{ $issue['responsible_fa'] }}</td>
            <td style="border: 1px solid #404040; background-color: {{ $categoryColor }};">{{ $issue['category'] }}</td>
            <td style="border: 1px solid #404040; background-color: {{ $statusColor }}; color: {{ $statusText }};">{{ $issue['status'] }}</td>
            <td style="border: 1px solid #404040;">{{ $issue['description'] }}</td>
            <td style="border: 1px solid #404040; text-align: center;">{{ $issue['location'] }}</td>
            <td style="border: 1px solid #404040; text-align: center;">{{ $issue['time_raised'] }}</td>
        </tr>
    @endforeach
</table>
