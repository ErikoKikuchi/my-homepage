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
        Schema::connection('client_db')->create('clients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('name');
            $table->enum('gender', ['male','female','other']);
            $table->string('occupation')->nullable();
            $table->text('body_notes')->nullable();
            $table->text('personality_notes')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('has_unpaid_fee')->default(false);
            $table->boolean('line_linked')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('clients');
    }
};
