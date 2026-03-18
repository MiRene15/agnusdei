@extends('layouts.teacher')

@section('title', 'Grade Encoding')

@section('content')

<div class="page-intro">
    <h4>Grade Encoding</h4>
    <p>Encode or update grades for students under your assigned classes.</p>
</div>

@if(session('success'))
    <div class="card" style="border-left:4px solid #16a34a; color:#166534;">
        {{ session('success') }}
    </div>
@endif

@forelse($classes as $class)
    <div class="card">
        <h4>
            {{ $class->subject->subject_name ?? '-' }}
            ({{ $class->grade_level }} - {{ $class->section }})
        </h4>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Grading Period</th>
                        <th>Grade</th>
                        <th>Remarks</th>
                        <th>Save</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($class->enrollments as $enrollment)
                        @php
                            $firstQuarter = $enrollment->grades->where('grading_period', '1st Quarter')->first();
                        @endphp
                        <tr>
                            <td>
                                {{ $enrollment->student->first_name ?? '-' }}
                                {{ $enrollment->student->last_name ?? '' }}
                            </td>
                            <td>1st Quarter</td>
                            <td colspan="3" style="padding:0;">
                                <form method="POST" action="{{ route('teacher.grades.save') }}" style="
                                    display:grid;
                                    grid-template-columns: 150px 1fr auto;
                                    gap:10px;
                                    padding:12px;
                                ">
                                    @csrf
                                    <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">
                                    <input type="hidden" name="grading_period" value="1st Quarter">

                                    <input
                                        type="number"
                                        step="0.01"
                                        name="grade"
                                        value="{{ $firstQuarter->grade ?? '' }}"
                                        placeholder="Enter grade"
                                        required
                                        style="padding:10px 12px; border:1px solid #cbd5e1; border-radius:10px;"
                                    >

                                    <input
                                        type="text"
                                        name="remarks"
                                        value="{{ $firstQuarter->remarks ?? '' }}"
                                        placeholder="Remarks"
                                        style="padding:10px 12px; border:1px solid #cbd5e1; border-radius:10px;"
                                    >

                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#64748b;">No enrolled students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="card">
        <p style="color:#64748b;">No classes available for grade encoding.</p>
    </div>
@endforelse

@endsection