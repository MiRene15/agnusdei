@extends('layouts.cashier')

@section('title', 'Receive Payment')

@section('content')

<div class="page-intro">
    <h4>Receive Payment</h4>
    <p>Record a new payment transaction for the selected billing account.</p>
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

@if($errors->any())
    <div class="card" style="border-left:4px solid #f59e0b; color:#92400e;">
        <strong>Please fix the following:</strong>
        <ul style="margin:8px 0 0 18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid-2">
    <div class="card">
        <h4 style="margin-bottom:14px;">Billing Details</h4>

        <div style="display:grid; gap:10px;">
            <div><strong>Student:</strong> {{ $tuition->student->first_name ?? '-' }} {{ $tuition->student->last_name ?? '' }}</div>
            <div><strong>Student Number:</strong> {{ $tuition->student->student_number ?? '-' }}</div>
            <div><strong>LRN:</strong> {{ $tuition->student->lrn ?? '-' }}</div>
            <div><strong>School Year:</strong> {{ $tuition->school_year }}</div>
            <div><strong>Total Due:</strong> ₱{{ number_format($tuition->total_due ?? $tuition->total_amount, 2) }}</div>
            <div><strong>Paid Amount:</strong> ₱{{ number_format($tuition->paid_amount, 2) }}</div>
            <div><strong>Balance:</strong> ₱{{ number_format($tuition->balance, 2) }}</div>
            <div><strong>Required Down Payment:</strong> ₱{{ number_format($tuition->down_payment_required ?? 0, 2) }}</div>
        </div>
    </div>

    <div class="card">
        <h4 style="margin-bottom:14px;">Payment Form</h4>

        <form action="{{ route('cashier.payments.store', $tuition->id) }}" method="POST" style="display:grid; gap:14px;">
            @csrf

            <div>
                <label style="display:block; margin-bottom:6px; font-weight:600;">Amount</label>
                <input
                    type="number"
                    step="0.01"
                    min="1"
                    max="{{ $tuition->balance }}"
                    name="amount"
                    value="{{ old('amount') }}"
                    class="form-control"
                    required
                >
            </div>

            <div>
                <label style="display:block; margin-bottom:6px; font-weight:600;">Payment Method</label>
                <select name="payment_method" class="form-control" required>
                    <option value="">Select payment method</option>
                    <option value="Cash" {{ old('payment_method') === 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="GCash" {{ old('payment_method') === 'GCash' ? 'selected' : '' }}>GCash</option>
                    <option value="Bank Transfer" {{ old('payment_method') === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                </select>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Save Payment</button>
                <a href="{{ route('cashier.billing') }}" class="btn btn-outline">Back to Billing</a>
            </div>
        </form>
    </div>
</div>

@endsection