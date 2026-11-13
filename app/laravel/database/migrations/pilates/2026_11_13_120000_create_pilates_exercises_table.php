<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('client_db')->create('pilates_exercises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('supplement')->nullable();
            $table->text('precautions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('pilates_exercises');
    }
};
