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
        Schema::table('confirm_orders', function (Blueprint $table) {
            // Set once at creation (used to seed the auto-generated Down Payment
            // invoice and to render the "Pembayaran Pertama" payment term on the PDF).
            $table->decimal('down_payment_amount', 15, 2)->nullable()->after('ppn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('confirm_orders', function (Blueprint $table) {
            $table->dropColumn('down_payment_amount');
        });
    }
};
