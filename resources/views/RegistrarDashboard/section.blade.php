@extends('layouts.registrar')

@section('title','Sectioning')

@section('content')

<div class="page-intro">
    <h4>Student Sectioning</h4>
    <p>Assign sections and school year to approved students.</p>
</div>

@if(session('success'))
<div class="card" style="border-left:4px solid #16a34a;">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <h4>Approved Students</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student Number</th>
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
                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td>{{ $student->grade_level }}</td>

                        <form method="POST" action="{{ route('registrar.section.update', $student->id) }}">
                            @csrf

                            <td>
                                <input
                                    type="text"
                                    name="section"
                                    value="{{ $student->section }}"
                                    placeholder="Example: A"
                                    style="padding:8px;border:1px solid #cbd5e1;border-radius:6px;width:80px;"
                                    required
                                >
                            </td>

                            <td>
                                <input
                                    type="text"
                                    name="school_year"
                                    value="{{ $student->school_year }}"
                                    placeholder="2025-2026"
                                    style="padding:8px;border:1px solid #cbd5e1;border-radius:6px;width:120px;"
                                    required
                                >
                            </td>

                            <td>
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </td>
                        </form>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No approved students yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection