@extends('layouts.app')

@section('title', 'Admission Application')

@section('content')
<div style="max-width: 900px; margin: 50px auto; padding: 0 20px;">
    <div style="background:#ffffff; border-radius:18px; box-shadow:0 12px 30px rgba(0,0,0,0.08); overflow:hidden;">
        <div style="background: linear-gradient(135deg, #001e82, #1636a3); color: white; padding: 30px;">
            <h2 style="margin:0; font-size:30px; font-weight:600;">Admission Application</h2>
            <p style="margin:8px 0 0; opacity:0.92; font-size:15px;">Please complete the admission form carefully and accurately.</p>
        </div>

        <div style="padding:30px;">
            @if(session('error'))
                <div style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca; border-radius:12px; padding:14px 16px; margin-bottom:20px;">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca; border-radius:12px; padding:14px 16px; margin-bottom:20px;">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li style="margin-bottom:4px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('student.admission.store') }}">
                @csrf

                <div style="margin-bottom:25px;">
                    <h3 style="color:#001e82; font-size:20px; margin-bottom:18px;">Personal Information</h3>
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:18px;">
                        <div><label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">First Name</label><input type="text" name="first_name" value="{{ old('first_name') }}" required style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px;"></div>
                        <div><label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Last Name</label><input type="text" name="last_name" value="{{ old('last_name') }}" required style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px;"></div>
                        <div><label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Birth Date</label><input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px;"></div>
                        <div><label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Age</label><input type="text" id="age" readonly placeholder="Auto computed age" style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#f8fafc;"></div>
                        <div><label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Gender</label><select name="sex" required style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#fff;"><option value="">Select Gender</option><option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option><option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option></select></div>
                        <div><label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Email</label><input type="email" name="email" value="{{ old('email') }}" style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px;"></div>
                        <div><label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Phone</label><input type="text" name="phone" value="{{ old('phone') }}" placeholder="09XXXXXXXXX or +639XXXXXXXXX" pattern="^(09\d{9}|\+639\d{9})$" inputmode="numeric" style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px;"></div>
                    </div>
                    <div style="margin-top:18px;"><label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Address</label><textarea name="address" rows="3" style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; resize:vertical;">{{ old('address') }}</textarea></div>
                </div>

                <div style="margin-bottom:25px;">
                    <h3 style="color:#001e82; font-size:20px; margin-bottom:18px;">Academic Information</h3>
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:18px;">
                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Preferred Grade Level</label>
                            <select name="applying_for_grade" id="applying_for_grade" required style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#fff;">
                                <option value="">Select Grade Level</option>
                                <option value="Nursery" {{ old('applying_for_grade') == 'Nursery' ? 'selected' : '' }}>Nursery</option>
                                <option value="Kinder" {{ old('applying_for_grade') == 'Kinder' ? 'selected' : '' }}>Kinder</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="Grade {{ $i }}" {{ old('applying_for_grade') == 'Grade ' . $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div id="shs-track-field" style="display:none;">
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Senior High Track</label>
                            <select name="shs_track" id="shs_track" style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#fff;">
                                <option value="">Select SHS track</option>
                                @foreach($shsTracks as $track)
                                    <option value="{{ $track }}" {{ old('shs_track') === $track ? 'selected' : '' }}>{{ $track }}</option>
                                @endforeach
                            </select>
                            <small style="display:block; margin-top:6px; color:#64748b;">This will be used for cleaner Senior High section, subject, and professor matching.</small>
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">LRN</label>
                            <input type="text" name="lrn" id="lrn" value="{{ old('lrn', '4') }}" maxlength="12" required disabled placeholder="4XXXXXXXXXXX" pattern="^4\d{11}$" inputmode="numeric" style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#f8fafc;">
                            <small id="lrn_note" style="color:#64748b;">Choose a grade level to enable the LRN field. It must be 12 digits, numbers only, and start with 4.</small>
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Previous School</label>
                            <input type="text" name="previous_school" value="{{ old('previous_school') }}" list="school-list" style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px;">
                            <datalist id="school-list">
                                <option value="Agnus Dei School Systems INC."></option>
                                <option value="San Jose National High School"></option>
                                <option value="Sto. Nino Academy"></option>
                                <option value="St. Mary's Academy"></option>
                                <option value="Divine Grace School"></option>
                                <option value="Central Luzon State University Laboratory School"></option>
                                <option value="Nueva Ecija High School"></option>
                                <option value="College of the Immaculate Conception"></option>
                            </datalist>
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Previous School Type</label>
                            <select name="previous_school_type" style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#fff;">
                                <option value="">Select School Type</option>
                                <option value="public" {{ old('previous_school_type') === 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ old('previous_school_type') === 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#334155;">Honor Rank From Previous School</label>
                            <select name="honor_rank" style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#fff;">
                                <option value="">No honor rank</option>
                                <option value="1" {{ old('honor_rank') === '1' ? 'selected' : '' }}>First Honor</option>
                                <option value="2" {{ old('honor_rank') === '2' ? 'selected' : '' }}>Second Honor</option>
                                <option value="3" {{ old('honor_rank') === '3' ? 'selected' : '' }}>Third Honor</option>
                            </select>
                            <small style="display:block; margin-top:6px; color:#64748b;">Only one automatic privilege is applied. The system will keep the highest eligible tuition discount.</small>
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:12px; flex-wrap:wrap;">
                    <a href="{{ url()->previous() }}" style="display:inline-block; padding:12px 20px; border-radius:10px; background:#e2e8f0; color:#334155; text-decoration:none; font-weight:600;">Cancel</a>
                    <button type="submit" style="border:none; background:#001e82; color:#fff; padding:12px 24px; border-radius:10px; font-size:15px; font-weight:600; cursor:pointer; box-shadow:0 10px 20px rgba(0,30,130,0.18);">Submit Admission</button>
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
const shsTrackField = document.getElementById('shs-track-field');
const shsTrackSelect = document.getElementById('shs_track');

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
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) { age--; }
    ageInput.value = age >= 0 ? age : '';
}

function updateAcademicFields() {
    const selected = gradeInput.value;
    const isShs = selected === 'Grade 11' || selected === 'Grade 12';

    if (selected !== '') {
        lrnInput.disabled = false;
        lrnInput.required = true;
        lrnInput.style.background = '#ffffff';
        if (!lrnInput.value) {
            lrnInput.value = '4';
        }
        lrnInput.placeholder = '4XXXXXXXXXXX';
        lrnNote.textContent = 'LRN is required. It must be 12 digits, numbers only, and start with 4.';
    } else {
        lrnInput.disabled = true;
        lrnInput.required = false;
        lrnInput.value = '4';
        lrnInput.style.background = '#f8fafc';
        lrnInput.placeholder = 'Select grade first';
        lrnNote.textContent = 'Choose a grade level to enable the LRN field. It must be 12 digits, numbers only, and start with 4.';
    }

    shsTrackField.style.display = isShs ? 'block' : 'none';
    shsTrackSelect.required = isShs;
    if (!isShs) { shsTrackSelect.value = ''; }
}

birthDateInput.addEventListener('change', computeAge);
birthDateInput.addEventListener('input', computeAge);
gradeInput.addEventListener('change', updateAcademicFields);
lrnInput.addEventListener('input', function () {
    let digits = this.value.replace(/\D/g, '');
    if (!digits.startsWith('4')) {
        digits = '4' + digits.replace(/^4*/, '');
    }
    this.value = digits.slice(0, 12);
});
window.addEventListener('load', function () { computeAge(); updateAcademicFields(); });
</script>
@endsection
