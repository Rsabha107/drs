<div class="table-responsive" style="max-height: 420px; overflow:auto; border:1px solid #e5e7eb; border-radius:8px;">
    <table class="table table-bordered table-sm mb-0">
        <thead style="position: sticky; top: 0; background: #f3f4f6; z-index: 2;">
            <tr>
                <th>Issue ID</th>
                <th>Raised By</th>
                <th>Responsible FA</th>
                <th>Category</th>
                <th>Status</th>
                <th>Title / Description</th>
                <th>Location</th>
                <th>Time Raised</th>
            </tr>
        </thead>
        <tbody>
        @forelse($issues as $row)
            @php $s = strtolower(trim($row->status ?? '')); @endphp
            <tr>
                <td>{{ $row->issue_id }}</td>
                <td>{{ $row->raised_by }}</td>
                <td>{{ $row->responsible_fa }}</td>
                <td>{{ $row->category }}</td>
                <td>
                    <span class="badge {{ $s === 'closed' ? 'bg-secondary' : 'bg-warning text-dark' }}">
                        {{ $row->status }}
                    </span>
                </td>
                <td style="min-width:280px;">{{ $row->description }}</td>
                <td>{{ $row->location }}</td>
                <td>{{ $row->time_raised }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted p-4">No VOC issues imported yet.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
