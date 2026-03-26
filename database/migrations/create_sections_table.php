<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('grade_level', 50);
            $table->string('section_name', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['grade_level', 'section_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};