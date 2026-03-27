@extends('layouts.registrar')

@section('title', 'Sectioning')

@section('content')
<div class="page-intro" style="margin-bottom:20px;">
    <h4>Sectioning Workspace</h4>
    <p>Place eligible students into aligned sections, monitor capacity, and respect same-year placement locks from one polished board.</p>
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

<div class="stats-grid">
    <div class="stat-card" style="background:linear-gradient(135deg, #fffaf5, #ffffff); border:1px solid #fed7aa;">
        <div class="stat-label" style="color:#9a3412;">School Year</div>
        <div class="stat-value" style="color:#7c2d12;">{{ $schoolYear }}</div>
        <div class="stat-sub" style="color:#9a3412;">Active enrollment cycle for section assignment</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg, #f0fdf4, #ffffff); border:1px solid #bbf7d0;">
        <div class="stat-label" style="color:#166534;">Ready Students</div>
        <div class="stat-value" style="color:#14532d;">{{ $students->count() }}</div>
        <div class="stat-sub" style="color:#166534;">Verified, cleared, approved, or already enrolled</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg, #eff6ff, #ffffff); border:1px solid #bfdbfe;">
        <div class="stat-label" style="color:#1d4ed8;">Aligned Class Sets</div>
        <div class="stat-value" style="color:#1e3a8a;">{{ $alignedClassCounts->filter(fn ($count) => $count > 0)->count() }}</div>
        <div class="stat-sub" style="color:#1d4ed8;">Sections with valid subjects and assigned teachers</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg, #faf5ff, #ffffff); border:1px solid #ddd6fe;">
        <div class="stat-label" style="color:#6d28d9;">Locked Placements</div>
        <div class="stat-value" style="color:#5b21b6;">{{ $students->filter(fn ($student) => $student->section && $student->school_year === $schoolYear && in_array($student->status, ['enrolled', 'withdrawn'], true))->count() }}</div>
        <div class="stat-sub" style="color:#6d28d9;">Students already locked for this school year</div>
    </div>
</div>

<div class="quick-actions" style="margin-bottom:18px;">
    <div class="action-box" style="pointer-events:none;">
        <h5>Manual Placement</h5>
        <p>Pick the target section and inspect the live capacity summary before saving.</p>
    </div>
    <div class="action-box" style="pointer-events:none;">
        <h5>Section Lock</h5>
        <p>Once a same-year section is saved, that placement stays locked until the next cycle.</p>
    </div>
    <form method="POST" action="{{ route('registrar.section.autoAssignBatch') }}" class="action-box" style="display:block;">
        @csrf
        <input type="hidden" name="school_year" value="{{ $schoolYear }}">
        <h5>Batch Auto Assign</h5>
        <p style="margin-bottom:14px;">Assign all eligible students still waiting for placement this school year.</p>
        <button type="submit" class="btn btn-primary" style="width:100%;">Run Batch Auto Assign</button>
    </form>
</div>

