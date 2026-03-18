@extends('layouts.cashier')

@section('title', 'Billing')

@section('content')

<div class="page-intro">
    <h4>Billing Records</h4>
    <p>Review student tuition balances and current billing status.</p>
</div>

<div class="card">
    <form method="GET" action="{{ route('cashier.billing') }}" class="search-row">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by student number, LRN, or name"
        >
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="{{ route('cashier.billing') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card">
    <h4>Student Billing List</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student Number</th>
                    <th>LRN</th>
                    <th>Name</th>
                    <th>School Year</th>
                    <th>Total Amount</th>
                    <th>Paid Amount</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Due Date</th>
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
                        <td>₱{{ number_format($billing->total_amount, 2) }}</td>
                        <td>₱{{ number_format($billing->paid_amount, 2) }}</td>
                        <td>₱{{ number_format($billing->balance, 2) }}</td>
                        <td>{{ ucfirst($billing->status ?? '-') }}</td>
                        <td>{{ $billing->due_date ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center; color:#64748b;">No billing records found.</td>
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