@extends('layouts.registrar')

@section('title', 'Registrar Dashboard')

@section('content')
<div class="page-intro">
    <h4>Dashboard Overview</h4>
    <p>Monitor admissions, student records, pending requirements, and sectioning activities.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Applicants</div>
        <div class="stat-value">245</div>
        <div class="stat-sub">This admission cycle</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Pending Review</div>
        <div class="stat-value">42</div>
        <div class="stat-sub">Applications awaiting review</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Approved Students</div>
        <div class="stat-value">180</div>
        <div class="stat-sub">Ready for enrollment</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Incomplete Requirements</div>
        <div class="stat-value">23</div>
        <div class="stat-sub">Documents still missing</div>
    </div>
</div>

<div class="card">
    <h4>Quick Actions</h4>
    <div class="quick-actions">
        <a href="{{ route('registrar.enrollments') }}" class="action-box">
            <h5>Review Applications</h5>
            <p>Check newly submitted enrollment and admission requests.</p>
        </a>

        <a href="{{ route('registrar.students') }}" class="action-box">
            <h5>Manage Records</h5>
            <p>Open the student records table and review profiles quickly.</p>
        </a>

        <a href="{{ route('registrar.sectioning') }}" class="action-box">
            <h5>Assign Sections</h5>
            <p>Place students into sections and organize class distribution.</p>
        </a>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Recent Admission Applications</h4>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Application No.</th>
                        <th>Name</th>
                        <th>Grade Level</th>
                        <th>Status</th>
                        <th>Date Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>APP-2026-001</td>
                        <td>Maria Santos</td>
                        <td>Grade 11</td>
                        <td><span class="badge badge-pending">Pending</span></td>
                        <td>Mar 10, 2026</td>
                    </tr>
                    <tr>
                        <td>APP-2026-002</td>
                        <td>John Dela Cruz</td>
                        <td>Grade 9</td>
                        <td><span class="badge badge-review">Under Review</span></td>
                        <td>Mar 10, 2026</td>
                    </tr>
                    <tr>
                        <td>APP-2026-003</td>
                        <td>Angela Reyes</td>
                        <td>Grade 12</td>
                        <td><span class="badge badge-approved">Approved</span></td>
                        <td>Mar 09, 2026</td>
                    </tr>
                    <tr>
                        <td>APP-2026-004</td>
                        <td>Kevin Flores</td>
                        <td>Grade 8</td>
                        <td><span class="badge badge-incomplete">Incomplete</span></td>
                        <td>Mar 09, 2026</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h4>Today's Tasks</h4>
        <ul class="mini-list">
            <li>Verify newly uploaded documents for Grade 11 applicants.</li>
            <li>Approve pending student profiles for section assignment.</li>
            <li>Coordinate with cashier for cleared enrollment records.</li>
            <li>Print summary report for today’s processed applications.</li>
        </ul>
    </div>
</div>
@endsection