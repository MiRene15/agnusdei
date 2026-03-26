@extends('layouts.teacher')

@section('title', 'Reports')

@section('content')

<div class="page-intro">
    <h4>Teaching Reports</h4>
    <p>Summary of your teaching load and grade encoding activity.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Classes</div>
        <div class="stat-value">{{ $totalClasses }}</div>
        <div class="stat-sub">Assigned teaching classes</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Students</div>
        <div class="stat-value">{{ $totalStudents }}</div>
        <div class="stat-sub">Students under all assigned classes</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Grades Encoded</div>
        <div class="stat-value">{{ $totalGradesEncoded }}</div>
        <div class="stat-sub">Saved grade entries</div>
    </div>
</div>

<div class="card">
    <h4 style="margin-bottom:14px;">Class Summary</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Subject Code</th>
                    <th>Grade Level</th>
                    <th>Section</th>
                    <th>School Year</th>
                    <th>Total Students</th>
                    <th>Grades Encoded</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $class)
                    <tr>
                        <td>{{ $class->subject->subject_name ?? '-' }}</td>
                        <td>{{ $class->subject->subject_code ?? '-' }}</td>
                        <td>{{ $class->grade_level }}</td>
                        <td>{{ $class->section }}</td>
                        <td>{{ $class->school_year }}</td>
                        <td>{{ $class->enrollments->count() }}</td>
                        <td>
                            {{ $class->enrollments->sum(function ($enrollment) {
                                return $enrollment->grades->count();
                            }) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; color:#64748b;">No report data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection