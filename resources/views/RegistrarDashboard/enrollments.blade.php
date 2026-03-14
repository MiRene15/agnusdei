@extends('layouts.registrar')

@section('title', 'Enrollment Requests')

@section('content')

<div class="page-intro">
    <h4>Enrollment Requests</h4>
    <p>Review, filter, and manage incoming admission applications.</p>
</div>

<div class="card">
    <form method="GET" action="{{ route('registrar.enrollments') }}" class="search-row">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by application number or student name"
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

        <select name="status">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="incomplete" {{ request('status') == 'incomplete' ? 'selected' : '' }}>Incomplete</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('registrar.enrollments') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card">
    <h4>Application List</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Application No.</th>
                    <th>LRN</th>
                    <th>Applicant</th>
                    <th>Grade Level</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admissions as $admission)
                    <tr>
                        <td>{{ $admission->application_number }}</td>
                        <td>{{ $admission->lrn ?? '-' }}</td>
                        <td>{{ $admission->first_name }} {{ $admission->last_name }}</td>
                        <td>{{ $admission->applying_for_grade }}</td>
                        <td>
                            @if($admission->status === 'approved')
                                <span class="badge badge-approved">Approved</span>
                            @elseif($admission->status === 'under_review')
                                <span class="badge badge-review">Under Review</span>
                            @elseif($admission->status === 'incomplete')
                                <span class="badge badge-incomplete">Incomplete</span>
                            @else
                                <span class="badge badge-pending">{{ ucfirst($admission->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $admission->application_date ?? '-' }}</td>
                        <td>
                            <a href="{{ route('registrar.enrollments.show', $admission->id) }}" class="btn btn-primary">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; color:#64748b;">No applications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:18px;">
        {{ $admissions->links() }}
    </div>
</div>

@endsection