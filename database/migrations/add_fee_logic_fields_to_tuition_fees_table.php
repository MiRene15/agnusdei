<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tuition_fees', function (Blueprint $table) {
            if (!Schema::hasColumn('tuition_fees', 'down_payment_required')) {
                $table->decimal('down_payment_required', 10, 2)->default(0)->after('total_amount');
            }

            if (!Schema::hasColumn('tuition_fees', 'monthly_payment')) {
                $table->decimal('monthly_payment', 10, 2)->default(0)->after('down_payment_required');
            }

            if (!Schema::hasColumn('tuition_fees', 'previous_balance')) {
                $table->decimal('previous_balance', 10, 2)->default(0)->after('monthly_payment');
            }

            if (!Schema::hasColumn('tuition_fees', 'total_due')) {
                $table->decimal('total_due', 10, 2)->default(0)->after('previous_balance');
            }

            if (!Schema::hasColumn('tuition_fees', 'is_downpayment_cleared')) {
                $table->boolean('is_downpayment_cleared')->default(false)->after('total_due');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tuition_fees', function (Blueprint $table) {
            foreach ([
                'is_downpayment_cleared',
                'total_due',
                'previous_balance',
                'monthly_payment',
                'down_payment_required',
            ] as $column) {
                if (Schema::hasColumn('tuition_fees', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};