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
            $table->foreignUuid('client_id')->constrained()->restrictOnDelete();
            $table->unsignedTinyInteger('passes_purchased'); // 購入回数または移行残回数
            $table->date('purchased_at')->nullable();
            $table->boolean('is_finished')->default(false);
            $table->enum('pass_type', ['session', 'venue_addon']);
            $table->foreignUuid('location_id')->nullable()->constrained()->restrictOnDelete();
            $table->timestamp('imported_at')->nullable();
            $table->text('note')->nullable();
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
