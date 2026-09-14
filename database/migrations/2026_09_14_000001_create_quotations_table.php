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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quote_no');
            $table->unsignedInteger('revision')->default(0);
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->foreignId('client_contact_id')->nullable()->constrained('client_contacts')->nullOnDelete();
            $table->foreignId('ship_id')->constrained()->restrictOnDelete();
            $table->foreignId('master_item_category_id')->nullable()->constrained('master_item_categories')->nullOnDelete();
            $table->foreignId('source_quotation_id')->nullable()->constrained('quotations')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('docking_year');
            $table->string('survey_type');
            $table->date('quotation_date');
            $table->string('tenant_id')->nullable();
            $table->timestamps();

            $table->unique(['quote_no', 'revision']);
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
