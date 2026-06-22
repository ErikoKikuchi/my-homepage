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
        Schema::connection('client_db')->create('locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->boolean('is_bookable')->default(true);
            $table->unsignedInteger('price_addon_per_session')->default(0);
            $table->string('address');
            $table->decimal('base_fee', 8, 2)->nullable();
            $table->string('map_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('locations');
    }
};
