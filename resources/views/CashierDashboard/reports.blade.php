@extends('layouts.cashier')

@section('title', 'Reports')

@section('content')
<div class="page-intro">
    <h4>Reports</h4>
    <p>Access daily collection summaries and billing reports.</p>
</div>

<div class="card">
    <h4>Available Reports</h4>
    <ul style="color:#64748b; line-height: 2; padding-left: 20px;">
        <li>Daily Collection Report</li>
        <li>Outstanding Balance Report</li>
        <li>Student Payment History</li>
        <li>Monthly Cash Summary</li>
    </ul>
</div>
@endsection