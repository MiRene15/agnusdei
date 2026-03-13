@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="page-intro">
    <h4>User Management</h4>
    <p>Manage admin, registrar, teacher, parent, and student accounts.</p>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>System Admin</td>
                    <td>admin@school.com</td>
                    <td>Admin</td>
                    <td>Active</td>
                </tr>
                <tr>
                    <td>Main Registrar</td>
                    <td>registrar@school.com</td>
                    <td>Registrar</td>
                    <td>Active</td>
                </tr>
                <tr>
                    <td>Teacher One</td>
                    <td>teacher@school.com</td>
                    <td>Teacher</td>
                    <td>Active</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection