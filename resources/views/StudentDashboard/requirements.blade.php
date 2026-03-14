<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admission Requirements</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family:'Poppins', sans-serif;
            background:#f4f7fb;
            color:#1e293b;
            min-height:100vh;
            padding:40px 20px;
        }

        .page-wrap {
            max-width:1100px;
            margin:0 auto;
        }

        .top-actions {
            display:flex;
            justify-content:space-between;
            align-items:center;
            flex-wrap:wrap;
            gap:12px;
            margin-bottom:20px;
        }

        .top-actions h2 {
            color:#001e82;
            font-size:28px;
            font-weight:700;
        }

        .btn {
            display:inline-block;
            padding:11px 18px;
            border:none;
            border-radius:10px;
            text-decoration:none;
            font-size:14px;
            font-weight:600;
            cursor:pointer;
            font-family:inherit;
            transition:0.2s ease;
        }

        .btn-primary {
            background:#001e82;
            color:#ffffff;
        }

        .btn-primary:hover {
            background:#1636a3;
        }

        .btn-danger {
            background:#dc2626;
            color:#ffffff;
        }

        .btn-danger:hover {
            background:#b91c1c;
        }

        .card {
            background:#ffffff;
            border-radius:20px;
            box-shadow:0 12px 30px rgba(0,0,0,0.08);
            overflow:hidden;
        }

        .card-header {
            background:linear-gradient(135deg, #001e82, #1636a3);
            color:#ffffff;
            padding:30px;
        }

        .card-header p {
            margin-top:8px;
            font-size:15px;
            opacity:0.92;
        }

        .card-body {
            padding:30px;
        }

        .alert {
            border-radius:12px;
            padding:14px 16px;
            margin-bottom:18px;
            font-size:14px;
            font-weight:500;
        }

        .alert-success {
            background:#dcfce7;
            color:#166534;
            border:1px solid #bbf7d0;
        }

        .alert-info {
            background:#dbeafe;
            color:#1d4ed8;
            border:1px solid #bfdbfe;
        }

        .alert-danger {
            background:#fee2e2;
            color:#991b1b;
            border:1px solid #fecaca;
        }

        .info-grid {
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
            gap:18px;
            margin-bottom:28px;
        }

        .info-box {
            background:#f8fafc;
            border:1px solid #e2e8f0;
            border-radius:16px;
            padding:20px;
        }

        .info-box .label {
            font-size:13px;
            color:#64748b;
            margin-bottom:8px;
        }

        .info-box .value {
            font-size:18px;
            font-weight:700;
            color:#0f172a;
        }

        .status-box {
            margin-bottom:25px;
            border-radius:14px;
            padding:20px;
        }

        .status-progress {
            background:#fff7ed;
            border:1px solid #fed7aa;
        }

        .status-progress h4 {
            color:#9a3412;
            margin-bottom:6px;
        }

        .status-progress p {
            color:#7c2d12;
            font-size:14px;
            line-height:1.6;
        }

        .status-complete {
            background:#ecfdf5;
            border:1px solid #bbf7d0;
        }

        .status-complete h4 {
            color:#166534;
            margin-bottom:6px;
        }

        .status-complete p {
            color:#166534;
            font-size:14px;
            line-height:1.6;
        }

        .instruction-box {
            margin-bottom:24px;
            background:#f8fafc;
            border:1px solid #e2e8f0;
            border-radius:14px;
            padding:18px 20px;
            color:#475569;
            font-size:14px;
            line-height:1.7;
        }

        .table-wrap {
            overflow-x:auto;
            border:1px solid #e2e8f0;
            border-radius:18px;
        }

        table {
            width:100%;
            border-collapse:collapse;
            min-width:950px;
            background:#ffffff;
        }

        thead tr {
            background:#001e82;
            color:#ffffff;
        }

        th {
            padding:16px;
            text-align:left;
            font-size:14px;
            font-weight:600;
        }

        td {
            padding:16px;
            font-size:14px;
            color:#334155;
            border-bottom:1px solid #e2e8f0;
            vertical-align:top;
        }

        .badge {
            display:inline-block;
            padding:6px 12px;
            border-radius:999px;
            font-size:12px;
            font-weight:600;
            text-transform:capitalize;
        }

        .badge-approved {
            background:#dcfce7;
            color:#166534;
        }

        .badge-submitted {
            background:#dbeafe;
            color:#1d4ed8;
        }

        .badge-pending {
            background:#fef3c7;
            color:#92400e;
        }

        .badge-default {
            background:#f1f5f9;
            color:#475569;
        }

        .mini-label {
            display:inline-block;
            background:#dcfce7;
            color:#166534;
            padding:8px 12px;
            border-radius:10px;
            font-size:12px;
            font-weight:600;
        }

        .file-link {
            color:#001e82;
            font-weight:600;
            text-decoration:none;
        }

        .muted {
            color:#94a3b8;
        }

        .footer-note {
            margin-top:24px;
            color:#64748b;
            font-size:14px;
            line-height:1.7;
        }
    </style>
</head>
<body>

<div class="page-wrap">

    <div class="top-actions">
        <h2>Admission Requirements</h2>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Exit / Logout</button>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 style="font-size:30px; font-weight:600;">Admission Requirements</h2>
            <p>Upload and manage the required documents for your admission application.</p>
        </div>

        <div class="card-body">

            @php
                $totalRequirements = $admission->requirements->count();
                $submittedRequirements = $admission->requirements->where('submitted', 1)->count();
                $allRequirementsSubmitted = $totalRequirements > 0 && $submittedRequirements === $totalRequirements;
            @endphp

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach ($errors->all() as $error)
                            <li style="margin-bottom:4px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="info-grid">
                <div class="info-box">
                    <div class="label">Application Number</div>
                    <div class="value">{{ $admission->application_number }}</div>
                </div>

                <div class="info-box">
                    <div class="label">Admission Status</div>
                    <div class="value" style="text-transform:capitalize;">
                        {{ str_replace('_', ' ', $admission->status) }}
                    </div>
                </div>

                <div class="info-box">
                    <div class="label">Applicant</div>
                    <div class="value">{{ $admission->first_name ?? '' }} {{ $admission->last_name ?? '' }}</div>
                </div>
            </div>

            @if(!$allRequirementsSubmitted)
                <div class="status-box status-progress">
                    <h4>Application In Progress</h4>
                    <p>
                        Your admission application is currently being processed.
                        Please proceed on-site for completion of the remaining admission steps.
                    </p>
                    <div style="margin-top:14px;">
                        <button type="button" class="btn" style="background:#ea580c; color:#fff;">
                            Proceed On-Site
                        </button>
                    </div>
                </div>
            @else
                <div class="status-box status-complete">
                    <h4>Requirements Completed</h4>
                    <p>
                        All admission requirements have been submitted successfully.
                        Your application is now waiting for registrar approval.
                    </p>
                </div>
            @endif

            <div class="instruction-box">
                <strong style="color:#334155;">Instructions:</strong><br>
                @if(!$allRequirementsSubmitted)
                    Please upload all missing requirements in clear and readable format.
                    After completing your uploads, wait for the registrar to review your application or proceed on-site if instructed.
                @else
                    No further uploads are needed at this time.
                    Please wait for the registrar to verify your submitted documents and approve your application.
                @endif
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Requirement</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Submitted Date</th>
                            <th>File</th>
                            <th>Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admission->requirements as $req)
                            <tr>
                                <td style="font-weight:500; color:#0f172a;">
                                    {{ $req->requirement_name }}
                                </td>

                                <td>
                                    @if($req->status === 'approved')
                                        <span class="badge badge-approved">{{ $req->status }}</span>
                                    @elseif($req->status === 'submitted')
                                        <span class="badge badge-submitted">{{ $req->status }}</span>
                                    @elseif($req->status === 'pending')
                                        <span class="badge badge-pending">{{ $req->status }}</span>
                                    @else
                                        <span class="badge badge-default">{{ $req->status ?? 'N/A' }}</span>
                                    @endif
                                </td>

                                <td>{{ $req->submitted ? 'Yes' : 'No' }}</td>

                                <td>{{ $req->submitted_at ?? '-' }}</td>

                                <td>
                                    @if($req->file_path)
                                        <a href="{{ asset('storage/' . $req->file_path) }}" target="_blank" class="file-link">
                                            View File
                                        </a>
                                    @else
                                        <span class="muted">No file uploaded</span>
                                    @endif
                                </td>

                                <td>
                                    @if($allRequirementsSubmitted)
                                        <span class="mini-label">Waiting for Approval</span>
                                    @elseif(!$req->submitted)
                                        <form method="POST" action="{{ route('student.requirements.upload') }}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="requirement_id" value="{{ $req->id }}">

                                            <input type="file" name="document" required style="
                                                width:100%;
                                                margin-bottom:10px;
                                                font-size:13px;
                                                color:#334155;
                                            ">

                                            <button type="submit" class="btn btn-primary">
                                                Upload
                                            </button>
                                        </form>
                                    @else
                                        <span class="mini-label">Completed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding:24px; text-align:center; color:#64748b; font-size:14px;">
                                    No requirements found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="footer-note">
                <strong style="color:#334155;">Reminder:</strong>
                Please upload clear and readable files only. Once all required documents are submitted, your application will proceed for review.
            </div>
        </div>
    </div>
</div>

</body>
</html>
