@extends('layouts.cashier')

@section('title', 'Billing')

@section('content')
<div class="page-intro">
    <h4>Billing</h4>
    <p>Review student balances, due dates, and payment status.</p>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Invoice No.</th>
                    <th>Amount Due</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Maria Santos</td>
                    <td>INV-2026-001</td>
                    <td>₱12,500</td>
                    <td>Mar 20, 2026</td>
                    <td><span class="badge badge-pending">Pending</span></td>
                </tr>
                <tr>
                    <td>Kevin Flores</td>
                    <td>INV-2026-002</td>
                    <td>₱10,000</td>
                    <td>Mar 05, 2026</td>
                    <td><span class="badge badge-overdue">Overdue</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection