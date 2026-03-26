<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->string('shs_track')->nullable()->after('applying_for_grade');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('shs_track')->nullable()->after('grade_level');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('shs_track');
        });

        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn('shs_track');
        });
    }
};
