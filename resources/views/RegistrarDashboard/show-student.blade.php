@extends('layouts.registrar')

@section('title', 'Student Details')

@section('content')

<div class="page-intro">
    <h4>Student Details</h4>
    <p>View all available information for this student record.</p>
</div>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <h4 style="margin-bottom:0;">Student Profile</h4>

        <a href="{{ route('registrar.students') }}" class="btn btn-outline">
            Back to Student Records
        </a>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Basic Information</h4>

        <div class="section-box">
            <h5>Student Number</h5>
            <p>{{ $student->student_number }}</p>
        </div>

        <div class="section-box" style="margin-top:14px;">
            <h5>LRN</h5>
            <p>{{ $student->lrn ?? '-' }}</p>
        </div>

        <div class="section-box" style="margin-top:14px;">
            <h5>Full Name</h5>
            <p>{{ $student->first_name }} {{ $student->last_name }}</p>
        </div>

        <div class="section-box" style="margin-top:14px;">
            <h5>Birth Date</h5>
            <p>{{ $student->birth_date ?? '-' }}</p>
        </div>

        <div class="section-box" style="margin-top:14px;">
            <h5>Gender</h5>
            <p>{{ $student->gender ?? '-' }}</p>
        </div>

        <div class="section-box" style="margin-top:14px;">
            <h5>Status</h5>
            <p>{{ ucfirst($student->status) }}</p>
        </div>
    </div>

    <div class="card">
        <h4>School Information</h4>

        <div class="section-box">
            <h5>Grade Level</h5>
            <p>{{ $student->grade_level ?? '-' }}</p>
        </div>

        <div class="section-box" style="margin-top:14px;">
            <h5>Section</h5>
            <p>{{ $student->section ?? '-' }}</p>
        </div>

        <div class="section-box" style="margin-top:14px;">
            <h5>School Year</h5>
            <p>{{ $student->school_year ?? '-' }}</p>
        </div>

        <div class="section-box" style="margin-top:14px;">
            <h5>Email</h5>
            <p>{{ $student->email ?? '-' }}</p>
        </div>

        <div class="section-box" style="margin-top:14px;">
            <h5>Phone</h5>
            <p>{{ $student->phone ?? '-' }}</p>
        </div>

        <div class="section-box" style="margin-top:14px;">
            <h5>Address</h5>
            <p>{{ $student->address ?? '-' }}</p>
        </div>
    </div>
</div>

@if($student->admission)
<div class="card">
    <h4>Admission Information</h4>

    <div class="grid-3">
        <div class="section-box">
            <h5>Application Number</h5>
            <p>{{ $student->admission->application_number ?? '-' }}</p>
        </div>

        <div class="section-box">
            <h5>Admission Status</h5>
            <p>{{ ucfirst($student->admission->status ?? '-') }}</p>
        </div>

        <div class="section-box">
            <h5>Applying For Grade</h5>
            <p>{{ $student->admission->applying_for_grade ?? '-' }}</p>
        </div>
    </div>

    <div class="grid-3" style="margin-top:16px;">
        <div class="section-box">
            <h5>Previous School</h5>
            <p>{{ $student->admission->previous_school ?? '-' }}</p>
        </div>

        <div class="section-box">
            <h5>Application Date</h5>
            <p>{{ $student->admission->application_date ?? '-' }}</p>
        </div>

        <div class="section-box">
            <h5>Remarks</h5>
            <p>{{ $student->admission->remarks ?? '-' }}</p>
        </div>
    </div>
</div>
@endif

@endsection