@extends('layouts.registrar')

@section('title', 'Sectioning')

@section('content')
<div class="page-intro" style="margin-bottom:20px;">
    <h4>Sectioning Workspace</h4>
    <p>Assign students only to sections with ready subject-teacher matching. Students become enrollable after cashier clears the required payment.</p>
</div>

@if(session('success'))
    <div class="card" style="border-left:4px solid #16a34a; color:#166534; margin-bottom:16px;">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="card" style="border-left:4px solid #dc2626; color:#991b1b; margin-bottom:16px;">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="card" style="border-left:4px solid #f59e0b; color:#92400e; margin-bottom:16px;">
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:18px;">
    <div class="card" style="background:linear-gradient(135deg, #fffaf5, #ffffff); border:1px solid #fed7aa;">
        <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#9a3412;">School Year</div>
        <div style="font-size:28px; font-weight:700; color:#7c2d12; margin-top:6px;">{{ $schoolYear }}</div>
        <div style="color:#9a3412; margin-top:6px;">Active enrollment cycle for section assignment</div>
    </div>
    <div class="card" style="background:linear-gradient(135deg, #f0fdf4, #ffffff); border:1px solid #bbf7d0;">
        <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#166534;">Ready Students</div>
        <div style="font-size:28px; font-weight:700; color:#14532d; margin-top:6px;">{{ $students->count() }}</div>
        <div style="color:#166534; margin-top:6px;">Verified, cleared, approved, or already enrolled</div>
    </div>
    <div class="card" style="background:linear-gradient(135deg, #eff6ff, #ffffff); border:1px solid #bfdbfe;">
        <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#1d4ed8;">Aligned Class Sets</div>
        <div style="font-size:28px; font-weight:700; color:#1e3a8a; margin-top:6px;">{{ $alignedClassCounts->filter(fn ($count) => $count > 0)->count() }}</div>
        <div style="color:#1d4ed8; margin-top:6px;">Sections with valid subjects and assigned teachers</div>
    </div>
</div>

