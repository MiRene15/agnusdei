@extends('layouts.registrar')

@section('title', 'Sectioning')

@section('content')

<div class="page-intro">
    <h4>Student Sectioning</h4>
    <p>Assign sections and school year to approved students. Subjects will be auto-assigned based on class matching.</p>
</div>

@if(session('success'))
    <div class="card" style="border-left:4px solid #16a34a; color:#166534;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="card" style="border-left:4px solid #dc2626; color:#991b1b;">
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $error)
                <li style="margin-bottom:4px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <h4>Approved Students</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student Number</th>
                    <th>LRN</th>
                    <th>Name</th>
                    <th>Grade Level</th>
                    <th>Section</th>
                    <th>School Year</th>
                    <th>Save</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $student->student_number }}</td>
                        <td>{{ $student->lrn ?? '-' }}</td>
                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td>{{ $student->grade_level }}</td>

                        <td colspan="3" style="padding:0;">
                            <form method="POST" action="{{ route('registrar.section.update', $student->id) }}" style="
                                display:grid;
                                grid-template-columns: 1fr 1fr auto;
                                gap:12px;
                                align-items:center;
                                padding:12px;
                            ">
                                @csrf

                                <input
                                    type="text"
                                    name="section"
                                    value="{{ $student->section }}"
                                    placeholder="Example: A"
                                    style="padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px;"
                                    required
                                >

                                <input
                                    type="text"
                                    name="school_year"
                                    value="{{ $student->school_year }}"
                                    placeholder="2025-2026"
                                    style="padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px;"
                                    required
                                >

                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; color:#64748b;">No approved students yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection