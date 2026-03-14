@extends('layouts.registrar')

@section('title', 'Registrar Dashboard')

@section('content')

<div class="page-intro">
    <h4>Registrar Dashboard</h4>
    <p>Monitor applications, review recent submissions, and manage student admissions efficiently.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Applicants</div>
        <div class="stat-value">{{ $totalApplicants }}</div>
        <div class="stat-sub">All admission applications received</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Pending Applicants</div>
        <div class="stat-value">{{ $pendingApplicants }}</div>
        <div class="stat-sub">Awaiting registrar review</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Approved Applicants</div>
        <div class="stat-value">{{ $approvedApplicants }}</div>
        <div class="stat-sub">Already accepted and processed</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Incomplete Requirements</div>
        <div class="stat-value">{{ $incompleteApplicants }}</div>
        <div class="stat-sub">Need missing or corrected documents</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Recent Applications</h4>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Application No.</th>
                        <th>Applicant</th>
                        <th>Grade Level</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAdmissions as $admission)
                        <tr>
                            <td>{{ $admission->application_number }}</td>
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
                            <td>
                                <a href="{{ route('registrar.enrollments.show', $admission->id) }}" class="btn btn-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#64748b;">No recent applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h4>Quick Guide</h4>
        <ul class="mini-list">
            <li>Review all student-submitted admission forms.</li>
            <li>Approve applications only after all requirements are complete.</li>
            <li>Use sectioning to assign approved students to their sections.</li>
            <li>Student records update automatically after approval.</li>
        </ul>
    </div>
</div>

@endsection