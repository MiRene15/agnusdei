<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tuition_fees', function (Blueprint $table) {
            $table->boolean('carryover_approved')->default(false)->after('balance');
            $table->timestamp('carryover_approved_at')->nullable()->after('carryover_approved');
            $table->unsignedBigInteger('carryover_approved_by')->nullable()->after('carryover_approved_at');
            $table->string('carried_over_to_school_year')->nullable()->after('carryover_approved_by');

            $table->foreign('carryover_approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tuition_fees', function (Blueprint $table) {
            $table->dropForeign(['carryover_approved_by']);
            $table->dropColumn([
                'carryover_approved',
                'carryover_approved_at',
                'carryover_approved_by',
                'carried_over_to_school_year',
            ]);
        });
    }
};
