@extends('layouts.admin')

@section('title', 'Reference Codes')

@section('content')

<style>
    .reference-grid {
        display: grid;
        grid-template-columns: minmax(320px, 380px) minmax(0, 1fr);
        gap: 24px;
        align-items: start;
    }

    .reference-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .reference-create-btn {
        width: 100%;
    }

    .reference-action-form {
        display: inline-block;
        width: 100%;
    }

    .reference-action-btn {
        width: 100%;
        padding: 9px 12px !important;
        font-size: 13px !important;
    }

    .reference-code-text {
        font-weight: 700;
        color: #001e82;
        word-break: break-word;
    }

    .reference-meta {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }

    .reference-assignment {
        font-size: 13px;
        color: #64748b;
        margin-top: 4px;
        line-height: 1.5;
    }

    .reference-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .reference-badge-used {
        background: #dcfce7;
        color: #166534;
    }

    .reference-badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .reference-badge-active {
        background: #dbeafe;
        color: #1d4ed8;
    }

    @media (max-width: 1200px) {
        .reference-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 900px) {
        .reference-filter-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .reference-filter-grid {
            grid-template-columns: 1fr;
        }

        .reference-create-btn,
        .reference-action-btn {
            width: 100%;
        }

        table {
            min-width: 900px;
        }
    }
</style>

<div class="page-intro">
    <h4>Reference Codes</h4>
    <p>Create and manage registration codes for Teacher, Registrar, Cashier, and Admin accounts.</p>
</div>

<div class="reference-grid">
    <div class="card">
        <h4>Create Reference Code</h4>

        <form method="POST" action="{{ route('admin.reference-codes.store') }}">
            @csrf

            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="">Select Role</option>
                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="registrar" {{ old('role') == 'registrar' ? 'selected' : '' }}>Registrar</option>
                    <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div id="teacher-fields" style="{{ old('role') === 'teacher' ? '' : 'display:none;' }}">
                <div class="form-group">
                    <label for="subject_id">Subject</label>
                    <select name="subject_id" id="subject_id" class="form-control">
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->subject_name ?? ('Subject #' . $subject->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="section">Section</label>
                    <input
                        type="text"
                        name="section"
                        id="section"
                        class="form-control"
                        value="{{ old('section') }}"
                        placeholder="e.g. St. Matthew"
                    >
                </div>

                <div class="form-group">
                    <label for="grade_level">Grade Level</label>
                    <input
                        type="text"
                        name="grade_level"
                        id="grade_level"
                        class="form-control"
                        value="{{ old('grade_level') }}"
                        placeholder="e.g. Grade 10"
                    >
                </div>

                <div class="form-group">
                    <label for="school_year">School Year</label>
                    <input
                        type="text"
                        name="school_year"
                        id="school_year"
                        class="form-control"
                        value="{{ old('school_year') }}"
                        placeholder="e.g. 2025-2026"
                    >
                </div>

                <div class="form-group">
                    <label for="semester">Semester</label>
                    <input
                        type="text"
                        name="semester"
                        id="semester"
                        class="form-control"
                        value="{{ old('semester') }}"
                        placeholder="e.g. 1st Semester"
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="expires_at">Expires At</label>
                <input
                    type="datetime-local"
                    name="expires_at"
                    id="expires_at"
                    class="form-control"
                    value="{{ old('expires_at') }}"
                >
            </div>

            <button type="submit" class="btn btn-primary reference-create-btn">Create Reference Code</button>
        </form>
    </div>

    <div class="card">
        <h4>Existing Reference Codes</h4>

        <form method="GET" action="{{ route('admin.reference-codes') }}" class="reference-filter-grid">
            <select name="role" class="form-control">
                <option value="">All Roles</option>
                <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                <option value="registrar" {{ request('role') == 'registrar' ? 'selected' : '' }}>Registrar</option>
                <option value="cashier" {{ request('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>

            <select name="status" class="form-control">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Used</option>
                <option value="unused" {{ request('status') == 'unused' ? 'selected' : '' }}>Unused</option>
            </select>

            <input
                type="text"
                name="search"
                class="form-control"
                value="{{ request('search') }}"
                placeholder="Search code, section, role..."
            >

            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Role</th>
                        <th>Subject / Assignment</th>
                        <th>Status</th>
                        <th>Used By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($codes as $code)
                        <tr>
                            <td>
                                <div class="reference-code-text">{{ $code->code }}</div>
                                <div class="reference-meta">
                                    {{ $code->created_at?->format('M d, Y h:i A') }}
                                </div>
                            </td>

                            <td style="text-transform:capitalize;">{{ $code->role }}</td>

                            <td>
                                @if($code->role === 'teacher')
                                    <div style="font-weight:600; color:#0f172a;">
                                        {{ $code->subject->subject_name ?? 'No subject' }}
                                    </div>
                                    <div class="reference-assignment">
                                        Section: {{ $code->section ?? 'N/A' }}<br>
                                        Grade: {{ $code->grade_level ?? 'N/A' }}<br>
                                        SY: {{ $code->school_year ?? 'N/A' }}<br>
                                        Semester: {{ $code->semester ?? 'N/A' }}
                                    </div>
                                @else
                                    <span style="color:#64748b;">Not applicable</span>
                                @endif
                            </td>

                            <td>
                                @if($code->is_used)
                                    <span class="reference-badge reference-badge-used">Used</span>
                                @elseif(!$code->is_active)
                                    <span class="reference-badge reference-badge-inactive">Inactive</span>
                                @else
                                    <span class="reference-badge reference-badge-active">Active</span>
                                @endif

                                @if($code->expires_at)
                                    <div class="reference-meta">
                                        Expires: {{ \Illuminate\Support\Carbon::parse($code->expires_at)->format('M d, Y h:i A') }}
                                    </div>
                                @endif
                            </td>

                            <td>{{ $code->usedBy->name ?? '—' }}</td>

                            <td>
                                @if(!$code->is_used && $code->is_active)
                                    <form method="POST" action="{{ route('admin.reference-codes.deactivate', $code->id) }}" class="reference-action-form">
                                        @csrf
                                        <button type="submit" class="btn btn-danger reference-action-btn">
                                            Deactivate
                                        </button>
                                    </form>
                                @else
                                    <span style="color:#94a3b8; font-size:13px;">No action</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; color:#64748b;">No reference codes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:18px;">
            {{ $codes->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const teacherFields = document.getElementById('teacher-fields');

    function toggleTeacherFields() {
        teacherFields.style.display = roleSelect.value === 'teacher' ? 'block' : 'none';
    }

    toggleTeacherFields();
    roleSelect.addEventListener('change', toggleTeacherFields);
});
</script>

@endsection