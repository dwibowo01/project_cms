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
        Schema::create('master_item_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tenant_id')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->unique(['tenant_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_item_categories');
    }
};
