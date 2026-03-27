@extends('layouts.registrar')

@section('title', 'Student Record')

@section('content')
@php
    $latestTuition = $student->tuitionFees->sortByDesc('school_year')->first();
    $isShs = in_array(trim((string) ($student->grade_level ?? '')), ['Grade 11', 'Grade 12'], true);
    $voucherSubmitted = $student->admission && $student->admission->requirements->where('requirement_name', 'SHS Voucher')->where('submitted', 1)->isNotEmpty();
@endphp

<div class="page-intro">
    <h4>Student Record</h4>
    <p>View student identity, section placement, payment readiness, PTC state, and current enrollment details in one registrar workspace.</p>
</div>

@if(session('success'))
    <div class="card" style="border-left:4px solid #16a34a; color:#166534;">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="card" style="border-left:4px solid #dc2626; color:#991b1b;">{{ session('error') }}</div>
@endif

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Student Number</div>
        <div class="stat-value" style="font-size:20px;">{{ $student->student_number }}</div>
        <div class="stat-sub">{{ $student->email ?? 'No institutional email yet' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Current Status</div>
        <div class="stat-value" style="font-size:20px;">{{ strtoupper(str_replace('_', ' ', $student->status ?? '-')) }}</div>
        <div class="stat-sub">Portal: {{ ucfirst($student->portal_access_status ?? 'locked') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Section Placement</div>
        <div class="stat-value" style="font-size:20px;">{{ $student->section ?? 'Pending' }}</div>
        <div class="stat-sub">{{ $student->grade_level ?? '-' }} | {{ $student->school_year ?? '-' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Billing Balance</div>
        <div class="stat-value" style="font-size:20px;">{{ $latestTuition ? 'PHP ' . number_format($latestTuition->balance, 2) : 'No Record' }}</div>
        <div class="stat-sub">{{ $latestTuition && $latestTuition->is_downpayment_cleared ? 'Down payment cleared' : 'Awaiting payment clearance' }}</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Student Identity</h4>
        <div class="grid-3">
            <div class="stat-card"><div class="stat-label">Full Name</div><div class="stat-value" style="font-size:18px;">{{ $student->first_name }} {{ $student->last_name }}</div><div class="stat-sub">Registered student profile</div></div>
            <div class="stat-card"><div class="stat-label">LRN</div><div class="stat-value" style="font-size:18px;">{{ $student->lrn ?? '-' }}</div><div class="stat-sub">Learner reference number</div></div>
            <div class="stat-card"><div class="stat-label">PTC</div><div class="stat-value" style="font-size:18px;">{{ $student->ptc_completed ? 'Completed' : 'Pending' }}</div><div class="stat-sub">{{ $student->ptc_completed_at ? $student->ptc_completed_at->format('M d, Y h:i A') : 'No completion date yet' }}</div></div>
        </div>

        <div class="card" style="margin-top:16px; margin-bottom:0; background:linear-gradient(180deg, #ffffff, #f8fbff);">
            <h4 style="margin-bottom:12px;">Admission Snapshot</h4>
            <div style="display:grid; gap:10px; color:#334155;">
                <div><strong>Application No.:</strong> {{ $student->admission->application_number ?? '-' }}</div>
                <div><strong>Applied Grade Level:</strong> {{ $student->admission->applying_for_grade ?? '-' }}</div>
                <div><strong>SHS Track:</strong> {{ $student->shs_track ?? 'Not Applicable' }}</div>
                <div><strong>Previous School Type:</strong> {{ $student->previous_school_type ? ucfirst($student->previous_school_type) : '-' }}</div>
                <div><strong>Honor Rank:</strong> {{ $student->honor_rank ?: 'None' }}</div>
                <div><strong>Admission Verified:</strong> {{ ($student->admission && $student->admission->is_verified) ? 'Yes' : 'No' }}</div>
                <div><strong>Transferred:</strong> {{ $student->is_transferred ? 'Yes' : 'No' }}</div>
                <div><strong>Withdrawn:</strong> {{ $student->status === 'withdrawn' ? 'Yes' : 'No' }}</div>
                @if($student->withdrawal_effective_at)
                    <div><strong>Withdrawal Effective:</strong> {{ $student->withdrawal_effective_at->format('M d, Y h:i A') }}</div>
                @endif
                @if($student->withdrawal_reason)
                    <div><strong>Withdrawal Reason:</strong> {{ $student->withdrawal_reason }}</div>
                @endif
                @if($student->transfer_notes)
                    <div><strong>Transfer Notes:</strong> {{ $student->transfer_notes }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <h4>Registrar Actions</h4>
        <div class="quick-actions" style="margin-bottom:18px;">
            @if(!$student->ptc_completed)
                <form method="POST" action="{{ route('registrar.students.ptc', $student->id) }}">@csrf<button type="submit" class="btn btn-primary" style="width:100%;">Mark PTC Completed</button></form>
            @else
                <div class="action-box" style="pointer-events:none; opacity:.8;"><h5>PTC Completed</h5><p>This student is already marked as PTC-complete.</p></div>
            @endif

            @if(!$student->is_transferred)
                <button type="button" class="btn btn-outline" style="width:100%;" onclick="document.getElementById('transfer-form-wrap').scrollIntoView({behavior:'smooth'});">Transfer Student</button>
            @else
                <div class="action-box" style="pointer-events:none; opacity:.8;"><h5>Transferred</h5><p>This student is already marked as transferred.</p></div>
            @endif

            @if($student->status !== 'withdrawn')
                <button type="button" class="btn btn-outline" style="width:100%;" onclick="document.getElementById('withdrawal-form-wrap').scrollIntoView({behavior:'smooth'});">Process Withdrawal</button>
            @else
                <div class="action-box" style="pointer-events:none; opacity:.8;"><h5>Withdrawal Recorded</h5><p>This student is already marked as withdrawn for the current school year.</p></div>
            @endif

            <a href="{{ route('registrar.students') }}" class="btn btn-outline" style="width:100%;">Back to Records</a>
        </div>

        <div class="card" style="margin-bottom:16px; background:linear-gradient(180deg, #ffffff, #f8fbff);">
            <h4 style="margin-bottom:12px;">Billing Readiness</h4>
            <div style="display:grid; gap:10px; color:#334155;">
                <div><strong>Down Payment Cleared:</strong> {{ $latestTuition && $latestTuition->is_downpayment_cleared ? 'Yes' : 'No' }}</div>
                <div><strong>Total Due:</strong> {{ $latestTuition ? 'PHP ' . number_format($latestTuition->total_due, 2) : 'No billing record' }}</div>
                <div><strong>Balance:</strong> {{ $latestTuition ? 'PHP ' . number_format($latestTuition->balance, 2) : 'No billing record' }}</div>
                <div><strong>Payment Plan:</strong> {{ $latestTuition ? ucfirst($latestTuition->payment_plan ?? 'monthly') : 'No billing record' }}</div>
                <div><strong>Discount:</strong> {{ $latestTuition && $latestTuition->discount_type ? ucwords(str_replace('_', ' ', $latestTuition->discount_type)) . ' / PHP ' . number_format($latestTuition->discount_amount ?? 0, 2) : 'None' }}</div>
                <div><strong>Voucher Status:</strong> {{ $latestTuition ? ucwords(str_replace('_', ' ', $latestTuition->voucher_status ?? 'not_applicable')) : 'No billing record' }}</div>
            </div>
        </div>

        @if($isShs && $latestTuition)
            <div class="card" style="background:linear-gradient(180deg, #ffffff, #f8fbff);">
                <h4 style="margin-bottom:12px;">SHS Voucher Check</h4>
                <div style="display:grid; gap:10px; color:#334155;">
                    <div><strong>Voucher Submitted:</strong> {{ $voucherSubmitted ? 'Yes' : 'No' }}</div>
                    <div><strong>Registrar Confirmed:</strong> {{ $latestTuition->voucher_registrar_verified_at ? $latestTuition->voucher_registrar_verified_at->format('M d, Y h:i A') : 'Pending' }}</div>
                    <div><strong>Cashier Confirmed:</strong> {{ $latestTuition->voucher_cashier_verified_at ? $latestTuition->voucher_cashier_verified_at->format('M d, Y h:i A') : 'Pending' }}</div>
                </div>
                @if($voucherSubmitted && !$latestTuition->voucher_registrar_verified_at)
                    <form method="POST" action="{{ route('registrar.students.voucher', [$student->id, $latestTuition->id]) }}" style="margin-top:14px;">@csrf<button type="submit" class="btn btn-primary" style="width:100%;">Confirm SHS Voucher</button></form>
                @endif
            </div>
        @endif
    </div>
</div>

<div class="card" id="transfer-form-wrap">
    <h4>Transfer Handling</h4>
    @if(!$student->is_transferred)
        <form method="POST" action="{{ route('registrar.students.transfer', $student->id) }}" style="display:grid; gap:12px;">
            @csrf
            <textarea name="transfer_notes" rows="4" class="form-control" placeholder="Enter transfer notes or receiving-school details" required>{{ old('transfer_notes') }}</textarea>
            <button type="submit" class="btn btn-danger" style="max-width:260px;">Mark as Transferred</button>
        </form>
    @else
        <div style="color:#64748b;">This student is already marked as transferred.</div>
    @endif
</div>

<div class="card" id="withdrawal-form-wrap">
    <h4>Withdrawal Policy Workflow</h4>
    <div style="padding:16px; border:1px solid #fef3c7; border-radius:16px; background:#fffbeb; color:#92400e; margin-bottom:16px;">
        <strong>Policy note:</strong> Withdrawal should be processed only after confirming registrar review, billing implications, and student/guardian acknowledgment. Marking a student withdrawn locks portal access and blocks same-year sectioning changes.
    </div>
    @if($student->status !== 'withdrawn')
        <form method="POST" action="{{ route('registrar.students.withdraw', $student->id) }}" style="display:grid; gap:12px;">
            @csrf
            <textarea name="withdrawal_reason" rows="4" class="form-control" placeholder="Enter the withdrawal reason, effective concern, and any registrar notes" required>{{ old('withdrawal_reason') }}</textarea>
            <label style="display:flex; gap:10px; align-items:flex-start; color:#334155;">
                <input type="checkbox" name="withdrawal_policy_acknowledged" value="1" style="width:auto; margin-top:4px;">
                <span>I confirm that the withdrawal concern was reviewed and the policy implications were acknowledged before processing.</span>
            </label>
            <button type="submit" class="btn btn-danger" style="max-width:260px;">Mark as Withdrawn</button>
        </form>
    @else
        <div style="color:#64748b;">Withdrawal has already been recorded for this student.</div>
    @endif
</div>

<div class="card">
    <h4>Tuition Timeline</h4>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>School Year</th>
                    <th>Total Due</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Plan</th>
                    <th>Voucher</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($student->tuitionFees->sortByDesc('school_year') as $tuition)
                    <tr>
                        <td>{{ $tuition->school_year }}</td>
                        <td>PHP {{ number_format($tuition->total_due, 2) }}</td>
                        <td>PHP {{ number_format($tuition->paid_amount, 2) }}</td>
                        <td>PHP {{ number_format($tuition->balance, 2) }}</td>
                        <td>{{ ucfirst($tuition->payment_plan ?? 'monthly') }}</td>
                        <td>{{ ucwords(str_replace('_', ' ', $tuition->voucher_status ?? 'not_applicable')) }}</td>
                        <td>
                            @if(!$tuition->carryover_approved && (float) $tuition->balance > 0 && $tuition->school_year !== ($student->school_year ?? now()->year . '-' . (now()->year + 1)))
                                <form method="POST" action="{{ route('registrar.students.carryover', [$student->id, $tuition->id]) }}">@csrf<button type="submit" class="btn btn-outline">Approve Carryover</button></form>
                            @else
                                <span style="color:#64748b;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center; color:#64748b;">No tuition records found yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h4>Current Enrollment Snapshot</h4>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Subject</th><th>Class</th><th>Teacher</th><th>School Year</th></tr></thead>
            <tbody>
                @forelse($student->enrollments as $enrollment)
                    <tr>
                        <td>{{ $enrollment->class->subject->subject_name ?? '-' }}</td>
                        <td>{{ $enrollment->class->grade_level ?? '-' }} / {{ $enrollment->class->section ?? '-' }}</td>
                        <td>{{ ($enrollment->class->teacher->first_name ?? '-') }} {{ $enrollment->class->teacher->last_name ?? '' }}</td>
                        <td>{{ $enrollment->class->school_year ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center; color:#64748b;">No class enrollments found yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
