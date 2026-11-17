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
        Schema::connection('client_db')->create('client_body_notes', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('client_id')->constrained('clients')->cascadeOnDelete();
    $table->text('body_note');
    $table->date('observed_on')->nullable(); 
    $table->timestamps();

    $table->index(['client_id', 'observed_on']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_body_notes');
    }
};
