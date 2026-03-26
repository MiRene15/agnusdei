<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'Enrollment Window for School Year 2026-2027',
                'message' => 'Enrollment and assessment for the upcoming school year are now open. Families are encouraged to complete requirements and payment coordination early to secure preferred schedules and sections.',
                'audience' => 'public',
                'posted_at' => now()->subDays(2),
            ],
            [
                'title' => 'Parent-Teacher Conference Schedule Reminder',
                'message' => 'Parents and guardians are reminded to coordinate with advisers regarding scheduled face-to-face conferences before final grade release and report card distribution.',
                'audience' => 'parents',
                'posted_at' => now()->subDays(4),
            ],
            [
                'title' => 'Cashier Transactions and Receipt Release',
                'message' => 'All tuition and down payment transactions are processed through the cashier office in cash only. Digital printable receipts are released right after payment posting for record keeping.',
                'audience' => 'students',
                'posted_at' => now()->subDays(6),
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::updateOrCreate(
                ['title' => $announcement['title']],
                $announcement
            );
        }
    }
}

