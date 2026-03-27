@extends('layouts.student')

@section('title', 'Subjects')

@section('content')
<div class="page-intro">
    <h4>My Subjects</h4>
    <p>These subjects are connected to your current grade level, section, and school year placement.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Subjects</div>
        <div class="stat-value">{{ $enrollments->count() }}</div>
        <div class="stat-sub">Classes currently assigned to your record</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Grade Level</div>
        <div class="stat-value" style="font-size:20px;">{{ $student->grade_level ?? 'N/A' }}</div>
        <div class="stat-sub">Current enrolled grade level</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Section</div>
        <div class="stat-value" style="font-size:20px;">{{ $student->section ?? 'Pending' }}</div>
        <div class="stat-sub">Current assigned section</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">School Year</div>
        <div class="stat-value" style="font-size:20px;">{{ $student->school_year ?? 'N/A' }}</div>
        <div class="stat-sub">Academic cycle currently in use</div>
    </div>
</div>

<div class="card">
    <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; align-items:flex-start; margin-bottom:18px;">
        <div>
            <h4 style="margin-bottom:6px;">Subject List</h4>
            <p style="color:#64748b; margin:0;">Use this page to quickly confirm the subjects loaded under your current section placement.</p>
        </div>
        <div style="padding:12px 14px; border-radius:16px; background:#eff6ff; color:#1d4ed8; font-weight:700;">{{ $student->student_number ?? 'No Student Number' }}</div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Grade Level</th>
                    <th>Section</th>
                    <th>School Year</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                    <tr>
                        <td>
                            <div style="display:grid; gap:4px;">
                                <strong>{{ $enrollment->class->subject->subject_name ?? '-' }}</strong>
                                <span style="color:#64748b;">{{ $enrollment->class->subject->subject_code ?? '-' }}</span>
                            </div>
                        </td>
                        <td>{{ $enrollment->class->grade_level ?? '-' }}</td>
                        <td>{{ $enrollment->class->section ?? '-' }}</td>
                        <td>{{ $enrollment->class->school_year ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#64748b;">No subjects assigned yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h4>Subject Notes</h4>
    <ul class="mini-list">
        <li>Your subject list updates automatically from your enrolled class records.</li>
        <li>If a subject looks missing or incorrect, check your section placement first.</li>
        <li>Registrar sectioning and teacher class alignment both affect what appears here.</li>
    </ul>
</div>
@endsection
