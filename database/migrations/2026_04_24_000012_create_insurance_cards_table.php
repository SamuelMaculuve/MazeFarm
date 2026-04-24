<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('insurance_company_id')->constrained()->cascadeOnDelete();
            $table->string('card_number', 60)->index();
            $table->string('employee_number', 60)->nullable();
            $table->string('employer_name')->nullable();
            $table->decimal('coverage_limit_annual', 12, 2)->nullable()->comment('Limite anual de cobertura em MZN');
            $table->decimal('coverage_used', 12, 2)->default(0)->comment('Valor já utilizado no ano');
            $table->date('expiry_date')->index();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['card_number', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_cards');
    }
};
