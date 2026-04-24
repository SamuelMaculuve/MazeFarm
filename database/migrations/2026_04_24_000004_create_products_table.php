<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('generic_name')->index();
            $table->string('commercial_name')->nullable()->index();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('pharmaceutical_form')->nullable();
            $table->string('dosage')->nullable();
            $table->string('unit', 30)->default('unidade');
            $table->string('barcode', 50)->nullable()->unique()->index();
            $table->string('fnm_code', 20)->nullable()->comment('Código do Formulário Nacional de Medicamentos');
            $table->boolean('requires_prescription')->default(false)->comment('Medicamento Sujeito a Receita');
            $table->boolean('is_controlled')->default(false)->comment('Substância controlada');
            $table->boolean('is_narcotic')->default(false)->comment('Narcótico/Psicotrópico');
            $table->integer('minimum_stock')->default(0);
            $table->integer('reorder_point')->default(0);
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('sale_price', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('country_of_origin', 60)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
