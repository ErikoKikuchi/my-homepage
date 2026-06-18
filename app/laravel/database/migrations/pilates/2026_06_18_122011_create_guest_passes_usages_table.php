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
        Schema::connection('client_db')->create('guest_passes_usages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('guest_pass_purchase_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('client_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('reservation_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('status', ['reserved', 'completed', 'cancelled'])->default('reserved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('guest_passes_usages');
    }
};
