@extends('layouts.cashier')

@section('title', 'Billing')

@section('content')
<div class="page-intro" style="margin-bottom:20px;">
    <h4>Billing Control</h4>
    <p>Yearly tuition now computes automatically, including approved carryover balances from past school years.</p>
</div>

<div class="card" style="margin-bottom:18px;">
    <form method="GET" action="{{ route('cashier.billing') }}" class="search-row" style="align-items:center; gap:12px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student number, LRN, name, track, or school year">
        <select name="status" class="form-control" style="max-width:240px;">
            <option value="">All statuses</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
            <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            <option value="voucher" {{ request('status') === 'voucher' ? 'selected' : '' }}>Voucher</option>
            <option value="cleared" {{ request('status') === 'cleared' ? 'selected' : '' }}>Down payment cleared</option>
            <option value="not_cleared" {{ request('status') === 'not_cleared' ? 'selected' : '' }}>Waiting for unlock payment</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('cashier.billing') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card" style="padding:0; overflow:hidden; border:1px solid #e5e7eb; border-radius:22px;">
    <div style="display:flex; justify-content:space-between; gap:16px; align-items:center; padding:20px 22px; border-bottom:1px solid #eef2f7; background:linear-gradient(180deg, #ffffff, #fafaf9);">
        <div>
            <h4 style="margin:0;">Student Billing Records</h4>
            <p style="margin:6px 0 0; color:#64748b;">Annual tuition, approved carryover, and installment readiness at a glance.</p>
        </div>
    </div>

    <div class="table-wrap" style="margin:0;">
        <table style="min-width:1400px;">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>School Year</th>
                    <th>Annual Tuition</th>
                    <th>Approved Carryover</th>
                    <th>Monthly Plan</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Unlock Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($billings as $billing)
                    @php $student = $billing->student; @endphp
                    <tr>
                        <td>
                            <div style="display:grid; gap:4px;">
                                <strong>{{ $student->first_name ?? '-' }} {{ $student->last_name ?? '' }}</strong>
                                <span style="color:#64748b;">{{ $student->student_number ?? '-' }}</span>
                                <span style="color:#94a3b8; font-size:13px;">{{ $student->grade_level ?? '-' }}{{ $student->shs_track ? ' | ' . $student->shs_track : '' }}</span>
                            </div>
                        </td>
                        <td>{{ $billing->school_year }}</td>
                        <td>PHP {{ number_format($billing->total_amount, 2) }}</td>
                        <td>PHP {{ number_format($billing->previous_balance, 2) }}</td>
                        <td>
                            <div>DP: PHP {{ number_format($billing->down_payment_required, 2) }}</div>
                            <div style="color:#64748b; font-size:13px;">Monthly: PHP {{ number_format($billing->monthly_payment, 2) }}</div>
                        </td>
                        <td>PHP {{ number_format($billing->paid_amount, 2) }}</td>
                        <td>PHP {{ number_format($billing->balance, 2) }}</td>
                        <td>
                            @if($billing->is_downpayment_cleared)
                                <span class="badge badge-success">Dashboard Unlock Ready</span>
                            @else
                                <span class="badge badge-warning">Waiting for Down Payment</span>
                            @endif
                            <div style="margin-top:6px; color:#64748b; font-size:13px;">Need PHP {{ number_format(max(0, (float) $billing->down_payment_required - (float) $billing->paid_amount), 2) }} more</div>
                        </td>
                        <td>
                            @if((float) $billing->balance > 0)
                                <a href="{{ route('cashier.payments.create', $billing->id) }}" class="btn btn-primary">Receive Payment</a>
                            @else
                                <span style="color:#64748b; font-weight:600;">Fully Paid</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" style="text-align:center; padding:28px; color:#64748b;">No billing records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:18px 22px; border-top:1px solid #eef2f7;">{{ $billings->links() }}</div>
</div>
@endsection
