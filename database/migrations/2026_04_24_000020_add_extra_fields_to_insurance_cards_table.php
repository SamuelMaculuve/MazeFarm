<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insurance_cards', function (Blueprint $table) {
            $table->string('policy_number', 60)->nullable()->after('card_number');
            $table->decimal('coverage_pct', 5, 2)->nullable()->after('employer_name')->comment('Overrides company default; null = use company default');
            $table->decimal('copay_amount', 10, 2)->default(0)->after('coverage_pct');
            $table->decimal('monthly_limit', 12, 2)->nullable()->after('coverage_limit_annual');
            $table->date('valid_from')->nullable()->after('expiry_date');
            $table->text('notes')->nullable()->after('valid_from');
        });
    }

    public function down(): void
    {
        Schema::table('insurance_cards', function (Blueprint $table) {
            $table->dropColumn(['policy_number', 'coverage_pct', 'copay_amount', 'monthly_limit', 'valid_from', 'notes']);
        });
    }
};
