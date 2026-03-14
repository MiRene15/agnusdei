@extends('layouts.app')

@section('title', 'Admission Application')

@section('content')
<div style="max-width: 900px; margin: 50px auto; padding: 0 20px;">
    <div style="
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        overflow: hidden;
    ">
        <div style="
            background: linear-gradient(135deg, #001e82, #1636a3);
            color: white;
            padding: 30px;
        ">
            <h2 style="margin: 0; font-size: 30px; font-weight: 600;">Admission Application</h2>
            <p style="margin: 8px 0 0; opacity: 0.92; font-size: 15px;">
                Please complete the admission form carefully and accurately.
            </p>
        </div>

        <div style="padding: 30px;">
            @if(session('error'))
                <div style="
                    background: #fee2e2;
                    color: #991b1b;
                    border: 1px solid #fecaca;
                    border-radius: 12px;
                    padding: 14px 16px;
                    margin-bottom: 20px;
                ">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="
                    background: #fee2e2;
                    color: #991b1b;
                    border: 1px solid #fecaca;
                    border-radius: 12px;
                    padding: 14px 16px;
                    margin-bottom: 20px;
                ">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li style="margin-bottom:4px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('student.admission.store') }}">
                @csrf

                <div style="margin-bottom: 25px;">
                    <h3 style="color:#001e82; font-size:20px; margin-bottom:18px;">Personal Information</h3>

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:18px;">
                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" placeholder="Enter first name" required style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px;">
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" placeholder="Enter last name" required style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px;">
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Birth Date</label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="form-control" required style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px;">
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Age</label>
                            <input type="text" name="age" id="age" class="form-control" placeholder="Auto computed age" readonly style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#f8fafc;">
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Gender</label>
                            <select name="sex" class="form-control" required style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#fff;">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter email address" style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px;">
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Enter phone number" style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px;">
                        </div>
                    </div>

                    <div style="margin-top:18px;">
                        <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Address</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Enter complete address" style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; resize:vertical;">{{ old('address') }}</textarea>
                    </div>
                </div>

                <div style="margin-bottom: 25px;">
                    <h3 style="color:#001e82; font-size:20px; margin-bottom:18px;">Academic Information</h3>

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:18px;">
                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Applying for Grade</label>
                            <select name="applying_for_grade" id="applying_for_grade" required style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#fff;">
                                <option value="">Select Grade Level</option>
                                <option value="Nursery" {{ old('applying_for_grade') == 'Nursery' ? 'selected' : '' }}>Nursery</option>
                                <option value="Kinder" {{ old('applying_for_grade') == 'Kinder' ? 'selected' : '' }}>Kinder</option>
                                <option value="Grade 1" {{ old('applying_for_grade') == 'Grade 1' ? 'selected' : '' }}>Grade 1</option>
                                <option value="Grade 2" {{ old('applying_for_grade') == 'Grade 2' ? 'selected' : '' }}>Grade 2</option>
                                <option value="Grade 3" {{ old('applying_for_grade') == 'Grade 3' ? 'selected' : '' }}>Grade 3</option>
                                <option value="Grade 4" {{ old('applying_for_grade') == 'Grade 4' ? 'selected' : '' }}>Grade 4</option>
                                <option value="Grade 5" {{ old('applying_for_grade') == 'Grade 5' ? 'selected' : '' }}>Grade 5</option>
                                <option value="Grade 6" {{ old('applying_for_grade') == 'Grade 6' ? 'selected' : '' }}>Grade 6</option>
                                <option value="Grade 7" {{ old('applying_for_grade') == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
                                <option value="Grade 8" {{ old('applying_for_grade') == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
                                <option value="Grade 9" {{ old('applying_for_grade') == 'Grade 9' ? 'selected' : '' }}>Grade 9</option>
                                <option value="Grade 10" {{ old('applying_for_grade') == 'Grade 10' ? 'selected' : '' }}>Grade 10</option>
                                <option value="Grade 11" {{ old('applying_for_grade') == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                <option value="Grade 12" {{ old('applying_for_grade') == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                            </select>
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">LRN</label>
                            <input
                                type="text"
                                name="lrn"
                                id="lrn"
                                value="{{ old('lrn') }}"
                                class="form-control"
                                placeholder="Select grade first"
                                maxlength="12"
                                required
                                disabled
                                style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#f8fafc;"
                            >
                            <small id="lrn_note" style="color:#64748b;">Choose a grade level to enable the LRN field. LRN is required.</small>
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Previous School</label>
                            <input type="text" name="previous_school" value="{{ old('previous_school') }}" list="school-list" class="form-control" placeholder="Type or select previous school" style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px;">
                            <datalist id="school-list">
                                <option value="Agnus Dei School Systems INC."></option>
                                <option value="San Jose National High School"></option>
                                <option value="Sto. Niño Academy"></option>
                                <option value="St. Mary's Academy"></option>
                                <option value="Divine Grace School"></option>
                                <option value="Central Luzon State University Laboratory School"></option>
                                <option value="Nueva Ecija High School"></option>
                                <option value="College of the Immaculate Conception"></option>
                            </datalist>
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:12px; flex-wrap:wrap;">
                    <a href="{{ url()->previous() }}" style="
                        display:inline-block;
                        padding:12px 20px;
                        border-radius:10px;
                        background:#e2e8f0;
                        color:#334155;
                        text-decoration:none;
                        font-weight:600;
                    ">
                        Cancel
                    </a>

                    <button type="submit" style="
                        border:none;
                        background:#001e82;
                        color:#fff;
                        padding:12px 24px;
                        border-radius:10px;
                        font-size:15px;
                        font-weight:600;
                        cursor:pointer;
                        box-shadow: 0 10px 20px rgba(0,30,130,0.18);
                    ">
                        Submit Admission
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const birthDateInput = document.getElementById('birth_date');
    const ageInput = document.getElementById('age');
    const gradeInput = document.getElementById('applying_for_grade');
    const lrnInput = document.getElementById('lrn');
    const lrnNote = document.getElementById('lrn_note');

    function computeAge() {
        const birthDateValue = birthDateInput.value;

        if (!birthDateValue) {
            ageInput.value = '';
            return;
        }

        const today = new Date();
        const birthDate = new Date(birthDateValue);

        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        ageInput.value = age >= 0 ? age : '';
    }

    function updateLrnField() {
        const selected = gradeInput.value;

        if (selected !== '') {
            lrnInput.disabled = false;
            lrnInput.required = true;
            lrnInput.style.background = '#ffffff';
            lrnInput.placeholder = 'Enter 12-digit Learner Reference Number';
            lrnNote.textContent = 'LRN is required for all applicants.';
        } else {
            lrnInput.disabled = true;
            lrnInput.required = false;
            lrnInput.value = '';
            lrnInput.style.background = '#f8fafc';
            lrnInput.placeholder = 'Select grade first';
            lrnNote.textContent = 'Choose a grade level to enable the LRN field. LRN is required.';
        }
    }

    birthDateInput.addEventListener('change', computeAge);
    birthDateInput.addEventListener('input', computeAge);
    gradeInput.addEventListener('change', updateLrnField);

    window.addEventListener('load', function () {
        computeAge();
        updateLrnField();
    });
</script>
@endsection
