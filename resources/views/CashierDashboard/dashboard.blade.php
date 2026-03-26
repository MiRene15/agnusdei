@extends('layouts.cashier')

@section('title', 'Cashier Dashboard')

@section('content')
<div class="page-intro" style="margin-bottom:20px;">
    <h4>Cashier Dashboard</h4>
    <p>Monitor computed school-year tuition, process face-to-face payments, and keep tuition tracking clean and audit-ready.</p>
</div>

@if(session('success'))
    <div class="card" style="border-left:4px solid #16a34a; color:#166534; margin-bottom:16px;">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="card" style="border-left:4px solid #dc2626; color:#991b1b; margin-bottom:16px;">{{ session('error') }}</div>
@endif

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:18px;">
    <div class="card" style="background:linear-gradient(135deg, #fffaf5, #ffffff); border:1px solid #fed7aa;">
        <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#9a3412;">Total Collected</div>
        <div style="font-size:28px; font-weight:700; color:#7c2d12; margin-top:6px;">PHP {{ number_format($totalCollected, 2) }}</div>
        <div style="color:#9a3412; margin-top:6px;">All posted tuition payments</div>
    </div>
    <div class="card" style="background:linear-gradient(135deg, #f0fdf4, #ffffff); border:1px solid #bbf7d0;">
        <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#166534;">Cash Collections</div>
        <div style="font-size:28px; font-weight:700; color:#14532d; margin-top:6px;">PHP {{ number_format($cashCollections, 2) }}</div>
        <div style="color:#166534; margin-top:6px;">Face-to-face cash received</div>
    </div>
    <div class="card" style="background:linear-gradient(135deg, #eff6ff, #ffffff); border:1px solid #bfdbfe;">
        <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#1d4ed8;">Payment Count</div>
        <div style="font-size:28px; font-weight:700; color:#1e3a8a; margin-top:6px;">{{ $paymentCount }}</div>
        <div style="color:#1d4ed8; margin-top:6px;">Recorded cashier transactions</div>
    </div>
    <div class="card" style="background:linear-gradient(135deg, #faf5ff, #ffffff); border:1px solid #ddd6fe;">
        <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#6d28d9;">Pending Accounts</div>
        <div style="font-size:28px; font-weight:700; color:#5b21b6; margin-top:6px;">{{ $pendingAccounts }}</div>
        <div style="color:#6d28d9; margin-top:6px;">Students with remaining balance</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h4 style="margin-bottom:14px;">Quick Actions</h4>
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap:12px;">
            <a href="{{ route('cashier.billing') }}" class="btn btn-primary" style="text-align:center;">Open Billing Board</a>
            <a href="{{ route('cashier.payments') }}" class="btn btn-outline" style="text-align:center;">Review Payments</a>
            <a href="{{ route('cashier.reports') }}" class="btn btn-outline" style="text-align:center;">Collection Reports</a>
        </div>
    </div>

    <div class="card">
        <h4 style="margin-bottom:14px;">Quick Search</h4>
        <form action="{{ route('cashier.billing') }}" method="GET" class="search-row" style="align-items:center; gap:12px;">
            <input type="text" name="search" placeholder="Search student, LRN, student number, or school year" value="{{ request('search') }}">
            <select name="status" class="form-control" style="max-width:220px;">
                <option value="">All statuses</option>
                <option value="not_cleared">Waiting for unlock</option>
                <option value="cleared">Down payment cleared</option>
                <option value="partial">Partial</option>
                <option value="paid">Paid</option>
            </select>
            <button type="submit" class="btn btn-primary">Search Billing</button>
        </form>
        <p style="margin-top:10px; font-size:13px; color:#64748b;">Use this to jump straight into the student billing ledger and receive payment in person.</p>
    </div>
</div>

<div class="card">
    <h4 style="margin-bottom:14px;">Cashiering Notes</h4>
    <ul class="mini-list">
        <li>Verified students can pay down payment or full payment through the cashier.</li>
        <li>Portal access unlocks automatically when the required amount is cleared.</li>
        <li>Cash transactions now support tendered amount and change tracking for audit accuracy.</li>
    </ul>
</div>
@endsection

