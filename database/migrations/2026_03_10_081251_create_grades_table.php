<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
            $table->string('grading_period');
            $table->decimal('grade', 5, 2);
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unique(['enrollment_id', 'grading_period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};