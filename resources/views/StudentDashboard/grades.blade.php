@extends('layouts.student')

@section('title', 'Grades')

@section('content')
<div class="page-intro">
    <h4>My Grades</h4>
    <p>Review your encoded subject grades and switch between all grading periods or a specific quarter.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Visible Records</div>
        <div class="stat-value">{{ $grades->count() }}</div>
        <div class="stat-sub">Filtered grades shown on screen</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Quarter Filter</div>
        <div class="stat-value" style="font-size:20px;">{{ $selectedPeriod !== '' ? $selectedPeriod : 'All Quarters' }}</div>
        <div class="stat-sub">Switch between all periods or one quarter</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Average Final Grade</div>
        <div class="stat-value" style="font-size:20px;">{{ $grades->count() ? number_format($grades->avg(fn ($grade) => (float) ($grade->final_grade ?? $grade->grade)), 2) : 'N/A' }}</div>
        <div class="stat-sub">Based on visible grade records</div>
    </div>
</div>

<div class="card">
    <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; align-items:center; margin-bottom:18px;">
        <div>
            <h4 style="margin-bottom:6px;">Grade Period Filter</h4>
            <p style="color:#64748b; margin:0;">Choose whether to show every grading record or just one quarter at a time.</p>
        </div>
        <form method="GET" action="{{ route('student.grades') }}" class="search-row" style="margin:0; min-width:min(100%, 420px);">
            <select name="period" class="form-control">
                <option value="">All Quarters</option>
                @foreach($periodOptions as $period)
                    <option value="{{ $period }}" @selected($selectedPeriod === $period)>{{ $period }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Apply</button>
            @if($selectedPeriod !== '')
                <a href="{{ route('student.grades') }}" class="btn btn-outline">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Grading Period</th>
                    <th>Seatwork</th>
                    <th>Quiz</th>
                    <th>Exam</th>
                    <th>Final Grade</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $grade)
                    <tr>
                        <td>
                            <div style="display:grid; gap:4px;">
                                <strong>{{ $grade->enrollment->class->subject->subject_name ?? '-' }}</strong>
                                <span style="color:#64748b;">{{ $grade->enrollment->class->subject->subject_code ?? '-' }}</span>
                            </div>
                        </td>
                        <td>{{ $grade->grading_period }}</td>
                        <td>{{ number_format((float) ($grade->seatwork_score ?? 0), 2) }}</td>
                        <td>{{ number_format((float) ($grade->quiz_score ?? 0), 2) }}</td>
                        <td>{{ number_format((float) ($grade->exam_score ?? 0), 2) }}</td>
                        <td><strong>{{ number_format((float) ($grade->final_grade ?? $grade->grade), 2) }}</strong></td>
                        <td>{{ $grade->remarks ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; color:#64748b;">No grades available for the selected filter yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
