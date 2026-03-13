@extends('layouts.parent')

@section('title', 'Billing')

@section('content')
<div class="page-intro">
    <h4>Billing</h4>
    <p>View tuition balances, payment history, and billing notices.</p>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Invoice No.</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Maria Santos</td>
                    <td>INV-2026-001</td>
                    <td>₱12,500</td>
                    <td>Pending</td>
                </tr>
                <tr>
                    <td>John Santos</td>
                    <td>INV-2026-002</td>
                    <td>₱10,000</td>
                    <td>Paid</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection