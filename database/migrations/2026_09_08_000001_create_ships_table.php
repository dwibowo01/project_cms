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
        Schema::create('ships', function (Blueprint $table) {
            $table->id();
            $table->string('ship_name');
            $table->string('ship_type');
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->decimal('loa_value', 8, 2);
            $table->string('loa_unit');
            $table->decimal('lbp_value', 8, 2)->nullable();
            $table->string('lbp_unit')->nullable();
            $table->decimal('height_value', 8, 2)->nullable();
            $table->string('height_unit')->nullable();
            $table->decimal('width_value', 8, 2)->nullable();
            $table->string('width_unit')->nullable();
            $table->decimal('draught_value', 8, 2)->nullable();
            $table->string('draught_unit')->nullable();
            $table->decimal('gt', 12, 2)->nullable();
            $table->decimal('nt', 12, 2)->nullable();
            $table->decimal('power_me', 10, 2)->nullable();
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
        Schema::dropIfExists('ships');
    }
};
