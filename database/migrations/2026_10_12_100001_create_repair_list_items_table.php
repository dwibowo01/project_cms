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
        Schema::create('repair_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quotation_item_id')->nullable()->constrained('quotation_items')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('repair_list_items')->cascadeOnDelete();
            $table->text('name');
            $table->unsignedInteger('qty')->nullable();
            $table->string('unit')->nullable();
            $table->foreignId('pic_user_id')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('repair_list_items');
    }
};
