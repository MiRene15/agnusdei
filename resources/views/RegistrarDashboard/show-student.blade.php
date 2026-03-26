@extends('layouts.registrar')

@section('title', 'Student Record')

@section('content')
<div class="page-intro">
    <h4>Student Record</h4>
    <p>Review student details, SHS track, tuition readiness, PTC status, and voucher confirmation progress.</p>
</div>

@if(session('success'))<div class="card" style="border-left:4px solid #16a34a; color:#166534;">{{ session('success') }}</div>@endif
@if(session('error'))<div class="card" style="border-left:4px solid #dc2626; color:#991b1b;">{{ session('error') }}</div>@endif

@php
    $latestTuition = $student->tuitionFees->sortByDesc('school_year')->first();
    $isShs = in_array(trim((string) ($student->grade_level ?? '')), ['Grade 11', 'Grade 12'], true);
    $voucherSubmitted = $student->admission && $student->admission->requirements->where('requirement_name', 'SHS Voucher')->where('submitted', 1)->isNotEmpty();
@endphp

<div class="grid-2">
    <div class="card">
        <h4>Student Information</h4>
        <div class="grid-3">
            <div class="section-box"><h5>Student Number</h5><p>{{ $student->student_number }}</p></div>
            <div class="section-box"><h5>Institutional Email</h5><p>{{ $student->email ?? '-' }}</p></div>
            <div class="section-box"><h5>Portal Access</h5><p>{{ ucfirst($student->portal_access_status ?? 'locked') }}</p></div>
        </div>
        <div class="grid-3" style="margin-top:16px;">
            <div class="section-box"><h5>Name</h5><p>{{ $student->first_name }} {{ $student->last_name }}</p></div>
            <div class="section-box"><h5>LRN</h5><p>{{ $student->lrn ?? '-' }}</p></div>
            <div class="section-box"><h5>Status</h5><p style="text-transform:capitalize;">{{ str_replace('_', ' ', $student->status ?? '-') }}</p></div>
        </div>
        <div class="grid-3" style="margin-top:16px;">
            <div class="section-box"><h5>Grade Level</h5><p>{{ $student->grade_level ?? '-' }}</p></div>
            <div class="section-box"><h5>SHS Track</h5><p>{{ $student->shs_track ?? 'Not Applicable' }}</p></div>
            <div class="section-box"><h5>Section</h5><p>{{ $student->section ?? 'Not Assigned' }}</p></div>
        </div>
        <div class="section-box" style="margin-top:16px;">
            <h5>Admission Snapshot</h5>
            <p><strong>Application No.:</strong> {{ $student->admission->application_number ?? '-' }}</p>
            <p><strong>Preferred Grade Level:</strong> {{ $student->admission->applying_for_grade ?? '-' }}</p>
            <p><strong>Senior High Track:</strong> {{ $student->admission->shs_track ?? 'Not Applicable' }}</p>
            <p><strong>Previous School Type:</strong> {{ $student->previous_school_type ? ucfirst($student->previous_school_type) : '-' }}</p>
            <p><strong>Honor Rank:</strong> {{ $student->honor_rank ? $student->honor_rank : 'None' }}</p>
            <p><strong>Verified:</strong> {{ ($student->admission && $student->admission->is_verified) ? 'Yes' : 'No' }}</p>
            <p><strong>PTC Completed:</strong> {{ $student->ptc_completed ? 'Yes' : 'No' }}</p>
            <p><strong>Transferred:</strong> {{ $student->is_transferred ? 'Yes' : 'No' }}</p>
            @if($student->transfer_notes)<p><strong>Transfer Notes:</strong> {{ $student->transfer_notes }}</p>@endif
        </div>
    </div>

    <div class="card">
        <h4>Registrar Actions</h4>
        <div class="section-box" style="margin-bottom:16px;">
            <h5>Billing Readiness</h5>
            <p><strong>Down Payment Cleared:</strong> {{ $latestTuition && $latestTuition->is_downpayment_cleared ? 'Yes' : 'No' }}</p>
            <p><strong>Total Due:</strong> {{ $latestTuition ? 'PHP ' . number_format($latestTuition->total_due, 2) : 'No billing record' }}</p>
            <p><strong>Balance:</strong> {{ $latestTuition ? 'PHP ' . number_format($latestTuition->balance, 2) : 'No billing record' }}</p>
            <p><strong>Payment Plan:</strong> {{ $latestTuition ? ucfirst($latestTuition->payment_plan ?? 'monthly') : 'No billing record' }}</p>
            <p><strong>Discount:</strong> {{ $latestTuition && $latestTuition->discount_type ? ucwords(str_replace('_', ' ', $latestTuition->discount_type)) . ' / PHP ' . number_format($latestTuition->discount_amount ?? 0, 2) : 'None' }}</p>
            <p><strong>Voucher Status:</strong> {{ $latestTuition ? ucwords(str_replace('_', ' ', $latestTuition->voucher_status ?? 'not_applicable')) : 'No billing record' }}</p>
        </div>
        <div style="display:flex; flex-direction:column; gap:12px;">
            @if(!$student->ptc_completed)
                <form method="POST" action="{{ route('registrar.students.ptc', $student->id) }}">@csrf<button type="submit" class="btn btn-success" style="width:100%;">Mark PTC Completed</button></form>
            @else
                <div class="btn btn-outline" style="width:100%; text-align:center; pointer-events:none; opacity:.75;">PTC Already Completed</div>
            @endif

            @if($isShs && $latestTuition)
                <div class="section-box">
                    <h5>SHS Voucher Checklist</h5>
                    <p><strong>Voucher Submitted:</strong> {{ $voucherSubmitted ? 'Yes' : 'No' }}</p>
                    <p><strong>Registrar Confirmed:</strong> {{ $latestTuition->voucher_registrar_verified_at ? $latestTuition->voucher_registrar_verified_at->format('M d, Y h:i A') : 'Pending' }}</p>
                    <p><strong>Cashier Confirmed:</strong> {{ $latestTuition->voucher_cashier_verified_at ? $latestTuition->voucher_cashier_verified_at->format('M d, Y h:i A') : 'Pending' }}</p>
                    @if($voucherSubmitted && !$latestTuition->voucher_registrar_verified_at)
                        <form method="POST" action="{{ route('registrar.students.voucher', [$student->id, $latestTuition->id]) }}" style="margin-top:12px;">@csrf<button type="submit" class="btn btn-primary" style="width:100%;">Confirm SHS Voucher</button></form>
                    @elseif(!$voucherSubmitted)
                        <p style="margin-top:12px; color:#64748b;">Waiting for the student to submit the SHS voucher requirement.</p>
                    @else
                        <p style="margin-top:12px; color:#166534;">Registrar-side voucher review is complete.</p>
                    @endif
                </div>
            @endif

            @if(!$student->is_transferred)
                <form method="POST" action="{{ route('registrar.students.transfer', $student->id) }}" style="display:grid; gap:10px;">@csrf<textarea name="transfer_notes" rows="3" class="form-control" placeholder="Enter transfer notes or receiving-school details" required style="padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px;">{{ old('transfer_notes') }}</textarea><button type="submit" class="btn btn-danger" style="width:100%;">Mark as Transferred</button></form>
            @else
                <div class="btn btn-outline" style="width:100%; text-align:center; pointer-events:none; opacity:.75;">Student Already Marked as Transferred</div>
            @endif
            <a href="{{ route('registrar.students') }}" class="btn btn-outline" style="text-align:center;">Back to Student Records</a>
        </div>
    </div>
</div>

<div class="card">
    <h4>Tuition Timeline</h4>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>School Year</th>
                    <th>Annual Tuition</th>
                    <th>Carryover</th>
                    <th>Total Due</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Plan</th>
                    <th>Discount</th>
                    <th>Voucher Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($student->tuitionFees->sortByDesc('school_year') as $tuition)
                    <tr>
                        <td>{{ $tuition->school_year }}</td>
                        <td>PHP {{ number_format($tuition->total_amount, 2) }}</td>
                        <td>PHP {{ number_format($tuition->previous_balance, 2) }}</td>
                        <td>PHP {{ number_format($tuition->total_due, 2) }}</td>
                        <td>PHP {{ number_format($tuition->paid_amount, 2) }}</td>
                        <td>PHP {{ number_format($tuition->balance, 2) }}</td>
                        <td>{{ ucfirst($tuition->payment_plan ?? 'monthly') }}</td>
                        <td>{{ $tuition->discount_type ? ucwords(str_replace('_', ' ', $tuition->discount_type)) . ' / PHP ' . number_format($tuition->discount_amount ?? 0, 2) : '-' }}</td>
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
                    <tr><td colspan="10" style="text-align:center; color:#64748b;">No tuition records found yet.</td></tr>
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
