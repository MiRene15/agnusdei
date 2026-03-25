<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_reference_codes', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['teacher', 'registrar', 'cashier', 'admin']);
            $table->string('code')->unique();

            // Plain nullable IDs to avoid FK/circular migration errors
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('section')->nullable();
            $table->string('grade_level')->nullable();
            $table->string('school_year')->nullable();
            $table->string('semester')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('used_by')->nullable();

            $table->boolean('is_used')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('used_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_reference_codes');
    }
};