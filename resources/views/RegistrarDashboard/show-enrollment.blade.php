@extends('layouts.registrar')

@section('title', 'View Enrollment')

@section('content')
<div class="page-intro">
    <h4>Enrollment Details</h4>
    <p>Review applicant information, submitted requirements, and current admission status.</p>
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

<div class="card">
    <h4>Admission Information</h4>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:18px;">
        <div>
            <div style="font-size:13px; color:#64748b; margin-bottom:6px;">Application Number</div>
            <div style="font-size:16px; font-weight:600; color:#0f172a;">{{ $admission->application_number }}</div>
        </div>

        <div>
            <div style="font-size:13px; color:#64748b; margin-bottom:6px;">LRN</div>
            <div style="font-size:16px; font-weight:600; color:#0f172a;">{{ $admission->lrn ?? '-' }}</div>
        </div>

        <div>
            <div style="font-size:13px; color:#64748b; margin-bottom:6px;">Status</div>
            <div style="font-size:16px; font-weight:600; color:#0f172a; text-transform:capitalize;">
                {{ str_replace('_', ' ', $admission->status) }}
            </div>
        </div>

        <div>
            <div style="font-size:13px; color:#64748b; margin-bottom:6px;">Application Date</div>
            <div style="font-size:16px; font-weight:600; color:#0f172a;">
                {{ $admission->application_date ? \Carbon\Carbon::parse($admission->application_date)->format('M d, Y') : '-' }}
            </div>
        </div>

        <div>
            <div style="font-size:13px; color:#64748b; margin-bottom:6px;">First Name</div>
            <div style="font-size:16px; font-weight:600; color:#0f172a;">{{ $admission->first_name }}</div>
        </div>

        <div>
            <div style="font-size:13px; color:#64748b; margin-bottom:6px;">Last Name</div>
            <div style="font-size:16px; font-weight:600; color:#0f172a;">{{ $admission->last_name }}</div>
        </div>

        <div>
            <div style="font-size:13px; color:#64748b; margin-bottom:6px;">Birth Date</div>
            <div style="font-size:16px; font-weight:600; color:#0f172a;">
                {{ $admission->birth_date ? \Carbon\Carbon::parse($admission->birth_date)->format('M d, Y') : '-' }}
            </div>
        </div>

        <div>
            <div style="font-size:13px; color:#64748b; margin-bottom:6px;">Sex</div>
            <div style="font-size:16px; font-weight:600; color:#0f172a;">{{ $admission->sex ?? '-' }}</div>
        </div>

        <div>
            <div style="font-size:13px; color:#64748b; margin-bottom:6px;">Email</div>
            <div style="font-size:16px; font-weight:600; color:#0f172a;">{{ $admission->email ?? '-' }}</div>
        </div>

        <div>
            <div style="font-size:13px; color:#64748b; margin-bottom:6px;">Phone</div>
            <div style="font-size:16px; font-weight:600; color:#0f172a;">{{ $admission->phone ?? '-' }}</div>
        </div>

        <div>
            <div style="font-size:13px; color:#64748b; margin-bottom:6px;">Applying for Grade</div>
            <div style="font-size:16px; font-weight:600; color:#0f172a;">{{ $admission->applying_for_grade }}</div>
        </div>

        <div>
            <div style="font-size:13px; color:#64748b; margin-bottom:6px;">Previous School</div>
            <div style="font-size:16px; font-weight:600; color:#0f172a;">{{ $admission->previous_school ?? '-' }}</div>
        </div>
    </div>

    <div style="margin-top:20px;">
        <div style="font-size:13px; color:#64748b; margin-bottom:6px;">Address</div>
        <div style="font-size:15px; font-weight:500; color:#0f172a;">{{ $admission->address ?? '-' }}</div>
    </div>

    <div style="margin-top:20px;">
        <div style="font-size:13px; color:#64748b; margin-bottom:6px;">Remarks</div>
        <div style="font-size:15px; font-weight:500; color:#0f172a;">{{ $admission->remarks ?? '-' }}</div>
    </div>
</div>

<div class="card">
    <h4>Submitted Requirements</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Requirement</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th>Submitted Date</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admission->requirements as $requirement)
                    <tr>
                        <td>{{ $requirement->requirement_name }}</td>
                        <td>{{ $requirement->submitted ? 'Yes' : 'No' }}</td>
                        <td style="text-transform:capitalize;">{{ $requirement->status ?? '-' }}</td>
                        <td>{{ $requirement->submitted_at ? \Carbon\Carbon::parse($requirement->submitted_at)->format('M d, Y h:i A') : '-' }}</td>
                        <td>
                            @if($requirement->file_path)
                                <a href="{{ asset('storage/' . $requirement->file_path) }}" target="_blank" class="btn btn-outline">
                                    View File
                                </a>
                            @else
                                <span style="color:#64748b;">No file uploaded</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No requirements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h4>Actions</h4>

    <div style="display:flex; gap:12px; flex-wrap:wrap;">
        <a href="{{ route('registrar.enrollments') }}" class="btn btn-outline">
            Back to Enrollments
        </a>

        <form method="POST" action="{{ route('registrar.enrollments.approve', $admission->id) }}">
            @csrf
            <button type="submit" class="btn btn-success">
                Approve Admission
            </button>
        </form>

        <form method="POST" action="{{ route('registrar.enrollments.incomplete', $admission->id) }}">
            @csrf
            <button type="submit" class="btn btn-danger">
                Mark as Incomplete
            </button>
        </form>
    </div>
</div>
@endsection