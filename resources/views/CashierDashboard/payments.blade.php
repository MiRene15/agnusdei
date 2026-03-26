@extends('layouts.cashier')

@section('title', 'Payments')

@section('content')

<div class="page-intro">
    <h4>Payment Records</h4>
    <p>Track all recorded payment transactions.</p>
</div>

<div class="card">
    <form method="GET" action="{{ route('cashier.payments') }}" class="search-row">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by student, LRN, receipt no., reference no., or method"
        >
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="{{ route('cashier.payments') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>LRN</th>
                    <th>Payment Date</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Reference No.</th>
                    <th>Receipt No.</th>
                    <th>Received By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>
                            {{ $payment->tuitionFee->student->first_name ?? '-' }}
                            {{ $payment->tuitionFee->student->last_name ?? '' }}
                        </td>
                        <td>{{ $payment->tuitionFee->student->lrn ?? '-' }}</td>
                        <td>{{ $payment->payment_date ?? '-' }}</td>
                        <td>₱{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->payment_method ?? '-' }}</td>
                        <td>{{ $payment->reference_no ?? '-' }}</td>
                        <td>{{ $payment->receipt_number ?? '-' }}</td>
                        <td>{{ $payment->received_by ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; color:#64748b;">No payment records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:18px;">
        {{ $payments->links() }}
    </div>
</div>

@endsection