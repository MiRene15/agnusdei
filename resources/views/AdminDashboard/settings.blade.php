@extends('layouts.admin')

@section('title', 'Settings')

@section('content')

<div class="page-intro">
    <h4>Settings</h4>
    <p>Update your profile</p>
</div>

<div class="card">
    <h4>Profile</h4>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $admin->name }}">
        </div>

        <div class="form-group">
            <label>Contact</label>
            <input type="text" name="contact_number" class="form-control" value="{{ $admin->contact_number }}">
        </div>

        <div class="grid-2">
            <input type="password" name="password" class="form-control" placeholder="New Password">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
        </div>

        <br>
        <button class="btn btn-primary">Save</button>
    </form>
</div>

@endsection