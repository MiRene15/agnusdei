@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: auto; padding: 40px 20px;">
    <h2>Admission Requirements</h2>

    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    @if(session('info'))
        <div class="alert alert-info mb-3">{{ session('info') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul style="margin:0; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p><strong>Application Number:</strong> {{ $admission->application_number }}</p>
    <p><strong>Admission Status:</strong> {{ $admission->status }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Requirement</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Submitted Date</th>
                <th>File</th>
                <th>Upload</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admission->requirements as $req)
                <tr>
                    <td>{{ $req->requirement_name }}</td>
                    <td>{{ $req->status }}</td>
                    <td>{{ $req->submitted ? 'Yes' : 'No' }}</td>
                    <td>{{ $req->submitted_at ?? '-' }}</td>
                    <td>
                        @if($req->file_path)
                            <a href="{{ asset('storage/' . $req->file_path) }}" target="_blank">View File</a>
                        @else
                            No file uploaded
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('student.requirements.upload') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="requirement_id" value="{{ $req->id }}">
                            <input type="file" name="document" class="form-control mb-2" required>
                            <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection