<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_user', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['tenant_id', 'user_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });

        // Backfill from existing users.tenant_id
        DB::statement("INSERT INTO tenant_user (tenant_id, user_id, created_at, updated_at)
            SELECT tenant_id, id, NOW(), NOW()
            FROM users
            WHERE tenant_id IS NOT NULL");
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_user');
    }
};