<div class="card" style="padding:0; overflow:hidden; border:1px solid #e5e7eb; border-radius:22px;">
    <div style="display:flex; justify-content:space-between; gap:16px; align-items:center; padding:20px 22px; border-bottom:1px solid #eef2f7; background:linear-gradient(180deg, #ffffff, #fafaf9);">
        <div>
            <h4 style="margin:0;">Student Placement Board</h4>
            <p style="margin:6px 0 0; color:#64748b;">Minimal view of billing readiness, section capacity, and alignment coverage.</p>
        </div>
    </div>

    <div class="table-wrap" style="margin:0;">
        <table style="min-width:1200px;">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Grade</th>
                    <th>Payment Readiness</th>
                    <th>Section Match</th>
                    <th>Assignment</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    @php
                        $billing = $student->tuitionFees->first();
                        $isCleared = $billing && ($billing->is_downpayment_cleared || (float) $billing->paid_amount >= (float) $billing->down_payment_required);
                        $currentSection = $student->section;
                    @endphp
                    <tr>
                        <td>
                            <div style="display:grid; gap:4px;">
                                <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                <span style="color:#64748b;">{{ $student->student_number ?: 'No student number yet' }}</span>
                                <span style="color:#94a3b8; font-size:13px;">LRN: {{ $student->lrn ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display:grid; gap:6px;">
                                <span style="font-weight:600;">{{ $student->grade_level }}</span>
                                <span class="badge {{ $student->status === 'enrolled' ? 'badge-success' : 'badge-info' }}">{{ strtoupper($student->status) }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display:grid; gap:6px;">
                                @if($billing)
                                    <span class="badge {{ $isCleared ? 'badge-success' : 'badge-warning' }}">
                                        {{ $isCleared ? 'Ready for Sectioning' : 'Awaiting Cashier Payment' }}
                                    </span>
                                    <span style="color:#64748b; font-size:13px;">Paid: PHP {{ number_format($billing->paid_amount, 2) }}</span>
                                    <span style="color:#64748b; font-size:13px;">Required: PHP {{ number_format($billing->down_payment_required, 2) }}</span>
                                @else
                                    <span class="badge badge-danger">No Billing Record</span>
                                    <span style="color:#64748b; font-size:13px;">Verify the admission first.</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="display:grid; gap:8px;">
                                @if($currentSection)
                                    @php $currentKey = $student->grade_level . '|' . $currentSection; @endphp
                                    <span style="font-weight:600;">Current: {{ $currentSection }}</span>
                                    <span style="color:#64748b; font-size:13px;">Aligned classes: {{ $alignedClassCounts[$currentKey] ?? 0 }}</span>
                                @else
                                    <span style="color:#64748b;">No section assigned yet</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding:16px;">
                            <div style="display:grid; gap:12px; min-width:420px;">
                                <form method="POST" action="{{ route('registrar.section.update', $student->id) }}" style="display:grid; grid-template-columns: minmax(170px, 1fr) 140px 130px auto; gap:10px; align-items:center;">
                                    @csrf
                                    <select name="section" class="form-control section-select" data-grade="{{ $student->grade_level }}" required>
                                        <option value="">Select section</option>
                                        @foreach($sections as $grade => $gradeSections)
                                            @foreach($gradeSections as $section)
                                                @php
                                                    $countKey = $section->grade_level . '|' . $section->section_name . '|' . $schoolYear;
                                                    $currentCount = $sectionCounts[$countKey]->total ?? 0;
                                                    $alignKey = $section->grade_level . '|' . $section->section_name;
                                                    $alignedCount = $alignedClassCounts[$alignKey] ?? 0;
                                                @endphp
                                                <option
                                                    value="{{ $section->section_name }}"
                                                    data-grade="{{ $section->grade_level }}"
                                                    data-capacity="{{ $section->capacity }}"
                                                    data-count="{{ $currentCount }}"
                                                    data-aligned="{{ $alignedCount }}"
                                                    {{ $student->section === $section->section_name ? 'selected' : '' }}
                                                >
                                                    {{ $section->section_name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>

                                    <input type="text" name="school_year" value="{{ $student->school_year ?? $schoolYear }}" class="form-control" required>
                                    <input type="text" class="form-control capacity-display" value="-" readonly>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>

                                <div style="display:flex; justify-content:space-between; gap:10px; align-items:center; flex-wrap:wrap;">
                                    <span class="alignment-note" style="color:#64748b; font-size:13px;">Choose a section to see capacity and aligned class count.</span>
                                    <form method="POST" action="{{ route('registrar.section.autoAssign', $student->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline">Auto Assign Best Match</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:28px; color:#64748b;">No students are ready for sectioning right now.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.section-select').forEach(function (select) {
        const studentGrade = select.dataset.grade;
        const form = select.closest('form');
        const capacityInput = form.querySelector('.capacity-display');
        const note = form.parentElement.querySelector('.alignment-note');

        function syncOptionMeta() {
            Array.from(select.options).forEach(function (option) {
                if (!option.value) {
                    return;
                }

                option.hidden = option.getAttribute('data-grade') !== studentGrade;
            });

            const selectedOption = select.options[select.selectedIndex];
            const capacity = selectedOption ? selectedOption.getAttribute('data-capacity') : null;
            const count = selectedOption ? selectedOption.getAttribute('data-count') : null;
            const aligned = selectedOption ? selectedOption.getAttribute('data-aligned') : null;

            capacityInput.value = capacity ? (count + ' / ' + capacity) : '-';
            note.textContent = aligned !== null && selectedOption && selectedOption.value
                ? ('Aligned classes: ' + aligned)
                : 'Choose a section to see capacity and aligned class count.';
        }

        select.addEventListener('change', syncOptionMeta);
        syncOptionMeta();
    });
});
</script>
@endsection
