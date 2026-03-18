@extends('layouts.teacher')

@section('title', 'My Classes')

@section('content')

<div class="page-intro">
    <h4>My Classes</h4>
    <p>View your assigned subjects, sections, and enrolled students.</p>
</div>

@forelse($classes as $class)
    <div class="card">
        <h4>
            {{ $class->subject->subject_name ?? '-' }}
            ({{ $class->subject->subject_code ?? '-' }})
        </h4>

        <p style="color:#64748b; margin-bottom:14px;">
            Grade Level: {{ $class->grade_level }} |
            Section: {{ $class->section }} |
            School Year: {{ $class->school_year }} |
            Room: {{ $class->room ?? '-' }}
        </p>

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
                            <td>
                                {{ $enrollment->student->first_name ?? '-' }}
                                {{ $enrollment->student->last_name ?? '' }}
                            </td>
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