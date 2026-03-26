@extends('layouts.cashier')

@section('title', 'Cashier Dashboard')

@section('content')

<div class="page-intro">
    <h4>Cashier Dashboard</h4>
    <p>Monitor billing records, collections, and pending accounts.</p>
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

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Collected</div>
        <div class="stat-value">₱{{ number_format($totalCollected, 2) }}</div>
        <div class="stat-sub">All recorded payment transactions</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Payment Count</div>
        <div class="stat-value">{{ $paymentCount }}</div>
        <div class="stat-sub">Number of payment entries</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Pending Accounts</div>
        <div class="stat-value">{{ $pendingAccounts }}</div>
        <div class="stat-sub">Billing records with remaining balance</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h4 style="margin-bottom:14px;">Quick Actions</h4>

        <div style="display:flex; flex-wrap:wrap; gap:10px;">
            <a href="{{ route('cashier.billing') }}" class="btn btn-primary">Billing</a>
            <a href="{{ route('cashier.payments') }}" class="btn btn-outline">View Payments</a>
            <a href="{{ route('cashier.reports') }}" class="btn btn-outline">Reports</a>
        </div>
    </div>

    <div class="card">
        <h4 style="margin-bottom:14px;">Quick Search</h4>

        <form action="{{ route('cashier.billing') }}" method="GET" class="search-row">
            <input type="text" name="search" placeholder="Search student name / LRN / student no..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <p style="margin-top:10px; font-size:13px; color:#64748b;">
            This will redirect to Billing with filtered results.
        </p>
    </div>
</div>

    <div class="card">
        <h4 style="margin-bottom:14px;">Cashier Notes</h4>
        <ul class="mini-list">
            <li>Use the Billing page to view student tuition balances.</li>
            <li>Use the Payments page to review all recorded collections.</li>
            <li>Portal access unlocks once down payment is cleared.</li>
        </ul>
    </div>
</div>

@endsection