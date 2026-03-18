@extends('layouts.parent')

@section('title', 'My Children')

@section('content')

<div class="page-intro">
    <h4>My Children</h4>
    <p>View all student profiles linked to your parent account.</p>
</div>

<div class="card">
    <h4>Linked Students</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student Number</th>
                    <th>LRN</th>
                    <th>Name</th>
                    <th>Birth Date</th>
                    <th>Gender</th>
                    <th>Grade Level</th>
                    <th>Section</th>
                    <th>School Year</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($children as $child)
                    <tr>
                        <td>{{ $child->student_number }}</td>
                        <td>{{ $child->lrn ?? '-' }}</td>
                        <td>{{ $child->first_name }} {{ $child->last_name }}</td>
                        <td>{{ $child->birth_date ?? '-' }}</td>
                        <td>{{ $child->gender ?? '-' }}</td>
                        <td>{{ $child->grade_level ?? '-' }}</td>
                        <td>{{ $child->section ?? '-' }}</td>
                        <td>{{ $child->school_year ?? '-' }}</td>
                        <td>{{ ucfirst($child->status ?? '-') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center; color:#64748b;">No linked children found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection