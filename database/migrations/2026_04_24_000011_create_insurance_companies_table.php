<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('nuit', 20)->nullable()->index();
            $table->string('contact_person')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('billing_cycle', 20)->default('monthly')->comment('weekly, monthly');
            $table->boolean('requires_preauth')->default(false)->comment('Requer pré-autorização');
            $table->decimal('default_coverage_pct', 5, 2)->default(100.00)->comment('Percentagem de cobertura padrão');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_companies');
    }
};
