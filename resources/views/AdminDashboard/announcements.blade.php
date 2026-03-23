@extends('layouts.admin')

@section('title', 'Announcements')

@section('content')

<div class="page-intro">
    <h4>Announcements</h4>
</div>

<div class="card">
    <h4>Create</h4>

    <form method="POST" action="{{ route('admin.announcements.store') }}">
        @csrf

        <input type="text" name="title" class="form-control" placeholder="Title"><br>

        <select name="audience" class="form-control">
            <option value="all">All</option>
            <option value="students">Students</option>
            <option value="teachers">Teachers</option>
        </select><br>

        <textarea name="message" class="form-control" rows="4"></textarea><br>

        <button class="btn btn-primary">Post</button>
    </form>
</div>

<div class="card">
    <h4>All Announcements</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Audience</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($announcements as $a)
                    <tr>
                        <td>{{ $a->title }}</td>
                        <td>{{ $a->audience }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.announcements.delete', $a->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection