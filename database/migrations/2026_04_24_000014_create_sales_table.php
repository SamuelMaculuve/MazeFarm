<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_number', 20)->unique()->index();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('insurance_card_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 20)->default('completed')->comment('completed, cancelled, refunded');
            $table->boolean('has_prescription')->default(false);
            $table->string('prescription_number', 60)->nullable();
            $table->string('prescription_doctor')->nullable();
            $table->date('prescription_date')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('customer_amount', 12, 2)->default(0)->comment('Amount customer pays');
            $table->decimal('insurance_amount', 12, 2)->default(0)->comment('Amount insurance covers');
            $table->string('insurance_auth_code', 60)->nullable()->comment('Código de autorização do seguro');
            $table->string('insurance_rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['created_at', 'branch_id']);
            $table->index(['customer_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
