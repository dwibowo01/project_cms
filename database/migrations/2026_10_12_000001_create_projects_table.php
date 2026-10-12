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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_no')->unique();
            $table->string('project_name');
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->text('description')->nullable();
            $table->date('docking_start_date');
            $table->date('docking_end_date')->nullable();
            $table->string('status')->default('Active');
            $table->string('project_type')->default('Docking');
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
        Schema::dropIfExists('projects');
    }
};
