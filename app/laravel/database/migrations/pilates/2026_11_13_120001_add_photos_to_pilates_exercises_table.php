<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('client_db')->table('pilates_exercises', function (Blueprint $table) {
            $table->json('photos')->nullable();
        });
    }

    public function down(): void
    {
        Schema::connection('client_db')->table('pilates_exercises', function (Blueprint $table) {
            $table->dropColumn('photos');
        });
    }
};
