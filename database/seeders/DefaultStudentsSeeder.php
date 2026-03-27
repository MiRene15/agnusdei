<?php

namespace Database\Seeders;

use App\Models\Admission;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\ParentModel;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use App\Support\TuitionPlanner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultStudentsSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = TuitionPlanner::currentSchoolYear();
        $password = Hash::make('Agnus2026!');
        $sections = Section::where('is_active', true)
            ->orderBy('grade_level')
            ->orderBy('section_name')
            ->get();

        $classMap = Classes::where('school_year', $schoolYear)
            ->get()
            ->groupBy(fn ($class) => $class->grade_level . '|' . $class->section);

        $firstNames = ['Liam', 'Noah', 'Ethan', 'Lucas', 'Aiden', 'Mia', 'Sophia', 'Emma', 'Chloe', 'Ava', 'Isla', 'Zara', 'Nathan', 'Caleb', 'Elijah', 'Harper', 'Layla', 'Sienna', 'Amara', 'Bianca'];
        $lastNames = ['Santos', 'Reyes', 'Cruz', 'Garcia', 'Torres', 'Flores', 'Mendoza', 'Domingo', 'Aquino', 'Ramos', 'Lopez', 'Navarro', 'Luna', 'Castro', 'Bautista', 'Sy', 'Salazar', 'Mercado', 'Villanueva', 'Natividad'];
        $guardianLastNames = ['Santos', 'Reyes', 'Cruz', 'Garcia', 'Torres', 'Flores', 'Mendoza', 'Domingo', 'Aquino', 'Ramos', 'Lopez', 'Navarro'];
        $streets = ['Mabini Street', 'Sampaguita Avenue', 'Rizal Street', 'Acacia Lane', 'Narra Road', 'Camia Drive', 'Evergreen Homes', 'Luna Street'];
        $barangays = ['San Isidro', 'Poblacion', 'Sto. Nino', 'Sta. Cruz', 'San Jose', 'Maligaya', 'Bagong Silang', 'Buenavista'];

        $counter = 1;

        foreach ($sections as $section) {
            for ($slot = 1; $slot <= 15; $slot++) {
                $firstName = $firstNames[($counter + $slot) % count($firstNames)];
                $lastName = $lastNames[($counter * 2 + $slot) % count($lastNames)];
                $guardianFirstName = $firstNames[($counter * 3 + $slot) % count($firstNames)];
                $guardianLastName = $guardianLastNames[($counter + $slot) % count($guardianLastNames)];
                $fullName = $firstName . ' ' . $lastName;
                $studentNumber = 'STU' . str_pad((string) $counter, 6, '0', STR_PAD_LEFT);
                $studentEmail = 'student' . str_pad((string) $counter, 4, '0', STR_PAD_LEFT) . '@agnusdei.local';
                $parentEmail = 'guardian' . str_pad((string) $counter, 4, '0', STR_PAD_LEFT) . '@agnusdei.local';
                $phone = '09' . str_pad((string) (100000000 + $counter), 9, '0', STR_PAD_LEFT);
                $guardianPhone = '09' . str_pad((string) (200000000 + $counter), 9, '0', STR_PAD_LEFT);
                $track = $this->resolveTrack($section->grade_level, $section->section_name, $counter);
                $gender = $counter % 2 === 0 ? 'Female' : 'Male';

                $parentUser = User::updateOrCreate(
                    ['email' => $parentEmail],
                    [
                        'name' => $guardianFirstName . ' ' . $guardianLastName,
                        'email' => $parentEmail,
                        'contact_number' => $guardianPhone,
                        'role' => 'parent',
                        'password' => $password,
                    ]
                );

                $parent = ParentModel::updateOrCreate(
                    ['email' => $parentEmail],
                    [
                        'user_id' => $parentUser->id,
                        'parent_number' => 'PAR-' . str_pad((string) $counter, 5, '0', STR_PAD_LEFT),
                        'first_name' => $guardianFirstName,
                        'last_name' => $guardianLastName,
                        'email' => $parentEmail,
                        'phone' => $guardianPhone,
                        'address' => $streets[$counter % count($streets)] . ', Brgy. ' . $barangays[$counter % count($barangays)],
                    ]
                );

                $studentUser = User::updateOrCreate(
                    ['email' => $studentEmail],
                    [
                        'name' => $fullName,
                        'email' => $studentEmail,
                        'contact_number' => $phone,
                        'role' => 'student',
                        'password' => $password,
                    ]
                );

                $student = Student::updateOrCreate(
                    ['student_number' => $studentNumber],
                    [
                        'user_id' => $studentUser->id,
                        'parent_id' => $parent->id,
                        'student_number' => $studentNumber,
                        'lrn' => '4' . str_pad((string) $counter, 11, '0', STR_PAD_LEFT),
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'birth_date' => $this->birthDateFor($section->grade_level, $counter),
                        'gender' => $gender,
                        'email' => $studentEmail,
                        'phone' => $phone,
                        'address' => $streets[($counter + 2) % count($streets)] . ', Brgy. ' . $barangays[($counter + 3) % count($barangays)],
                        'grade_level' => $section->grade_level,
                        'shs_track' => $track,
                        'previous_school_type' => $counter % 3 === 0 ? 'private' : 'public',
                        'honor_rank' => $counter % 5 === 0 ? (($counter % 3) + 1) : null,
                        'section' => $section->section_name,
                        'school_year' => $schoolYear,
                        'status' => 'enrolled',
                        'portal_access_status' => 'unlocked',
                        'portal_unlocked_at' => now()->subDays($counter % 20),
                        'ptc_completed' => $counter % 4 !== 0,
                        'ptc_completed_at' => $counter % 4 !== 0 ? now()->subDays($counter % 12) : null,
                        'is_transferred' => false,
                    ]
                );

                $admission = Admission::updateOrCreate(
                    ['lrn' => $student->lrn],
                    [
                        'application_number' => 'APP-SEED-' . str_pad((string) $counter, 6, '0', STR_PAD_LEFT),
                        'first_name' => $student->first_name,
                        'last_name' => $student->last_name,
                        'birth_date' => $student->birth_date,
                        'sex' => $student->gender,
                        'email' => $student->email,
                        'institutional_email' => $student->email,
                        'phone' => $student->phone,
                        'address' => $student->address,
                        'applying_for_grade' => $student->grade_level,
                        'shs_track' => $student->shs_track,
                        'previous_school' => 'Seeded Previous School',
                        'previous_school_type' => $student->previous_school_type,
                        'honor_rank' => $student->honor_rank,
                        'status' => 'approved',
                        'is_verified' => true,
                        'verified_at' => now()->subDays($counter % 20),
                        'verified_by' => null,
                        'application_date' => now()->subDays(($counter + $slot) % 40),
                        'remarks' => 'Seeded admission record.',
                    ]
                );

                $requirementNames = ['Birth Certificate', 'Report Card', 'Good Moral Certificate', '2x2 ID Picture'];
                if (TuitionPlanner::requiresShsTrack($student->grade_level)) {
                    $requirementNames[] = 'SHS Voucher';
                }

                foreach ($requirementNames as $requirementName) {
                    $admission->requirements()->updateOrCreate(
                        ['requirement_name' => $requirementName],
                        [
                            'submitted' => 1,
                            'submitted_at' => now()->subDays($counter % 15),
                            'status' => 'approved',
                            'remarks' => 'Seeded submitted requirement.',
                            'file_path' => null,
                        ]
                    );
                }

                if ((int) $student->admission_id !== (int) $admission->id) {
                    $student->admission_id = $admission->id;
                    $student->save();
                }

                $studentUser->update([
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'email' => $student->email,
                    'contact_number' => $student->phone,
                ]);

                $parentUser->update([
                    'name' => $parent->first_name . ' ' . $parent->last_name,
                    'email' => $parent->email,
                    'contact_number' => $parent->phone,
                ]);

                foreach ($classMap->get($section->grade_level . '|' . $section->section_name, collect()) as $class) {
                    Enrollment::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'class_id' => $class->id,
                        ],
                        [
                            'enrollment_date' => now()->subDays(($counter + $slot) % 45)->toDateString(),
                            'status' => 'enrolled',
                        ]
                    );
                }

                $counter++;
            }
        }
    }

    private function resolveTrack(string $gradeLevel, string $sectionName, int $seed): ?string
    {
        if (!TuitionPlanner::requiresShsTrack($gradeLevel)) {
            return null;
        }

        foreach (TuitionPlanner::shsTracks() as $track) {
            if (str_contains($sectionName, $track)) {
                return $track;
            }
        }

        $tracks = TuitionPlanner::shsTracks();

        return $tracks[$seed % count($tracks)];
    }

    private function birthDateFor(string $gradeLevel, int $seed): string
    {
        $age = match ($gradeLevel) {
            'Kinder' => 5,
            'Grade 1' => 6,
            'Grade 2' => 7,
            'Grade 3' => 8,
            'Grade 4' => 9,
            'Grade 5' => 10,
            'Grade 6' => 11,
            'Grade 7' => 12,
            'Grade 8' => 13,
            'Grade 9' => 14,
            'Grade 10' => 15,
            'Grade 11' => 16,
            'Grade 12' => 17,
            default => 10,
        };

        return now()->subYears($age)->subDays($seed % 240)->toDateString();
    }
}

