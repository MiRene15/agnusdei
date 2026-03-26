@extends('layouts.cashier')

@section('title', 'Billing')

@section('content')

<div class="page-intro">
    <h4>Billing Records</h4>
    <p>Review student tuition balances and proceed to payment collection.</p>
</div>

<div class="card">
    <form method="GET" action="{{ route('cashier.billing') }}" class="search-row">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by student number, LRN, name, or school year"
        >

        <select name="status" class="form-control" style="max-width: 220px;">
            <option value="">All Statuses</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
            <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            <option value="voucher" {{ request('status') === 'voucher' ? 'selected' : '' }}>Voucher</option>
            <option value="cleared" {{ request('status') === 'cleared' ? 'selected' : '' }}>Down Payment Cleared</option>
            <option value="not_cleared" {{ request('status') === 'not_cleared' ? 'selected' : '' }}>Down Payment Not Cleared</option>
        </select>

        <button type="submit" class="btn btn-primary">Search</button>
        <a href="{{ route('cashier.billing') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student Number</th>
                    <th>LRN</th>
                    <th>Name</th>
                    <th>School Year</th>
                    <th>Total Due</th>
                    <th>Paid Amount</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Down Payment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($billings as $billing)
                    <tr>
                        <td>{{ $billing->student->student_number ?? '-' }}</td>
                        <td>{{ $billing->student->lrn ?? '-' }}</td>
                        <td>
                            {{ $billing->student->first_name ?? '-' }}
                            {{ $billing->student->last_name ?? '' }}
                        </td>
                        <td>{{ $billing->school_year }}</td>
                        <td>₱{{ number_format($billing->total_due ?? $billing->total_amount, 2) }}</td>
                        <td>₱{{ number_format($billing->paid_amount, 2) }}</td>
                        <td>₱{{ number_format($billing->balance, 2) }}</td>
                        <td>
    @php
        $status = strtolower($billing->status ?? 'unpaid');
    @endphp

    @if($status === 'paid')
        <span class="badge badge-success">Paid</span>
    @elseif($status === 'partial')
        <span class="badge badge-info">Partial</span>
    @elseif($status === 'voucher')
        <span class="badge badge-warning">Voucher</span>
    @else
        <span class="badge badge-danger">Unpaid</span>
    @endif

    <div style="margin-top:6px;">
        @if($billing->is_downpayment_cleared)
            <span class="badge badge-success">Cleared</span>
        @else
            <span class="badge badge-warning">Not Cleared</span>
        @endif
    </div>
</td>
                        <td>
                            @if($billing->is_downpayment_cleared)
                                <span class="badge badge-success">Cleared</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if((float) $billing->balance > 0)
                                <a href="{{ route('cashier.payments.create', $billing->id) }}" class="btn btn-primary">
                                    Receive Payment
                                </a>
                            @else
                                <span style="color:#64748b;">Fully Paid</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align:center; color:#64748b;">No billing records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:18px;">
        {{ $billings->links() }}
    </div>
</div>

@endsection