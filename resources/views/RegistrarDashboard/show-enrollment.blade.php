@extends('layouts.registrar')

@section('title', 'Application Details')

@section('content')

<div class="page-intro">
    <h4>Application Details</h4>
    <p>Review applicant information, requirements, and update the admission decision.</p>
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

<div class="grid-2">
    <div class="card">
        <h4>Applicant Information</h4>

        <div class="grid-3">
            <div class="section-box">
                <h5>Application No.</h5>
                <p>{{ $admission->application_number }}</p>
            </div>

            <div class="section-box">
                <h5>LRN</h5>
                <p>{{ $admission->lrn ?? '-' }}</p>
            </div>

            <div class="section-box">
                <h5>Status</h5>
                <p style="text-transform:capitalize;">{{ str_replace('_', ' ', $admission->status) }}</p>
            </div>
        </div>

        <div class="grid-3" style="margin-top:16px;">
            <div class="section-box">
                <h5>First Name</h5>
                <p>{{ $admission->first_name }}</p>
            </div>

            <div class="section-box">
                <h5>Last Name</h5>
                <p>{{ $admission->last_name }}</p>
            </div>

            <div class="section-box">
                <h5>Grade Applying For</h5>
                <p>{{ $admission->applying_for_grade }}</p>
            </div>
        </div>

        <div class="grid-3" style="margin-top:16px;">
            <div class="section-box">
                <h5>Birth Date</h5>
                <p>{{ $admission->birth_date ?? '-' }}</p>
            </div>

            <div class="section-box">
                <h5>Sex</h5>
                <p>{{ $admission->sex ?? '-' }}</p>
            </div>

            <div class="section-box">
                <h5>Application Date</h5>
                <p>{{ $admission->application_date ?? '-' }}</p>
            </div>
        </div>

        <div style="margin-top:16px;" class="section-box">
            <h5>Contact Details</h5>
            <p><strong>Email:</strong> {{ $admission->email ?? '-' }}</p>
            <p><strong>Phone:</strong> {{ $admission->phone ?? '-' }}</p>
            <p><strong>Address:</strong> {{ $admission->address ?? '-' }}</p>
            <p><strong>Previous School:</strong> {{ $admission->previous_school ?? '-' }}</p>
        </div>
    </div>

    <div class="card">
        <h4>Registrar Actions</h4>

        <div style="display:flex; flex-direction:column; gap:12px;">
            <form method="POST" action="{{ route('registrar.enrollments.approve', $admission->id) }}">
                @csrf
                <button type="submit" class="btn btn-success" style="width:100%;">Approve Admission</button>
            </form>

            <form method="POST" action="{{ route('registrar.enrollments.incomplete', $admission->id) }}">
                @csrf
                <button type="submit" class="btn btn-danger" style="width:100%;">Mark as Incomplete</button>
            </form>

            <a href="{{ route('registrar.enrollments') }}" class="btn btn-outline" style="text-align:center;">
                Back to Enrollment Requests
            </a>
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
                    <tr>
                        <td>{{ $req->requirement_name }}</td>
                        <td>
                            @if($req->status === 'approved')
                                <span class="badge badge-approved">Approved</span>
                            @elseif($req->status === 'submitted')
                                <span class="badge badge-review">Submitted</span>
                            @elseif($req->status === 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @else
                                <span class="badge badge-incomplete">{{ ucfirst($req->status ?? 'unknown') }}</span>
                            @endif
                        </td>
                        <td>{{ $req->submitted ? 'Yes' : 'No' }}</td>
                        <td>{{ $req->submitted_at ?? '-' }}</td>
                        <td>
                            @if($req->file_path)
                                <a href="{{ asset('storage/' . $req->file_path) }}" target="_blank" class="btn btn-outline">
                                    View File
                                </a>
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