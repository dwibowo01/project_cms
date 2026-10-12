<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('repair_list_item_id')->nullable()->constrained('repair_list_items')->nullOnDelete();
            $table->text('name');
            $table->decimal('fee', 15, 2);
            $table->decimal('progress_percentage', 5, 2)->default(0);
            // Per-line discount applied before the invoice-level discount/PPN steps.
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('amount', 15, 2);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('tenant_id')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
