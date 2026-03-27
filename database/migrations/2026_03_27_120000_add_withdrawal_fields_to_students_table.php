<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'withdrawal_requested_at')) {
                $table->timestamp('withdrawal_requested_at')->nullable()->after('ptc_completed_at');
            }

            if (!Schema::hasColumn('students', 'withdrawal_effective_at')) {
                $table->timestamp('withdrawal_effective_at')->nullable()->after('withdrawal_requested_at');
            }

            if (!Schema::hasColumn('students', 'withdrawal_reason')) {
                $table->text('withdrawal_reason')->nullable()->after('withdrawal_effective_at');
            }

            if (!Schema::hasColumn('students', 'withdrawal_policy_acknowledged')) {
                $table->boolean('withdrawal_policy_acknowledged')->default(false)->after('withdrawal_reason');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            foreach ([
                'withdrawal_policy_acknowledged',
                'withdrawal_reason',
                'withdrawal_effective_at',
                'withdrawal_requested_at',
            ] as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