<div class="card" style="padding:0; overflow:hidden; border:1px solid #e5e7eb; border-radius:22px;">
    <div style="display:flex; justify-content:space-between; gap:16px; align-items:center; flex-wrap:wrap; padding:20px 22px; border-bottom:1px solid #eef2f7; background:linear-gradient(180deg, #ffffff, #fafaf9);">
        <div>
            <h4 style="margin:0;">Student Placement Board</h4>
            <p style="margin:6px 0 0; color:#64748b;">A cleaner placement table with clearer status, school year, capacity, and assignment controls.</p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <div style="padding:10px 12px; border-radius:999px; background:#eef2ff; color:#3730a3; font-weight:700; font-size:12px;">Placement lock active for same-year enrolled students</div>
        </div>
    </div>

    <div class="table-wrap" style="margin:0;">
        <table style="min-width:1320px;">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Grade / Status</th>
                    <th>Billing Status</th>
                    <th>Current Placement</th>
                    <th>Assignment Controls</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    @php
                        $billing = $student->tuitionFees->first();
                        $isCleared = $billing && ($billing->is_downpayment_cleared || (float) $billing->paid_amount >= (float) $billing->down_payment_required);
                        $currentSection = $student->section;
                        $isLocked = $student->section && ($student->school_year === $schoolYear) && in_array($student->status, ['enrolled', 'withdrawn'], true);
                    @endphp
                    <tr style="background:{{ $isLocked ? '#fbfdff' : '#ffffff' }};">
                        <td>
                            <div style="display:grid; gap:6px;">
                                <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                <span style="color:#64748b;">{{ $student->student_number ?: 'No student number yet' }}</span>
                                <span style="color:#94a3b8; font-size:13px;">LRN: {{ $student->lrn ?? '-' }}</span>
                                <span style="color:#94a3b8; font-size:13px;">School Year: {{ $student->school_year ?? $schoolYear }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display:grid; gap:8px;">
                                <span style="font-weight:700;">{{ $student->grade_level }}</span>
                                <span class="badge {{ $student->status === 'enrolled' ? 'badge-approved' : 'badge-review' }}">{{ strtoupper(str_replace('_', ' ', $student->status)) }}</span>
                                @if($isLocked)
                                    <span class="badge badge-review" style="width:max-content;">Locked For Current Year</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="display:grid; gap:8px;">
                                @if($billing)
                                    <span class="badge {{ $isCleared ? 'badge-approved' : 'badge-pending' }}">{{ $isCleared ? 'Payment Cleared' : 'Awaiting Cashier Payment' }}</span>
                                    <span style="color:#64748b; font-size:13px;">Paid: PHP {{ number_format($billing->paid_amount, 2) }}</span>
                                    <span style="color:#64748b; font-size:13px;">Required: PHP {{ number_format($billing->down_payment_required, 2) }}</span>
                                @else
                                    <span class="badge badge-incomplete">No Billing Record</span>
                                    <span style="color:#64748b; font-size:13px;">Verify admission first.</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="display:grid; gap:8px;">
                                <span style="font-weight:700;">{{ $currentSection ?: 'No Section Assigned Yet' }}</span>
                                @if($currentSection)
                                    @php $currentKey = $student->grade_level . '|' . $currentSection; @endphp
                                    <span style="color:#64748b; font-size:13px;">Aligned classes: {{ $alignedClassCounts[$currentKey] ?? 0 }}</span>
                                @else
                                    <span style="color:#64748b; font-size:13px;">Student can still be placed into any aligned active section.</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding:18px;">
                            <div style="display:grid; gap:12px; min-width:470px;">
                                <form method="POST" action="{{ route('registrar.section.update', $student->id) }}" class="placement-form">
                                    @csrf
                                    <div class="placement-grid">
                                        <div class="placement-field">
                                            <label>Section Assignment</label>
                                            <select name="section" class="form-control section-select" data-grade="{{ $student->grade_level }}" @disabled($isLocked) required>
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
                                        </div>

                                        <div class="placement-field">
                                            <label>School Year</label>
                                            <input type="text" name="school_year" value="{{ $student->school_year ?? $schoolYear }}" class="form-control school-year-display" @readonly($isLocked) required>
                                        </div>

                                        <div class="placement-field">
                                            <label>Capacity</label>
                                            <input type="text" class="form-control capacity-display" value="-" readonly>
                                        </div>

                                        <div class="placement-action">
                                            <button type="submit" class="btn btn-primary" @disabled($isLocked)>Save Placement</button>
                                        </div>
                                    </div>
                                </form>

                                <div style="display:flex; justify-content:space-between; gap:10px; align-items:center; flex-wrap:wrap;">
                                    <span class="alignment-note" style="color:#64748b; font-size:13px;">{{ $isLocked ? 'Assignment is locked until the next promotion or enrollment cycle.' : 'Choose a section to see live capacity and alignment count.' }}</span>
                                    <form method="POST" action="{{ route('registrar.section.autoAssign', $student->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline" @disabled($isLocked)>Auto Assign Best Match</button>
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

<style>
.placement-form {
    display:grid;
    gap:12px;
}
.placement-grid {
    display:grid;
    grid-template-columns: minmax(190px, 1.4fr) 140px 130px auto;
    gap:10px;
    align-items:end;
}
.placement-field {
    display:grid;
    gap:6px;
}
.placement-field label {
    font-size:12px;
    font-weight:700;
    letter-spacing:.06em;
    text-transform:uppercase;
    color:#64748b;
}
.placement-action {
    display:flex;
}
.school-year-display {
    font-weight:700;
    color:#1e3a8a;
}
@media (max-width: 1200px) {
    .placement-grid {
        grid-template-columns: 1fr 1fr;
    }
}
@media (max-width: 640px) {
    .placement-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.section-select').forEach(function (select) {
        const studentGrade = select.dataset.grade;
        const form = select.closest('.placement-form');
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

            if (note && !select.disabled) {
                note.textContent = aligned !== null && selectedOption && selectedOption.value
                    ? ('Aligned classes: ' + aligned + ' | Capacity: ' + count + ' / ' + capacity)
                    : 'Choose a section to see live capacity and alignment count.';
            }
        }

        select.addEventListener('change', syncOptionMeta);
        syncOptionMeta();
    });
});
</script>
@endsection
