@extends('layouts.registrar')

@section('title', 'Application Details')

@section('content')
@php
    $status = strtolower((string) $admission->status);
    $isApproved = $status === 'approved';
    $submittedCount = $admission->requirements->where('submitted', 1)->count();
    $totalRequirements = $admission->requirements->count();
@endphp

<div class="page-intro">
    <h4>Application Details</h4>
    <p>Review the applicant profile, track the requirement checklist, and decide whether the admission should be verified, approved, or marked incomplete.</p>
</div>

@if(session('success'))
    <div class="card" style="border-left:4px solid #16a34a; color:#166534;">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="card" style="border-left:4px solid #dc2626; color:#991b1b;">{{ session('error') }}</div>
@endif

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Application Number</div>
        <div class="stat-value" style="font-size:20px;">{{ $admission->application_number }}</div>
        <div class="stat-sub">Current registrar application reference</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Status</div>
        <div class="stat-value" style="font-size:20px;">{{ strtoupper(str_replace('_', ' ', $status)) }}</div>
        <div class="stat-sub">Verified: {{ $admission->is_verified ? 'Yes' : 'No' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Requirements Submitted</div>
        <div class="stat-value">{{ $submittedCount }} / {{ $totalRequirements }}</div>
        <div class="stat-sub">Documents currently uploaded by the applicant</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Applying For</div>
        <div class="stat-value" style="font-size:20px;">{{ $admission->applying_for_grade }}</div>
        <div class="stat-sub">{{ $admission->shs_track ?: 'No SHS track selected' }}</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Applicant Profile</h4>
        <div class="grid-3">
            <div class="stat-card"><div class="stat-label">Applicant</div><div class="stat-value" style="font-size:18px;">{{ $admission->first_name }} {{ $admission->last_name }}</div><div class="stat-sub">Full registered name</div></div>
            <div class="stat-card"><div class="stat-label">LRN</div><div class="stat-value" style="font-size:18px;">{{ $admission->lrn ?? '-' }}</div><div class="stat-sub">Learner reference number</div></div>
            <div class="stat-card"><div class="stat-label">Application Date</div><div class="stat-value" style="font-size:18px;">{{ $admission->application_date ? \Carbon\Carbon::parse($admission->application_date)->format('M d, Y') : '-' }}</div><div class="stat-sub">Initial filing date</div></div>
        </div>

        <div class="card" style="margin-top:16px; margin-bottom:0; background:linear-gradient(180deg, #ffffff, #f8fbff);">
            <h4 style="margin-bottom:12px;">Applicant Details</h4>
            <div style="display:grid; gap:10px; color:#334155;">
                <div><strong>Birth Date:</strong> {{ $admission->birth_date ?? '-' }}</div>
                <div><strong>Sex:</strong> {{ $admission->sex ?? '-' }}</div>
                <div><strong>Email:</strong> {{ $admission->email ?? '-' }}</div>
                <div><strong>Institutional Email:</strong> {{ $admission->institutional_email ?? 'Not generated yet' }}</div>
                <div><strong>Phone:</strong> {{ $admission->phone ?? '-' }}</div>
                <div><strong>Address:</strong> {{ $admission->address ?? '-' }}</div>
                <div><strong>Previous School:</strong> {{ $admission->previous_school ?? '-' }}</div>
                <div><strong>Remarks:</strong> {{ $admission->remarks ?? 'No registrar remarks yet.' }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <h4>Registrar Actions</h4>
        <div class="quick-actions">
            @if(!$admission->is_verified && !$isApproved)
                <form method="POST" action="{{ route('registrar.enrollments.verify', $admission->id) }}">@csrf<button type="submit" class="btn btn-outline" style="width:100%;">Verify Admission</button></form>
            @endif

            @if($admission->is_verified && !$isApproved)
                <form method="POST" action="{{ route('registrar.enrollments.approve', $admission->id) }}">@csrf<button type="submit" class="btn btn-primary" style="width:100%;">Approve Admission</button></form>
            @endif

            @if(!$isApproved)
                <form method="POST" action="{{ route('registrar.enrollments.incomplete', $admission->id) }}">@csrf<button type="submit" class="btn btn-outline" style="width:100%;">Mark Incomplete</button></form>
            @else
                <div class="action-box" style="pointer-events:none; opacity:.8;"><h5>Approved</h5><p>This admission is already approved and locked.</p></div>
            @endif

            <a href="{{ route('registrar.enrollments') }}" class="btn btn-outline" style="width:100%;">Back to Enrollment Requests</a>
        </div>
    </div>
</div>

<div class="card">
    <h4>Requirement Checklist</h4>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Requirement</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Submitted Date</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admission->requirements as $req)
                    @php $reqStatus = strtolower((string) $req->status); @endphp
                    <tr>
                        <td>{{ $req->requirement_name }}</td>
                        <td>
                            @if($reqStatus === 'approved')
                                <span class="badge badge-approved">Approved</span>
                            @elseif($reqStatus === 'submitted')
                                <span class="badge badge-review">Submitted</span>
                            @elseif($reqStatus === 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @else
                                <span class="badge badge-incomplete">{{ ucfirst($reqStatus ?: 'unknown') }}</span>
                            @endif
                        </td>
                        <td>{{ $req->submitted ? 'Yes' : 'No' }}</td>
                        <td>{{ $req->submitted_at ? \Carbon\Carbon::parse($req->submitted_at)->format('M d, Y h:i A') : '-' }}</td>
                        <td>
                            @if($req->file_path)
                                <a href="{{ asset('storage/' . $req->file_path) }}" target="_blank" class="btn btn-outline">View File</a>
                            @else
                                <span style="color:#64748b;">No file uploaded</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#64748b;">No requirements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
