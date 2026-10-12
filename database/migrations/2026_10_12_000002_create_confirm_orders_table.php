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
        Schema::create('confirm_orders', function (Blueprint $table) {
            $table->id();
            $table->string('confirm_order_no')->unique();
            $table->foreignId('quotation_id')->constrained()->restrictOnDelete();
            $table->foreignId('project_id')->constrained()->restrictOnDelete();
            $table->date('docking_start_date');
            $table->boolean('ppn')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('confirm_orders');
    }
};
