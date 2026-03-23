@extends('layouts.admin')

@section('title', 'Users')

@section('content')

<div class="page-intro">
    <h4>User Management</h4>
    <p>Search and manage system users.</p>
</div>

<div class="card">
    <h4>Filter</h4>

    <form method="GET">
        <div class="grid-2">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">

            <select name="role" class="form-control">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="registrar">Registrar</option>
                <option value="teacher">Teacher</option>
                <option value="parent">Parent</option>
                <option value="cashier">Cashier</option>
                <option value="student">Student</option>
            </select>
        </div>

        <br>
        <button class="btn btn-primary">Apply</button>
    </form>
</div>

<div class="card">
    <h4>Users</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->contact_number ?? '-' }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <br>
    {{ $users->links() }}
</div>

@endsection