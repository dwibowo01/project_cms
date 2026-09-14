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
        Schema::create('master_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('master_item_categories')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('master_items')->cascadeOnDelete();
            $table->text('name');
            $table->unsignedInteger('qty')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('unit_price', 15, 2)->default(0);
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
        Schema::dropIfExists('master_items');
    }
};
