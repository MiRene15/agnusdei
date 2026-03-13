@extends('layouts.parent')

@section('title', 'Parent Dashboard')

@section('content')
<div class="page-intro">
    <h4>Parent Dashboard</h4>
    <p>Monitor your child's records, grades, and billing information.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Children Linked</div>
        <div class="stat-value">2</div>
        <div class="stat-sub">Registered student accounts</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Pending Payments</div>
        <div class="stat-value">1</div>
        <div class="stat-sub">Billing notices</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Latest Grades</div>
        <div class="stat-value">Updated</div>
        <div class="stat-sub">Quarter 2 records available</div>
    </div>
</div>

<div class="card">
    <h4>Welcome</h4>
    <p style="color:#64748b;">Use the left menu to check children profiles, grades, and billing details.</p>
</div>
@endsection