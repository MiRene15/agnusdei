@extends('layouts.registrar')

@section('title', 'Registrar Dashboard')

@section('content')
<div class="page-intro">
    <h4>Registrar Dashboard</h4>
    <p>Track admission activity, move quickly into applicant review, and manage sectioning and student records from one board.</p>
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
        <div class="stat-sub">Applications needing follow-up</div>
    </div>
</div>

<div class="quick-actions" style="margin-bottom:24px;">
    <a href="{{ route('registrar.enrollments') }}" class="action-box">
        <h5>Review Applications</h5>
        <p>Open applicant files, verify records, and move admissions forward.</p>
    </a>
    <a href="{{ route('registrar.students') }}" class="action-box">
        <h5>Open Student Records</h5>
        <p>Inspect enrolled students, tuition status, PTC state, and transfers.</p>
    </a>
    <a href="{{ route('registrar.section') }}" class="action-box">
        <h5>Launch Sectioning</h5>
        <p>Assign eligible students to aligned sections and classes quickly.</p>
    </a>
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
                        @php $status = strtolower((string) $admission->status); @endphp
                        <tr>
                            <td>{{ $admission->application_number }}</td>
                            <td>{{ $admission->first_name }} {{ $admission->last_name }}</td>
                            <td>{{ $admission->applying_for_grade }}</td>
                            <td>
                                @if($status === 'approved')
                                    <span class="badge badge-approved">Approved</span>
                                @elseif($status === 'under_review')
                                    <span class="badge badge-review">Under Review</span>
                                @elseif($status === 'incomplete')
                                    <span class="badge badge-incomplete">Incomplete</span>
                                @else
                                    <span class="badge badge-pending">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                @endif
                            </td>
                            <td><a href="{{ route('registrar.enrollments.show', $admission->id) }}" class="btn btn-primary">View</a></td>
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
        <h4>Registrar Workflow</h4>
        <ul class="mini-list">
            <li>Verify the application first so billing can be prepared by cashier.</li>
            <li>Approve only when the required documents are complete.</li>
            <li>Use sectioning after payment clearance and class alignment are ready.</li>
            <li>Use student records for PTC, voucher, carryover, and transfer decisions.</li>
        </ul>
    </div>
</div>
@endsection
