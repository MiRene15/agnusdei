@extends('layouts.registrar')

@section('title', 'Student Records')

@section('content')

<div class="page-intro">
    <h4>Student Records</h4>
    <p>Manage approved student records, section assignments, and school year details.</p>
</div>

<div class="card">
    <form method="GET" action="{{ route('registrar.students') }}" class="search-row">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by student number, LRN, or name"
        >

        <select name="grade_level">
            <option value="">All Grade Levels</option>
            <option value="Nursery" {{ request('grade_level') == 'Nursery' ? 'selected' : '' }}>Nursery</option>
            <option value="Kinder" {{ request('grade_level') == 'Kinder' ? 'selected' : '' }}>Kinder</option>
            <option value="Grade 1" {{ request('grade_level') == 'Grade 1' ? 'selected' : '' }}>Grade 1</option>
            <option value="Grade 2" {{ request('grade_level') == 'Grade 2' ? 'selected' : '' }}>Grade 2</option>
            <option value="Grade 3" {{ request('grade_level') == 'Grade 3' ? 'selected' : '' }}>Grade 3</option>
            <option value="Grade 4" {{ request('grade_level') == 'Grade 4' ? 'selected' : '' }}>Grade 4</option>
            <option value="Grade 5" {{ request('grade_level') == 'Grade 5' ? 'selected' : '' }}>Grade 5</option>
            <option value="Grade 6" {{ request('grade_level') == 'Grade 6' ? 'selected' : '' }}>Grade 6</option>
            <option value="Grade 7" {{ request('grade_level') == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
            <option value="Grade 8" {{ request('grade_level') == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
            <option value="Grade 9" {{ request('grade_level') == 'Grade 9' ? 'selected' : '' }}>Grade 9</option>
            <option value="Grade 10" {{ request('grade_level') == 'Grade 10' ? 'selected' : '' }}>Grade 10</option>
            <option value="Grade 11" {{ request('grade_level') == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
            <option value="Grade 12" {{ request('grade_level') == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
        </select>

        <input
            type="text"
            name="section"
            value="{{ request('section') }}"
            placeholder="Filter by section"
        >

        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('registrar.students') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card">
    <h4>Student List</h4>

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
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $student->student_number }}</td>
                        <td>{{ $student->lrn ?? '-' }}</td>
                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td>{{ $student->grade_level }}</td>
                        <td>{{ $student->section ?? '-' }}</td>
                        <td>{{ $student->school_year ?? '-' }}</td>
                        <td>
                            @if($student->status === 'approved')
                                <span class="badge badge-approved">Approved</span>
                            @elseif($student->status === 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @else
                                <span class="badge badge-review">{{ ucfirst($student->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; color:#64748b;">No students found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:18px;">
        {{ $students->links() }}
    </div>
</div>

@endsection