<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AcademicEventSeeder::class,
            RoleReferenceCodeSeeder::class,
            SeedDefaultSubjects::class,
            DefaultStaffSeeder::class,
            SectionSeeder::class,
            TeachersClassesSchedulesSeeder::class,
            DefaultStudentsSeeder::class,
            TuitionFeeSeeder::class,
            DefaultPaymentsSeeder::class,
            DefaultGradesSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
