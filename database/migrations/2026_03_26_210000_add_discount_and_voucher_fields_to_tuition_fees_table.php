<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tuition_fees', function (Blueprint $table) {
            if (!Schema::hasColumn('tuition_fees', 'discount_type')) {
                $table->string('discount_type')->nullable()->after('carried_over_to_school_year');
            }

            if (!Schema::hasColumn('tuition_fees', 'discount_rate')) {
                $table->decimal('discount_rate', 5, 2)->default(0)->after('discount_type');
            }

            if (!Schema::hasColumn('tuition_fees', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_rate');
            }

            if (!Schema::hasColumn('tuition_fees', 'discount_notes')) {
                $table->string('discount_notes')->nullable()->after('discount_amount');
            }

            if (!Schema::hasColumn('tuition_fees', 'voucher_status')) {
                $table->string('voucher_status')->default('not_applicable')->after('carried_over_to_school_year');
            }

            if (!Schema::hasColumn('tuition_fees', 'voucher_registrar_verified_at')) {
                $table->timestamp('voucher_registrar_verified_at')->nullable()->after('voucher_status');
            }

            if (!Schema::hasColumn('tuition_fees', 'voucher_registrar_verified_by')) {
                $table->unsignedBigInteger('voucher_registrar_verified_by')->nullable()->after('voucher_registrar_verified_at');
                $table->foreign('voucher_registrar_verified_by')->references('id')->on('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('tuition_fees', 'voucher_cashier_verified_at')) {
                $table->timestamp('voucher_cashier_verified_at')->nullable()->after('voucher_registrar_verified_by');
            }

            if (!Schema::hasColumn('tuition_fees', 'voucher_cashier_verified_by')) {
                $table->unsignedBigInteger('voucher_cashier_verified_by')->nullable()->after('voucher_cashier_verified_at');
                $table->foreign('voucher_cashier_verified_by')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tuition_fees', function (Blueprint $table) {
            if (Schema::hasColumn('tuition_fees', 'voucher_cashier_verified_by')) {
                $table->dropForeign(['voucher_cashier_verified_by']);
            }

            if (Schema::hasColumn('tuition_fees', 'voucher_registrar_verified_by')) {
                $table->dropForeign(['voucher_registrar_verified_by']);
            }

            $columns = [
                'discount_type',
                'discount_rate',
                'discount_amount',
                'discount_notes',
                'voucher_status',
                'voucher_registrar_verified_at',
                'voucher_registrar_verified_by',
                'voucher_cashier_verified_at',
                'voucher_cashier_verified_by',
            ];

            $existing = array_values(array_filter($columns, fn ($column) => Schema::hasColumn('tuition_fees', $column)));
            if (!empty($existing)) {
                $table->dropColumn($existing);
            }
        });
    }
};
