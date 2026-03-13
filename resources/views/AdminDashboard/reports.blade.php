@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="page-intro">
    <h4>Reports</h4>
    <p>Review system reports, user statistics, and operational summaries.</p>
</div>

<div class="card">
    <h4>Available Reports</h4>
    <ul class="mini-list">
        <li>Enrollment Summary Report</li>
        <li>Student Population Report</li>
        <li>User Account Activity Report</li>
        <li>School Year Status Report</li>
    </ul>
</div>
@endsection