@extends('layouts.registrar')

@section('title', 'Enrollment Requests')

@section('content')

<div class="page-intro">
    <h4>Enrollment Requests</h4>
    <p>Review, filter, and manage incoming admission applications.</p>
</div>

@if(session('success'))
    <div class="card" style="border-left:4px solid #16a34a; color:#166534;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="card" style="border-left:4px solid #dc2626; color:#991b1b;">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <form method="GET" action="{{ route('registrar.enrollments') }}" class="search-row">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by application number or student name"
        >

        <select name="grade_level">
            <option value="">All Grade Levels</option>
            <option value="Nursery" {{ request('grade_level') == 'Nursery' ? 'selected' : '' }}>Nursery</option>
            <option value="Kinder" {{ request('grade_level') == 'Kinder' ? 'selected' : '' }}>Kinder</option>
            <option value="Grade 1" {{ request('grade_level') == 'Grade 1' ? 'selected' : '' }}>Grade 1</option>
            <option value="Grade 2" {{ request('grade_level') == 'Grade 2' ? 'selected' : '' }}>Grade 2</option>
            <option value="Grade 3" {{ request('grade_level') == 'Grade 3' ? 'selected' : '' }}>Grade 3</option>
            <option value="Grade 4" {{ request('grade_level') == 'Grade 4' ? 'selected' : '' }}>Grade 4</option>
            <option value="Grade 5" {{ request('grade_level') == 'Grade 5' ? 'selected' : '' }}>Grade 5</option>
            <option value="Grade 6" {{ request('grade_level') == 'Grade 6' ? 'selected' : '' }}>Grade 6</option>
            <option value="Grade 7" {{ request('grade_level') == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
            <option value="Grade 8" {{ request('grade_level') == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
            <option value="Grade 9" {{ request('grade_level') == 'Grade 9' ? 'selected' : '' }}>Grade 9</option>
            <option value="Grade 10" {{ request('grade_level') == 'Grade 10' ? 'selected' : '' }}>Grade 10</option>
            <option value="Grade 11" {{ request('grade_level') == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
            <option value="Grade 12" {{ request('grade_level') == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
        </select>

        <select name="status">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="incomplete" {{ request('status') == 'incomplete' ? 'selected' : '' }}>Incomplete</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('registrar.enrollments') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card">
    <h4>Application List</h4>

    <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:18px; align-items:center;">
        <div style="display:flex; align-items:center; gap:10px; color:#334155;">
            <span style="font-weight:600;">Batch Selection</span>
            <span id="selected-count" style="display:inline-flex; align-items:center; justify-content:center; min-width:34px; padding:6px 10px; border-radius:999px; background:#dbeafe; color:#1d4ed8; font-weight:700;">0</span>
            <span style="font-size:14px; color:#64748b;">Use the checkboxes to approve or mark multiple requests incomplete.</span>
        </div>
        <div style="display:flex; gap:12px; flex-wrap:wrap;">
        <form method="POST" action="{{ route('registrar.enrollments.batchApprove') }}" id="batch-approve-form">
            @csrf
            <button type="submit" class="btn btn-success" id="batch-approve-button" disabled>Batch Approve Selected</button>
        </form>

        <form method="POST" action="{{ route('registrar.enrollments.batchIncomplete') }}" id="batch-incomplete-form">
            @csrf
            <button type="submit" class="btn btn-warning" id="batch-incomplete-button" disabled>Batch Mark Incomplete</button>
        </form>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:48px;">
                        <input type="checkbox" id="select-all-admissions">
                    </th>
                    <th>Application No.</th>
                    <th>LRN</th>
                    <th>Applicant</th>
                    <th>Grade Level</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admissions as $admission)
                    @php
                        $status = strtolower((string) $admission->status);
                    @endphp
                    <tr>
                        <td>
                            @if($status !== 'approved')
                                <input type="checkbox" class="admission-checkbox" value="{{ $admission->id }}">
                            @endif
                        </td>
                        <td>{{ $admission->application_number }}</td>
                        <td>{{ $admission->lrn ?? '-' }}</td>
                        <td>{{ $admission->first_name }} {{ $admission->last_name }}</td>
                        <td>{{ $admission->applying_for_grade }}</td>
                        <td>
                            @if($status === 'approved')
                                <span class="badge badge-approved">Approved</span>
                            @elseif($status === 'under_review')
                                <span class="badge badge-review">Under Review</span>
                            @elseif($status === 'incomplete')
                                <span class="badge badge-incomplete">Incomplete</span>
                            @else
                                <span class="badge badge-pending">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                            @endif
                        </td>
                        <td>{{ $admission->application_date ?? '-' }}</td>
                        <td>
                            <a href="{{ route('registrar.enrollments.show', $admission->id) }}" class="btn btn-primary">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; color:#64748b;">No applications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:18px;">
        {{ $admissions->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all-admissions');
    const checkboxes = Array.from(document.querySelectorAll('.admission-checkbox'));
    const forms = [
        document.getElementById('batch-approve-form'),
        document.getElementById('batch-incomplete-form'),
    ];
    const selectedCount = document.getElementById('selected-count');
    const approveButton = document.getElementById('batch-approve-button');
    const incompleteButton = document.getElementById('batch-incomplete-button');

    function syncSelections() {
        const checkedIds = checkboxes.filter(checkbox => checkbox.checked).map(checkbox => checkbox.value);
        const hasSelection = checkedIds.length > 0;

        if (selectedCount) {
            selectedCount.textContent = checkedIds.length;
        }
        if (approveButton) {
            approveButton.disabled = !hasSelection;
            approveButton.style.opacity = hasSelection ? '1' : '.55';
            approveButton.style.cursor = hasSelection ? 'pointer' : 'not-allowed';
        }
        if (incompleteButton) {
            incompleteButton.disabled = !hasSelection;
            incompleteButton.style.opacity = hasSelection ? '1' : '.55';
            incompleteButton.style.cursor = hasSelection ? 'pointer' : 'not-allowed';
        }

        forms.forEach(form => {
            form.querySelectorAll('input[name="admission_ids[]"]').forEach(input => input.remove());

            checkedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'admission_ids[]';
                input.value = id;
                form.appendChild(input);
            });
        });
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });

            syncSelections();
        });
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', syncSelections);
    });

    syncSelections();
});
</script>

@endsection
