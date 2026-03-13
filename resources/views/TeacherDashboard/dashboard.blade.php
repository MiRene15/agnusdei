@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="page-intro">
    <h4>Teacher Dashboard</h4>
    <p>Manage classes, encode grades, and review teaching reports.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Assigned Classes</div>
        <div class="stat-value">6</div>
        <div class="stat-sub">Current academic load</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Students</div>
        <div class="stat-value">180</div>
        <div class="stat-sub">Across all classes</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Pending Grades</div>
        <div class="stat-value">12</div>
        <div class="stat-sub">To be encoded</div>
    </div>
</div>

<div class="card">
    <h4>Welcome</h4>
    <p style="color:#64748b;">Use the left menu to manage your classes, grades, and reports.</p>
</div>
@endsection