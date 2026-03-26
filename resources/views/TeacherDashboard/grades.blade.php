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

@if(session('error'))
    <div class="card" style="border-left:4px solid #dc2626; color:#991b1b;">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="card" style="border-left:4px solid #f59e0b; color:#92400e;">
        <strong>Please fix the following:</strong>
        <ul style="margin:8px 0 0 18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $gradingPeriods = ['1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter'];
@endphp

@forelse($classes as $class)
    <div class="card">
        <h4 style="margin-bottom:6px;">
            {{ $class->subject->subject_name ?? '-' }}
            ({{ $class->subject->subject_code ?? '-' }})
        </h4>
        <p style="color:#64748b; margin-bottom:16px;">
            {{ $class->grade_level }} • {{ $class->section }} • {{ $class->school_year }}
        </p>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="min-width:180px;">Student</th>
                        <th style="min-width:150px;">Grading Period</th>
                        <th style="min-width:120px;">Grade</th>
                        <th>Remarks</th>
                        <th style="min-width:100px;">Save</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($class->enrollments as $enrollment)
                        @foreach($gradingPeriods as $period)
                            @php
                                $existingGrade = $enrollment->grades->where('grading_period', $period)->first();
                            @endphp
                            <tr>
                                <td>
                                    {{ $enrollment->student->first_name ?? '-' }}
                                    {{ $enrollment->student->last_name ?? '' }}
                                </td>
                                <td>{{ $period }}</td>
                                <td colspan="3" style="padding:0;">
                                    <form method="POST" action="{{ route('teacher.grades.save') }}" style="
                                        display:grid;
                                        grid-template-columns: 120px 1fr auto;
                                        gap:10px;
                                        padding:12px;
                                        align-items:center;
                                    ">
                                        @csrf
                                        <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">
                                        <input type="hidden" name="grading_period" value="{{ $period }}">

                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="100"
                                            name="grade"
                                            value="{{ $existingGrade->grade ?? '' }}"
                                            placeholder="Grade"
                                            required
                                            style="padding:10px 12px; border:1px solid #cbd5e1; border-radius:10px;"
                                        >

                                        <input
                                            type="text"
                                            name="remarks"
                                            value="{{ $existingGrade->remarks ?? '' }}"
                                            placeholder="Remarks"
                                            style="padding:10px 12px; border:1px solid #cbd5e1; border-radius:10px;"
                                        >

                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
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