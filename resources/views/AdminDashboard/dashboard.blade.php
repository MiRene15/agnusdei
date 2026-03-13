@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-intro">
    <h4>Admin Dashboard</h4>
    <p>Manage system users, settings, reports, and overall ERP activity.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Users</div>
        <div class="stat-value">320</div>
        <div class="stat-sub">All registered accounts</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Active Students</div>
        <div class="stat-value">210</div>
        <div class="stat-sub">Currently enrolled students</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Teachers</div>
        <div class="stat-value">24</div>
        <div class="stat-sub">Faculty accounts</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Registrars</div>
        <div class="stat-value">5</div>
        <div class="stat-sub">Office staff accounts</div>
    </div>
</div>

<div class="card">
    <h4>System Overview</h4>
    <p style="color:#64748b;">Welcome to the Admin Dashboard. Use the side menu to manage users, edit settings, and generate reports.</p>
</div>
@endsection