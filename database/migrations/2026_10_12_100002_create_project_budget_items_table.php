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
        Schema::create('project_budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('repair_list_item_id')->nullable()->constrained('repair_list_items')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('project_budget_items')->cascadeOnDelete();
            $table->text('name');
            $table->unsignedInteger('qty')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('unit_price', 15, 2)->nullable();
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
        Schema::dropIfExists('project_budget_items');
    }
};
