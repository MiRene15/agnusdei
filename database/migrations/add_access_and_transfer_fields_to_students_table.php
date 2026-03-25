<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'portal_access_status')) {
                $table->string('portal_access_status')->default('locked')->after('status');
            }

            if (!Schema::hasColumn('students', 'portal_unlocked_at')) {
                $table->timestamp('portal_unlocked_at')->nullable()->after('portal_access_status');
            }

            if (!Schema::hasColumn('students', 'is_transferred')) {
                $table->boolean('is_transferred')->default(false)->after('portal_unlocked_at');
            }

            if (!Schema::hasColumn('students', 'transferred_at')) {
                $table->timestamp('transferred_at')->nullable()->after('is_transferred');
            }

            if (!Schema::hasColumn('students', 'transfer_notes')) {
                $table->text('transfer_notes')->nullable()->after('transferred_at');
            }

            if (!Schema::hasColumn('students', 'ptc_completed')) {
                $table->boolean('ptc_completed')->default(false)->after('transfer_notes');
            }

            if (!Schema::hasColumn('students', 'ptc_completed_at')) {
                $table->timestamp('ptc_completed_at')->nullable()->after('ptc_completed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            foreach ([
                'ptc_completed_at',
                'ptc_completed',
                'transfer_notes',
                'transferred_at',
                'is_transferred',
                'portal_unlocked_at',
                'portal_access_status',
            ] as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};