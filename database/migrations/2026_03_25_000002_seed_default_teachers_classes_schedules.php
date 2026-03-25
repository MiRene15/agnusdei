<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $schoolYear = '2026-2027';

        /*
        |--------------------------------------------------------------------------
        | DEFAULT TEACHER USERS + TEACHER PROFILES
        |--------------------------------------------------------------------------
        */
        $teacherSeeds = [
            ['first_name' => 'Maria', 'last_name' => 'Santos', 'email' => 'maria.santos@agnusdei.local', 'department' => 'Elementary'],
            ['first_name' => 'Jose', 'last_name' => 'Reyes', 'email' => 'jose.reyes@agnusdei.local', 'department' => 'Elementary'],
            ['first_name' => 'Ana', 'last_name' => 'Cruz', 'email' => 'ana.cruz@agnusdei.local', 'department' => 'Elementary'],
            ['first_name' => 'Paolo', 'last_name' => 'Garcia', 'email' => 'paolo.garcia@agnusdei.local', 'department' => 'Elementary'],

            ['first_name' => 'Liza', 'last_name' => 'Mendoza', 'email' => 'liza.mendoza@agnusdei.local', 'department' => 'Junior High School'],
            ['first_name' => 'Mark', 'last_name' => 'Torres', 'email' => 'mark.torres@agnusdei.local', 'department' => 'Junior High School'],
            ['first_name' => 'Rina', 'last_name' => 'Flores', 'email' => 'rina.flores@agnusdei.local', 'department' => 'Junior High School'],
            ['first_name' => 'Dennis', 'last_name' => 'Aquino', 'email' => 'dennis.aquino@agnusdei.local', 'department' => 'Junior High School'],

            ['first_name' => 'Carla', 'last_name' => 'Navarro', 'email' => 'carla.navarro@agnusdei.local', 'department' => 'Senior High School'],
            ['first_name' => 'Vincent', 'last_name' => 'Luna', 'email' => 'vincent.luna@agnusdei.local', 'department' => 'Senior High School'],
            ['first_name' => 'Sheila', 'last_name' => 'Ramos', 'email' => 'sheila.ramos@agnusdei.local', 'department' => 'Senior High School'],
            ['first_name' => 'Adrian', 'last_name' => 'Castro', 'email' => 'adrian.castro@agnusdei.local', 'department' => 'Senior High School'],
        ];

        $teacherIdsByDepartment = [
            'Elementary' => [],
            'Junior High School' => [],
            'Senior High School' => [],
        ];

        foreach ($teacherSeeds as $index => $teacherSeed) {
            $fullName = $teacherSeed['first_name'] . ' ' . $teacherSeed['last_name'];

            $existingUserId = DB::table('users')->where('email', $teacherSeed['email'])->value('id');

            if (!$existingUserId) {
                $existingUserId = DB::table('users')->insertGetId([
                    'name' => $fullName,
                    'email' => $teacherSeed['email'],
                    'contact_number' => '09' . str_pad((string) (100000000 + $index), 9, '0', STR_PAD_LEFT),
                    'role' => 'teacher',
                    'password' => Hash::make('password123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $existingTeacherId = DB::table('teachers')->where('email', $teacherSeed['email'])->value('id');

            if (!$existingTeacherId) {
                $existingTeacherId = DB::table('teachers')->insertGetId([
                    'user_id' => $existingUserId,
                    'teacher_number' => 'TCH-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'first_name' => $teacherSeed['first_name'],
                    'last_name' => $teacherSeed['last_name'],
                    'email' => $teacherSeed['email'],
                    'phone' => '09' . str_pad((string) (100000000 + $index), 9, '0', STR_PAD_LEFT),
                    'department' => $teacherSeed['department'],
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $teacherIdsByDepartment[$teacherSeed['department']][] = $existingTeacherId;
        }

        /*
        |--------------------------------------------------------------------------
        | SUBJECT LOOKUP
        |--------------------------------------------------------------------------
        */
        $subjectMap = DB::table('subjects')->pluck('id', 'subject_code')->toArray();

        /*
        |--------------------------------------------------------------------------
        | CLASS PLANS
        |--------------------------------------------------------------------------
        */
        $plans = [
            [
                'grade_level' => 'Grade 1',
                'section' => 'St. Mary',
                'semester' => null,
                'room_prefix' => 'E',
                'department' => 'Elementary',
                'subject_codes' => ['G1-ENG', 'G1-FIL', 'G1-MAT', 'G1-ESP', 'G1-MAPEH', 'G1-AP'],
            ],
            [
                'grade_level' => 'Grade 2',
                'section' => 'St. Joseph',
                'semester' => null,
                'room_prefix' => 'E',
                'department' => 'Elementary',
                'subject_codes' => ['G2-ENG', 'G2-FIL', 'G2-MAT', 'G2-ESP', 'G2-MAPEH', 'G2-AP'],
            ],
            [
                'grade_level' => 'Grade 3',
                'section' => 'St. John',
                'semester' => null,
                'room_prefix' => 'E',
                'department' => 'Elementary',
                'subject_codes' => ['G3-ENG', 'G3-FIL', 'G3-MAT', 'G3-SCI', 'G3-ESP', 'G3-MAPEH', 'G3-AP'],
            ],
            [
                'grade_level' => 'Grade 4',
                'section' => 'St. Luke',
                'semester' => null,
                'room_prefix' => 'E',
                'department' => 'Elementary',
                'subject_codes' => ['G4-ENG', 'G4-FIL', 'G4-MAT', 'G4-SCI', 'G4-AP', 'G4-ESP', 'G4-MAPEH', 'G4-EPP'],
            ],
            [
                'grade_level' => 'Grade 5',
                'section' => 'St. Mark',
                'semester' => null,
                'room_prefix' => 'E',
                'department' => 'Elementary',
                'subject_codes' => ['G5-ENG', 'G5-FIL', 'G5-MAT', 'G5-SCI', 'G5-AP', 'G5-ESP', 'G5-MAPEH', 'G5-EPP'],
            ],
            [
                'grade_level' => 'Grade 6',
                'section' => 'St. Matthew',
                'semester' => null,
                'room_prefix' => 'E',
                'department' => 'Elementary',
                'subject_codes' => ['G6-ENG', 'G6-FIL', 'G6-MAT', 'G6-SCI', 'G6-AP', 'G6-ESP', 'G6-MAPEH', 'G6-EPP'],
            ],

            [
                'grade_level' => 'Grade 7',
                'section' => 'Hope',
                'semester' => null,
                'room_prefix' => 'J',
                'department' => 'Junior High School',
                'subject_codes' => ['G7-ENG', 'G7-FIL', 'G7-MAT', 'G7-SCI', 'G7-AP', 'G7-ESP', 'G7-MAPEH', 'G7-TLE'],
            ],
            [
                'grade_level' => 'Grade 8',
                'section' => 'Faith',
                'semester' => null,
                'room_prefix' => 'J',
                'department' => 'Junior High School',
                'subject_codes' => ['G8-ENG', 'G8-FIL', 'G8-MAT', 'G8-SCI', 'G8-AP', 'G8-ESP', 'G8-MAPEH', 'G8-TLE'],
            ],
            [
                'grade_level' => 'Grade 9',
                'section' => 'Charity',
                'semester' => null,
                'room_prefix' => 'J',
                'department' => 'Junior High School',
                'subject_codes' => ['G9-ENG', 'G9-FIL', 'G9-MAT', 'G9-SCI', 'G9-AP', 'G9-ESP', 'G9-MAPEH', 'G9-TLE'],
            ],
            [
                'grade_level' => 'Grade 10',
                'section' => 'Courage',
                'semester' => null,
                'room_prefix' => 'J',
                'department' => 'Junior High School',
                'subject_codes' => ['G10-ENG', 'G10-FIL', 'G10-MAT', 'G10-SCI', 'G10-AP', 'G10-ESP', 'G10-MAPEH', 'G10-TLE'],
            ],

            [
                'grade_level' => 'Grade 11',
                'section' => 'STEM-A',
                'semester' => '1st Semester',
                'room_prefix' => 'S',
                'department' => 'Senior High School',
                'subject_codes' => ['SHS-OC', 'SHS-RW', 'SHS-GMATH', 'SHS-ELS', 'SHS-PD', 'SHS-PEH', 'STEM-PCAL', 'STEM-BCAL'],
            ],
            [
                'grade_level' => 'Grade 12',
                'section' => 'STEM-A',
                'semester' => '2nd Semester',
                'room_prefix' => 'S',
                'department' => 'Senior High School',
                'subject_codes' => ['SHS-EAPP', 'SHS-PR2', 'SHS-EMTECH', 'SHS-III', 'STEM-BIO1', 'STEM-CHEM1', 'STEM-PHY1'],
            ],
            [
                'grade_level' => 'Grade 11',
                'section' => 'ABM-A',
                'semester' => '1st Semester',
                'room_prefix' => 'S',
                'department' => 'Senior High School',
                'subject_codes' => ['SHS-OC', 'SHS-RW', 'SHS-GMATH', 'SHS-UCSP', 'SHS-PEH', 'ABM-BMATH', 'ABM-OAM', 'ABM-FABM1'],
            ],
            [
                'grade_level' => 'Grade 12',
                'section' => 'ABM-A',
                'semester' => '2nd Semester',
                'room_prefix' => 'S',
                'department' => 'Senior High School',
                'subject_codes' => ['SHS-EAPP', 'SHS-FPL', 'SHS-ENTREP', 'SHS-III', 'ABM-FABM2', 'SHS-PR2'],
            ],
            [
                'grade_level' => 'Grade 11',
                'section' => 'HUMSS-A',
                'semester' => '1st Semester',
                'room_prefix' => 'S',
                'department' => 'Senior High School',
                'subject_codes' => ['SHS-OC', 'SHS-21CL', 'SHS-UCSP', 'SHS-PEH', 'HUMSS-DISS', 'HUMSS-DIASS', 'SHS-PR1'],
            ],
            [
                'grade_level' => 'Grade 12',
                'section' => 'HUMSS-A',
                'semester' => '2nd Semester',
                'room_prefix' => 'S',
                'department' => 'Senior High School',
                'subject_codes' => ['SHS-EAPP', 'SHS-FPL', 'HUMSS-CREW', 'HUMSS-TNCT', 'SHS-III', 'SHS-PR2'],
            ],
            [
                'grade_level' => 'Grade 11',
                'section' => 'GAS-A',
                'semester' => '1st Semester',
                'room_prefix' => 'S',
                'department' => 'Senior High School',
                'subject_codes' => ['SHS-OC', 'SHS-RW', 'SHS-MIL', 'SHS-UCSP', 'SHS-PEH', 'GAS-HGP'],
            ],
            [
                'grade_level' => 'Grade 12',
                'section' => 'GAS-A',
                'semester' => '2nd Semester',
                'room_prefix' => 'S',
                'department' => 'Senior High School',
                'subject_codes' => ['SHS-EAPP', 'SHS-ENTREP', 'SHS-EMTECH', 'SHS-III', 'GAS-ORG'],
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | SCHEDULE HELPERS
        |--------------------------------------------------------------------------
        */
        $timeSlots = [
            ['07:00:00', '08:00:00'],
            ['08:00:00', '09:00:00'],
            ['09:00:00', '10:00:00'],
            ['10:00:00', '11:00:00'],
            ['11:00:00', '12:00:00'],
            ['13:00:00', '14:00:00'],
            ['14:00:00', '15:00:00'],
            ['15:00:00', '16:00:00'],
        ];

        $dayPatterns = [
            ['Monday', 'Wednesday'],
            ['Tuesday', 'Thursday'],
            ['Monday', 'Thursday'],
            ['Tuesday', 'Friday'],
            ['Wednesday', 'Friday'],
        ];

        $departmentTeacherCounters = [
            'Elementary' => 0,
            'Junior High School' => 0,
            'Senior High School' => 0,
        ];

        /*
        |--------------------------------------------------------------------------
        | INSERT CLASSES + SCHEDULES
        |--------------------------------------------------------------------------
        */
        foreach ($plans as $plan) {
            foreach ($plan['subject_codes'] as $subjectIndex => $subjectCode) {
                if (!isset($subjectMap[$subjectCode])) {
                    continue;
                }

                $teacherPool = $teacherIdsByDepartment[$plan['department']];
                if (empty($teacherPool)) {
                    continue;
                }

                $teacherId = $teacherPool[$departmentTeacherCounters[$plan['department']] % count($teacherPool)];
                $departmentTeacherCounters[$plan['department']]++;

                $roomNumber = 100 + (($subjectIndex + 1) % 10);
                $room = $plan['room_prefix'] . '-' . $roomNumber;

                $classQuery = DB::table('classes')
                    ->where('subject_id', $subjectMap[$subjectCode])
                    ->where('teacher_id', $teacherId)
                    ->where('section', $plan['section'])
                    ->where('grade_level', $plan['grade_level'])
                    ->where('school_year', $schoolYear);

                if (is_null($plan['semester'])) {
                    $classQuery->whereNull('semester');
                } else {
                    $classQuery->where('semester', $plan['semester']);
                }

                $existingClassId = $classQuery->value('id');

                $existingClassId = DB::table('classes')->insertGetId([
                    'subject_id' => $subjectMap[$subjectCode],
                    'teacher_id' => $teacherId,
                    'section' => $plan['section'],
                    'grade_level' => $plan['grade_level'],
                    'school_year' => $schoolYear,
                    'semester' => $plan['semester'],
                    'room' => $room,
                    'capacity' => 40,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $seedKey = crc32($subjectCode . '|' . $plan['section'] . '|' . $plan['grade_level']);
                $slot = $timeSlots[$seedKey % count($timeSlots)];
                $days = $dayPatterns[$seedKey % count($dayPatterns)];

                foreach ($days as $day) {
                    DB::table('schedules')->updateOrInsert(
                        [
                            'class_id' => $existingClassId,
                            'day_of_week' => $day,
                        ],
                        [
                            'start_time' => $slot[0],
                            'end_time' => $slot[1],
                            'room' => $room,
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                }
            }
        }
    }

    public function down(): void
    {
        $emails = [
            'maria.santos@agnusdei.local',
            'jose.reyes@agnusdei.local',
            'ana.cruz@agnusdei.local',
            'paolo.garcia@agnusdei.local',
            'liza.mendoza@agnusdei.local',
            'mark.torres@agnusdei.local',
            'rina.flores@agnusdei.local',
            'dennis.aquino@agnusdei.local',
            'carla.navarro@agnusdei.local',
            'vincent.luna@agnusdei.local',
            'sheila.ramos@agnusdei.local',
            'adrian.castro@agnusdei.local',
        ];

        $teacherIds = DB::table('teachers')->whereIn('email', $emails)->pluck('id')->toArray();
        $userIds = DB::table('users')->whereIn('email', $emails)->pluck('id')->toArray();

        $classIds = DB::table('classes')->whereIn('teacher_id', $teacherIds)->pluck('id')->toArray();

        if (!empty($classIds)) {
            DB::table('schedules')->whereIn('class_id', $classIds)->delete();
            DB::table('classes')->whereIn('id', $classIds)->delete();
        }

        if (!empty($teacherIds)) {
            DB::table('teachers')->whereIn('id', $teacherIds)->delete();
        }

        if (!empty($userIds)) {
            DB::table('users')->whereIn('id', $userIds)->delete();
        }
    }
};
