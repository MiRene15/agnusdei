@extends('layouts.admin')

@section('title', 'Reference Codes')

@section('content')

<div class="page-intro">
    <h4>Reference Codes</h4>
    <p>Create and manage registration codes for teacher, registrar, cashier, and admin accounts.</p>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Create Reference Code</h4>

        <form method="POST" action="{{ route('admin.reference-codes.store') }}">
            @csrf

            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="">Select Role</option>
                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="registrar" {{ old('role') == 'registrar' ? 'selected' : '' }}>Registrar</option>
                    <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input
                    type="text"
                    name="description"
                    id="description"
                    class="form-control"
                    value="{{ old('description') }}"
                    placeholder="What is this code for?"
                >
            </div>

            <div class="form-group">
                <label for="max_uses">Max Uses</label>
                <input
                    type="number"
                    min="1"
                    name="max_uses"
                    id="max_uses"
                    class="form-control"
                    value="{{ old('max_uses') }}"
                    placeholder="Leave blank for unlimited"
                >
            </div>

            <button type="submit" class="btn btn-primary">Create Reference Code</button>
        </form>
    </div>

    <div class="card">
        <h4>Existing Reference Codes</h4>

        <form method="GET" action="{{ route('admin.reference-codes') }}" class="search-row">
            <select name="role" class="form-control">
                <option value="">All Roles</option>
                <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                <option value="registrar" {{ request('role') == 'registrar' ? 'selected' : '' }}>Registrar</option>
                <option value="cashier" {{ request('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>

            <select name="status" class="form-control">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Used</option>
                <option value="unused" {{ request('status') == 'unused' ? 'selected' : '' }}>Unused</option>
            </select>

            <input
                type="text"
                name="search"
                class="form-control"
                value="{{ request('search') }}"
                placeholder="Search code, role, or description"
            >

            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Role</th>
                        <th>Description</th>
                        <th>Usage</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($codes as $code)
                        <tr>
                            <td style="font-weight:700; color:#001e82;">{{ $code->code }}</td>
                            <td>{{ ucfirst($code->role) }}</td>
                            <td>{{ $code->description ?? 'No description' }}</td>
                            <td>
                                {{ $code->used_count }}
                                @if($code->max_uses)
                                    / {{ $code->max_uses }}
                                @else
                                    / Unlimited
                                @endif
                            </td>
                            <td>{{ $code->is_active ? 'Active' : 'Inactive' }}</td>
                            <td>
                                @if($code->is_active)
                                    <form method="POST" action="{{ route('admin.reference-codes.deactivate', $code->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Deactivate</button>
                                    </form>
                                @else
                                    <span style="color:#94a3b8; font-size:13px;">No action</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; color:#64748b;">No reference codes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:18px;">
            {{ $codes->links() }}
        </div>
    </div>
</div>

@endsection
