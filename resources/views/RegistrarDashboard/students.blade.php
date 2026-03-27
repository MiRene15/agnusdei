@extends('layouts.registrar')

@section('title', 'Student Records')

@section('content')
<div class="page-intro">
    <h4>Student Records</h4>
    <p>Review approved student profiles, filter by grade and section, and jump directly into each record.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">All Student Records</div>
        <div class="stat-value">{{ $studentSummary['total'] ?? 0 }}</div>
        <div class="stat-sub">Total students stored in the registrar records</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Enrolled Students</div>
        <div class="stat-value">{{ $studentSummary['enrolled'] ?? 0 }}</div>
        <div class="stat-sub">Students already marked as enrolled</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">With Section</div>
        <div class="stat-value">{{ $studentSummary['with_section'] ?? 0 }}</div>
        <div class="stat-sub">Students already assigned to a section</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Filtered Result</div>
        <div class="stat-value">{{ $studentSummary['filtered'] ?? 0 }}</div>
        <div class="stat-sub">Records matching the current filters</div>
    </div>
</div>

<div class="card">
    <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; align-items:flex-start; margin-bottom:18px;">
        <div>
            <h4 style="margin-bottom:6px;">Record Search</h4>
            <p style="color:#64748b; margin:0;">Search by name, student number, or LRN, then narrow the list by grade level and section.</p>
        </div>
        <div style="padding:12px 14px; border-radius:16px; background:#eff6ff; color:#1d4ed8; font-weight:700;">{{ request('grade_level') ?: 'All Grades' }}</div>
    </div>

    <form method="GET" action="{{ route('registrar.students') }}" class="search-row">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student number, LRN, or name">

        <select name="grade_level" id="grade_level_filter" class="form-control">
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

        <select name="section" id="section_filter" class="form-control">
            <option value="">All Sections</option>
            @foreach($sections as $grade => $gradeSections)
                @foreach($gradeSections as $section)
                    <option value="{{ $section->section_name }}" data-grade="{{ $section->grade_level }}" {{ request('section') == $section->section_name ? 'selected' : '' }}>
                        {{ $section->grade_level }} - {{ $section->section_name }}
                    </option>
                @endforeach
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Apply Filter</button>
        <a href="{{ route('registrar.students') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card">
    <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; align-items:center; margin-bottom:14px;">
        <div>
            <h4 style="margin-bottom:6px;">Student List</h4>
            <p style="color:#64748b; margin:0;">Open a record to review tuition, enrollment, sectioning, PTC, transfer status, and voucher progress.</p>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Grade / Section</th>
                    <th>School Year</th>
                    <th>Status</th>
                    <th>Institutional Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    @php $status = strtolower((string) $student->status); @endphp
                    <tr>
                        <td>
                            <div style="display:grid; gap:4px;">
                                <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                <span style="color:#64748b;">{{ $student->student_number }}</span>
                                <span style="color:#94a3b8; font-size:13px;">LRN: {{ $student->lrn ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display:grid; gap:4px;">
                                <strong>{{ $student->grade_level }}</strong>
                                <span style="color:#64748b;">{{ $student->section ?? 'Not Yet Assigned' }}</span>
                            </div>
                        </td>
                        <td>{{ $student->school_year ?? '-' }}</td>
                        <td>
                            @if($status === 'approved' || $status === 'enrolled')
                                <span class="badge badge-approved">{{ ucfirst($status) }}</span>
                            @elseif($status === 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @else
                                <span class="badge badge-review">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                            @endif
                        </td>
                        <td>{{ $student->email ?? '-' }}</td>
                        <td><a href="{{ route('registrar.students.show', $student->id) }}" class="btn btn-primary">Open Record</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:#64748b;">No students found for the selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:18px;">
        {{ $students->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const gradeSelect = document.getElementById('grade_level_filter');
    const sectionSelect = document.getElementById('section_filter');

    function filterSections() {
        const selectedGrade = gradeSelect.value;

        Array.from(sectionSelect.options).forEach(function (option, index) {
            if (index === 0) {
                option.hidden = false;
                return;
            }

            option.hidden = selectedGrade !== '' && option.getAttribute('data-grade') !== selectedGrade;
        });

        if (sectionSelect.selectedOptions.length && sectionSelect.selectedOptions[0].hidden) {
            sectionSelect.value = '';
        }
    }

    gradeSelect.addEventListener('change', filterSections);
    filterSections();
});
</script>
@endsection
