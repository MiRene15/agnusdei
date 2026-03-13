@extends('layouts.cashier')

@section('title', 'Payments')

@section('content')
<div class="page-intro">
    <h4>Payments</h4>
    <p>View and record student payment transactions.</p>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Receipt No.</th>
                    <th>Student Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>OR-2026-001</td>
                    <td>Maria Santos</td>
                    <td>₱12,500</td>
                    <td>Mar 13, 2026</td>
                    <td><span class="badge badge-paid">Paid</span></td>
                </tr>
                <tr>
                    <td>OR-2026-002</td>
                    <td>John Dela Cruz</td>
                    <td>₱8,000</td>
                    <td>Mar 13, 2026</td>
                    <td><span class="badge badge-paid">Paid</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection