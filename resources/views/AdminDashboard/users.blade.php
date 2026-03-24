@extends('layouts.admin')

@section('title', 'User Management')

@section('content')

<div class="page-intro">
    <h4>User Management</h4>
    <p>Search, filter, and review the users registered in the system.</p>
</div>

<div class="card">
    <h4>Filter Users</h4>

    <form method="GET" action="{{ route('admin.users') }}">
        <div class="grid-2">
            <div class="form-group">
                <label for="search">Search</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    class="form-control"
                    placeholder="Search by name, email, role, or contact number"
                    value="{{ request('search') }}"
                >
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" class="form-control">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="registrar" {{ request('role') == 'registrar' ? 'selected' : '' }}>Registrar</option>
                    <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="parent" {{ request('role') == 'parent' ? 'selected' : '' }}>Parent</option>
                    <option value="cashier" {{ request('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Apply Filter</button>
    </form>
</div>

<div class="card">
    <h4>Users List</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Role</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->contact_number ?? '-' }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($users, 'links'))
        <div style="margin-top: 18px;">
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection