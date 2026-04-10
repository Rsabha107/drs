@extends('drs.layout.admin_template')
@section('title', 'Email Templates')
@section('main')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Email Templates</h4>

            <a href="{{ route('admin.email-templates.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> New Template
            </a>
        </div>

        {{-- Flash / Toastr --}}
        @if (session('message'))
            <div class="alert alert-{{ session('alert-type', 'success') }} alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Filters --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control"
                    placeholder="Search by key or name">
            </div>

            <div class="col-md-2">
                <select name="locale" class="form-select">
                    <option value="">All Locales</option>
                    <option value="en" @selected(request('locale') == 'en')>EN</option>
                    <option value="ar" @selected(request('locale') == 'ar')>AR</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="active" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" @selected(request('active') === '1')>Active</option>
                    <option value="0" @selected(request('active') === '0')>Inactive</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100">
                    <i class="bx bx-filter"></i> Filter
                </button>
            </div>
        </form>

        {{-- Table --}}
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Key</th>
                            <th>Name</th>
                            <th>Locale</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $tpl)
                            <tr>
                                <td>
                                    <code>{{ $tpl->key }}</code>
                                </td>

                                <td>{{ $tpl->name }}</td>

                                <td>
                                    <span class="badge bg-info">
                                        {{ strtoupper($tpl->locale) }}
                                    </span>
                                </td>

                                <td class="text-truncate" style="max-width: 280px;">
                                    {{ $tpl->subject }}
                                </td>

                                <td>
                                    @if ($tpl->active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('admin.email-templates.edit', $tpl) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-edit"></i>
                                    </a>

                                    <form method="POST" action="{{ route('admin.email-templates.destroy', $tpl) }}"
                                        class="d-inline" onsubmit="return confirm('Delete this template?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No email templates found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($templates->hasPages())
                <div class="card-footer">
                    {{ $templates->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
