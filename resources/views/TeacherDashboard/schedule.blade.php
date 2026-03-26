@extends('layouts.teacher')

@section('title', 'Teaching Schedule')

@section('content')
<div class="page-intro">
    <h4>Teaching Schedule</h4>
    <p>View your weekly teaching schedule with advisory classes clearly marked.</p>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject</th>
                    <th>Advisory</th>
                    <th>Grade Level</th>
                    <th>Section</th>
                    <th>Day</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Room</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule['subject_code'] }}</td>
                        <td>{{ $schedule['subject_name'] }}</td>
                        <td>{{ !empty($schedule['is_advisory']) ? 'Yes' : 'No' }}</td>
                        <td>{{ $schedule['grade_level'] }}</td>
                        <td>{{ $schedule['section'] }}</td>
                        <td>{{ $schedule['day_of_week'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule['start_time'])->format('h:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule['end_time'])->format('h:i A') }}</td>
                        <td>{{ $schedule['room'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center; color:#64748b;">No schedule found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
