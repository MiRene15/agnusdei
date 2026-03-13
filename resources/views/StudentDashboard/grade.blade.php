@extends('layouts.student')

@section('title', 'Grades')

@section('content')

<div class="page-intro">
    <h4>My Grades</h4>
    <p>View your encoded grades per subject and grading period.</p>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Grading Period</th>
                    <th>Grade</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $grade)
                    <tr>
                        <td>{{ $grade->enrollment->class->subject->subject_name ?? '-' }}</td>
                        <td>{{ $grade->grading_period }}</td>
                        <td>{{ $grade->grade }}</td>
                        <td>{{ $grade->remarks ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No grades available yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection