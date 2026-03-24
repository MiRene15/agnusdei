@extends('layouts.admin')

@section('title', 'Announcements')

@section('content')

<div class="page-intro">
    <h4>Announcements</h4>
    <p>Create, review, and manage school-wide announcements for different audiences.</p>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Create Announcement</h4>

        <form method="POST" action="{{ route('admin.announcements.store') }}">
            @csrf

            <div class="form-group">
                <label for="title">Title</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control"
                    placeholder="Enter announcement title"
                    value="{{ old('title') }}"
                >
            </div>

            <div class="form-group">
                <label for="audience">Audience</label>
                <select id="audience" name="audience" class="form-control">
                    <option value="all" {{ old('audience') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="students" {{ old('audience') == 'students' ? 'selected' : '' }}>Students</option>
                    <option value="teachers" {{ old('audience') == 'teachers' ? 'selected' : '' }}>Teachers</option>
                    <option value="parents" {{ old('audience') == 'parents' ? 'selected' : '' }}>Parents</option>
                    <option value="cashiers" {{ old('audience') == 'cashiers' ? 'selected' : '' }}>Cashiers</option>
                    <option value="registrars" {{ old('audience') == 'registrars' ? 'selected' : '' }}>Registrars</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea
                    id="message"
                    name="message"
                    class="form-control"
                    rows="6"
                    placeholder="Write your announcement message here..."
                >{{ old('message') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Post Announcement</button>
        </form>
    </div>

    <div class="card">
        <h4>Announcement Preview</h4>
        <ul class="mini-list">
            @forelse($announcements->take(5) as $a)
                <li>
                    <strong>{{ $a->title }}</strong><br>
                    {{ ucfirst($a->audience) }}
                    @if($a->posted_at)
                        • {{ $a->posted_at->format('M d, Y h:i A') }}
                    @endif
                </li>
            @empty
                <li>No announcements posted yet.</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="card">
    <h4>All Announcements</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Audience</th>
                    <th>Message</th>
                    <th>Posted At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $a)
                    <tr>
                        <td>{{ $a->title }}</td>
                        <td>{{ ucfirst($a->audience) }}</td>
                        <td>{{ $a->message }}</td>
                        <td>
                            {{ $a->posted_at ? $a->posted_at->format('M d, Y h:i A') : '-' }}
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.announcements.delete', $a->id) }}" onsubmit="return confirm('Delete this announcement?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No announcements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($announcements, 'links'))
        <div style="margin-top: 18px;">
            {{ $announcements->links() }}
        </div>
    @endif
</div>

@endsection