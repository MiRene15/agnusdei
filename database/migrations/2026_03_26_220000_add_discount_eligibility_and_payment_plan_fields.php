<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            if (!Schema::hasColumn('admissions', 'previous_school_type')) {
                $table->string('previous_school_type')->nullable()->after('previous_school');
            }

            if (!Schema::hasColumn('admissions', 'honor_rank')) {
                $table->unsignedTinyInteger('honor_rank')->nullable()->after('previous_school_type');
            }
        });

        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'previous_school_type')) {
                $table->string('previous_school_type')->nullable()->after('shs_track');
            }

            if (!Schema::hasColumn('students', 'honor_rank')) {
                $table->unsignedTinyInteger('honor_rank')->nullable()->after('previous_school_type');
            }
        });

        Schema::table('tuition_fees', function (Blueprint $table) {
            if (!Schema::hasColumn('tuition_fees', 'payment_plan')) {
                $table->string('payment_plan')->default('monthly')->after('school_year');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tuition_fees', function (Blueprint $table) {
            if (Schema::hasColumn('tuition_fees', 'payment_plan')) {
                $table->dropColumn('payment_plan');
            }
        });

        Schema::table('students', function (Blueprint $table) {
            $drop = [];
            if (Schema::hasColumn('students', 'previous_school_type')) {
                $drop[] = 'previous_school_type';
            }
            if (Schema::hasColumn('students', 'honor_rank')) {
                $drop[] = 'honor_rank';
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });

        Schema::table('admissions', function (Blueprint $table) {
            $drop = [];
            if (Schema::hasColumn('admissions', 'previous_school_type')) {
                $drop[] = 'previous_school_type';
            }
            if (Schema::hasColumn('admissions', 'honor_rank')) {
                $drop[] = 'honor_rank';
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
