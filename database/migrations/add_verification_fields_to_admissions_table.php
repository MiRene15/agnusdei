<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            if (!Schema::hasColumn('admissions', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('status');
            }

            if (!Schema::hasColumn('admissions', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('is_verified');
            }

            if (!Schema::hasColumn('admissions', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('admissions', 'institutional_email')) {
                $table->string('institutional_email')->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            if (Schema::hasColumn('admissions', 'verified_by')) {
                $table->dropConstrainedForeignId('verified_by');
            }

            if (Schema::hasColumn('admissions', 'verified_at')) {
                $table->dropColumn('verified_at');
            }

            if (Schema::hasColumn('admissions', 'is_verified')) {
                $table->dropColumn('is_verified');
            }

            if (Schema::hasColumn('admissions', 'institutional_email')) {
                $table->dropColumn('institutional_email');
            }
        });
    }
};