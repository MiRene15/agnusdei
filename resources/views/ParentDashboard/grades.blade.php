@extends('layouts.parent')

@section('title', 'Child Grades')

@section('content')

<div class="page-intro">
    <h4>Child Grades</h4>
    <p>Review encoded grades for each linked student.</p>
</div>

@forelse($children as $child)
    <div class="card">
        <h4>{{ $child->first_name }} {{ $child->last_name }}</h4>
        <p style="color:#64748b; margin-bottom:16px;">
            LRN: {{ $child->lrn ?? '-' }} |
            Grade Level: {{ $child->grade_level ?? '-' }} |
            Section: {{ $child->section ?? '-' }}
        </p>

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
                    @php
                        $grades = $gradesByChild[$child->id] ?? collect();
                    @endphp

                    @forelse($grades as $grade)
                        <tr>
                            <td>{{ $grade->enrollment->class->subject->subject_name ?? '-' }}</td>
                            <td>{{ $grade->grading_period }}</td>
                            <td>{{ $grade->grade }}</td>
                            <td>{{ $grade->remarks ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:#64748b;">No grades available yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="card">
        <p style="color:#64748b;">No linked children found.</p>
    </div>
@endforelse

@endsection