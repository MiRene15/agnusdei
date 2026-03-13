@extends('layouts.student')

@section('title', 'Subjects')

@section('content')

<div class="page-intro">
    <h4>My Subjects</h4>
    <p>These subjects were assigned automatically based on your grade level, section, and school year.</p>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Grade Level</th>
                    <th>Section</th>
                    <th>School Year</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                    <tr>
                        <td>{{ $enrollment->class->subject->subject_code ?? '-' }}</td>
                        <td>{{ $enrollment->class->subject->subject_name ?? '-' }}</td>
                        <td>{{ $enrollment->class->grade_level ?? '-' }}</td>
                        <td>{{ $enrollment->class->section ?? '-' }}</td>
                        <td>{{ $enrollment->class->school_year ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No subjects assigned yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection