@extends('layouts.teacher')

@section('title', 'My Classes')

@section('content')
<div class="page-intro">
    <h4>My Classes</h4>
    <p>View your assigned subjects, advisory ownership, sections, and enrolled students.</p>
</div>

@if(session('success'))
    <div class="card" style="border-left:4px solid #16a34a; color:#166534;">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="card" style="border-left:4px solid #dc2626; color:#991b1b;">{{ session('error') }}</div>
@endif

@forelse($classes as $class)
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-wrap:wrap; margin-bottom:14px;">
            <div>
                <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:6px;">
                    <h4 style="margin:0;">{{ $class->subject->subject_name ?? '-' }} ({{ $class->subject->subject_code ?? '-' }})</h4>
                    @if($class->is_advisory)
                        <span class="badge badge-info">Advisory Class</span>
                    @endif
                </div>
                <p style="color:#64748b; margin:0;">Grade Level: {{ $class->grade_level }} | Section: {{ $class->section }} | School Year: {{ $class->school_year }} | Room: {{ $class->room ?? '-' }}</p>
            </div>

            <div style="padding:10px 14px; background:#f8fafc; border-radius:10px; min-width:150px;">
                <div style="font-size:12px; color:#64748b;">Enrolled Students</div>
                <div style="font-size:24px; font-weight:700; color:#0f172a;">{{ $class->enrollments->count() }}</div>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student Number</th>
                        <th>LRN</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($class->enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->student->student_number ?? '-' }}</td>
                            <td>{{ $enrollment->student->lrn ?? '-' }}</td>
                            <td>{{ $enrollment->student->first_name ?? '-' }} {{ $enrollment->student->last_name ?? '' }}</td>
                            <td>{{ ucfirst($enrollment->status ?? '-') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:#64748b;">No enrolled students.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="card">
        <p style="color:#64748b;">No assigned classes found.</p>
    </div>
@endforelse
@endsection
