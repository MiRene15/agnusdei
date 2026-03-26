<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            if (!Schema::hasColumn('grades', 'seatwork_score')) {
                $table->decimal('seatwork_score', 5, 2)->default(0)->after('grading_period');
            }

            if (!Schema::hasColumn('grades', 'quiz_score')) {
                $table->decimal('quiz_score', 5, 2)->default(0)->after('seatwork_score');
            }

            if (!Schema::hasColumn('grades', 'exam_score')) {
                $table->decimal('exam_score', 5, 2)->default(0)->after('quiz_score');
            }

            if (!Schema::hasColumn('grades', 'final_grade')) {
                $table->decimal('final_grade', 5, 2)->default(0)->after('exam_score');
            }
        });
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            foreach (['final_grade', 'exam_score', 'quiz_score', 'seatwork_score'] as $column) {
                if (Schema::hasColumn('grades', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
