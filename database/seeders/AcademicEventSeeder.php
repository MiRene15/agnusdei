<?php

namespace Database\Seeders;

use App\Models\AcademicEvent;
use Illuminate\Database\Seeder;

class AcademicEventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'event_key' => 'enrollment_open',
                'event_name' => 'Enrollment Open',
                'is_enabled' => true,
                'description' => 'Controls whether enrollment is open.',
            ],
            [
                'event_key' => 'ptc_required',
                'event_name' => 'PTC Required',
                'is_enabled' => false,
                'description' => 'Controls whether PTC is required before grades release.',
            ],
            [
                'event_key' => 'grade_encoding_open',
                'event_name' => 'Grade Encoding Open',
                'is_enabled' => true,
                'description' => 'Controls whether teachers are allowed to encode grades during the active grading window.',
            ],
        ];

        foreach ($events as $event) {
            AcademicEvent::updateOrCreate(
                ['event_key' => $event['event_key']],
                $event
            );
        }
    }
}
