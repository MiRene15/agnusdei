@extends('layouts.registrar')

@section('title', 'Sectioning')

@section('content')

<div class="page-intro">
    <h4>Student Sectioning</h4>
    <p>Assign sections and school year to approved students. Subjects will be auto-assigned based on class matching.</p>
</div>

@if(session('success'))
    <div class="card" style="border-left:4px solid #16a34a; color:#166534;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="card" style="border-left:4px solid #dc2626; color:#991b1b;">
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $error)
                <li style="margin-bottom:4px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <h4>Approved Students</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student Number</th>
                    <th>LRN</th>
                    <th>Name</th>
                    <th>Grade Level</th>
                    <th>Section / School Year / Capacity / Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $student->student_number }}</td>
                        <td>{{ $student->lrn ?? '-' }}</td>
                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td>{{ $student->grade_level }}</td>

                        <td style="padding:0;">
                            <div style="padding:12px; display:grid; grid-template-columns: 1fr auto; gap:12px; align-items:center;">
                                <form method="POST" action="{{ route('registrar.section.update', $student->id) }}" style="
                                    display:grid;
                                    grid-template-columns: 1fr 160px 180px auto;
                                    gap:12px;
                                    align-items:center;
                                    width:100%;
                                ">
                                    @csrf

                                    <select
                                        name="section"
                                        class="form-control section-select"
                                        data-grade="{{ $student->grade_level }}"
                                        required
                                    >
                                        <option value="">Select Section</option>

                                        @foreach($sections as $grade => $gradeSections)
                                            @foreach($gradeSections as $section)
                                                @php
                                                    $schoolYearKey = ($student->school_year ?? now()->year . '-' . (now()->year + 1));
                                                    $countKey = $section->grade_level . '|' . $section->section_name . '|' . $schoolYearKey;
                                                    $currentCount = $sectionCounts[$countKey]->total ?? 0;
                                                @endphp
                                                <option
                                                    value="{{ $section->section_name }}"
                                                    data-grade="{{ $section->grade_level }}"
                                                    data-capacity="{{ $section->capacity }}"
                                                    data-count="{{ $currentCount }}"
                                                    {{ $student->section == $section->section_name ? 'selected' : '' }}
                                                >
                                                    {{ $section->section_name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>

                                    <input
                                        type="text"
                                        name="school_year"
                                        value="{{ $student->school_year ?? now()->year . '-' . (now()->year + 1) }}"
                                        placeholder="2026-2027"
                                        class="form-control"
                                        required
                                    >

                                    <input
                                        type="text"
                                        class="form-control capacity-display"
                                        value="-"
                                        placeholder="Capacity"
                                        readonly
                                    >

                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>

                                <form method="POST" action="{{ route('registrar.section.autoAssign', $student->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline">Auto Assign</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#64748b;">No approved students yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.section-select').forEach(select => {
        const studentGrade = select.dataset.grade;
        const form = select.closest('form');
        const capacityInput = form.querySelector('.capacity-display');

        function applyFilterAndCapacity() {
            Array.from(select.options).forEach(option => {
                if (!option.value) return;

                const optionGrade = option.getAttribute('data-grade');
                option.hidden = optionGrade !== studentGrade;
            });

            const selectedOption = select.options[select.selectedIndex];
            const capacity = selectedOption ? selectedOption.getAttribute('data-capacity') : null;
            const count = selectedOption ? selectedOption.getAttribute('data-count') : null;

            capacityInput.value = (capacity && count !== null) ? (count + ' / ' + capacity) : '-';
        }

        select.addEventListener('change', applyFilterAndCapacity);
        applyFilterAndCapacity();
    });
});
</script>

@endsection