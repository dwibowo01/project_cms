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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->string('invoice_type');
            $table->foreignId('confirm_order_id')->constrained()->restrictOnDelete();
            // Jumlah Invoice (net, already reduced by previously paid DP/Progress
            // invoices for Progress/Final types). Null gross/deduction for Down Payment.
            $table->decimal('gross_amount', 15, 2)->nullable();
            $table->decimal('deduction_amount', 15, 2)->default(0);
            $table->decimal('amount', 15, 2);
            // Header-level discount applied to "amount" before PPN. Null/0 for Down
            // Payment, which has no PPN step at all.
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0);
            // amount - discount_amount; PPN is computed on this, not on "amount".
            $table->decimal('taxable_amount', 15, 2);
            $table->decimal('ppn_percentage', 5, 2)->nullable();
            $table->decimal('ppn_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->string('status')->default('Unpaid');
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
        Schema::dropIfExists('invoices');
    }
};
