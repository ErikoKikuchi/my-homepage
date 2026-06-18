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
        Schema::connection('client_db')->create('guest_pass_purchases', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('client_id')->constrained()->cascadeOnDelete();
    $table->unsignedTinyInteger('passes_purchased'); // 3 or 10
    $table->unsignedInteger('amount_paid')->nullable();
    $table->string('payment_method')->nullable(); // cash, stripe等
    $table->date('purchased_at');
    $table->boolean('is_finished')->default(false);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('guest_pass_purchases');
    }
};
