@extends('layouts.cashier')

@section('title', 'Cashier Dashboard')

@section('content')
<div class="page-intro">
    <h4>Cashier Dashboard</h4>
    <p>Track collections, pending balances, and student payment records.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Payments Today</div>
        <div class="stat-value">18</div>
        <div class="stat-sub">Processed transactions</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Amount Collected</div>
        <div class="stat-value">₱54,000</div>
        <div class="stat-sub">Today's total collections</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Pending Balances</div>
        <div class="stat-value">42</div>
        <div class="stat-sub">Students with dues</div>
    </div>
</div>

<div class="card">
    <h4>Quick Overview</h4>
    <p style="color:#64748b;">Use the cashier portal to review billing, post payments, and generate collection reports.</p>
</div>
@endsection