@extends('layouts.cashier')

@section('title', 'Receive Payment')

@section('content')
<div class="container">
    <h3>Receive Payment</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-3 mb-3">
        <p><strong>Student:</strong> {{ $tuitionFee->student->first_name }} {{ $tuitionFee->student->last_name }}</p>
        <p><strong>Student Number:</strong> {{ $tuitionFee->student->student_number }}</p>
        <p><strong>School Year:</strong> {{ $tuitionFee->school_year }}</p>
        <p><strong>Total Amount:</strong> {{ number_format($tuitionFee->total_amount, 2) }}</p>
        <p><strong>Paid Amount:</strong> {{ number_format($tuitionFee->paid_amount, 2) }}</p>
        <p><strong>Balance:</strong> {{ number_format($tuitionFee->balance, 2) }}</p>
    </div>

    <form action="{{ route('cashier.payments.store', $tuitionFee->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Payment Date</label>
            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <select name="payment_method" class="form-control" required>
                <option value="Cash">Cash</option>
                <option value="GCash">GCash</option>
                <option value="Bank Transfer">Bank Transfer</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Reference No.</label>
            <input type="text" name="reference_no" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save Payment</button>
    </form>
</div>
@endsection