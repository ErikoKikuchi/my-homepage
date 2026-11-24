<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('client_db')->create('reservation_closures', function (Blueprint $table) {
            $table->date('date')->primary();
            $table->string('period', 10);
        });
    }

    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('reservation_closures');
    }
};
