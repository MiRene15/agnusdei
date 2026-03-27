@extends('layouts.parent')

@section('title', 'Parent Dashboard')

@section('content')
<div class="page-intro">
    <h4>Parent Dashboard</h4>
    <p>Monitor linked students, academic progress, and billing status from one cleaner dashboard.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Parent Account</div>
        <div class="stat-value" style="font-size:20px;">{{ $parent ? $parent->first_name . ' ' . $parent->last_name : 'Not Linked' }}</div>
        <div class="stat-sub">Registered parent profile</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Children</div>
        <div class="stat-value">{{ $totalChildren }}</div>
        <div class="stat-sub">Students linked to this account</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Outstanding Balance</div>
        <div class="stat-value">PHP {{ number_format($totalBalance, 2) }}</div>
        <div class="stat-sub">Combined remaining balance</div>
    </div>
</div>

<div class="quick-actions" style="margin-bottom:24px;">
    <a href="{{ route('parent.children') }}" class="action-box">
        <h5>Open Children List</h5>
        <p>Review every linked student profile and current school placement.</p>
    </a>
    <a href="{{ route('parent.grades') }}" class="action-box">
        <h5>Check Grades</h5>
        <p>See subject grades and monitor academic progress quickly.</p>
    </a>
    <a href="{{ route('parent.billing') }}" class="action-box">
        <h5>Review Billing</h5>
        <p>Track balances, payments, and current tuition standing.</p>
    </a>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Children Overview</h4>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>LRN</th>
                        <th>Name</th>
                        <th>Grade Level</th>
                        <th>Section</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($children as $child)
                        <tr>
                            <td>{{ $child->lrn ?? '-' }}</td>
                            <td>{{ $child->first_name }} {{ $child->last_name }}</td>
                            <td>{{ $child->grade_level ?? '-' }}</td>
                            <td>{{ $child->section ?? '-' }}</td>
                            <td>{{ ucfirst($child->status ?? '-') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#64748b;">No linked children found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h4>Quick Notes</h4>
        <ul class="mini-list">
            <li>Use `My Children` to inspect linked student profiles.</li>
            <li>Use `Grades` to monitor quarter-based academic performance.</li>
            <li>Use `Billing` to review tuition status and recent payment history.</li>
        </ul>
    </div>
</div>
@endsection
