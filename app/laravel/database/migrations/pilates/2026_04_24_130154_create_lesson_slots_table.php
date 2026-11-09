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
        Schema::connection('client_db')->create('lesson_slots', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('lesson_template_id')->constrained()->restrictOnDelete();
            $table->date('date');
            $table->foreignUuid('location_id')->nullable()->constrained();
            $table->softDeletes();
            $table->tinyInteger('active_slot')
                ->nullable()
                ->storedAs('IF(deleted_at IS NULL, 1, NULL)');
            $table->unique(
                ['date', 'lesson_template_id', 'active_slot'],
                'lesson_slots_active_date_template_unique'
            );
            $table->boolean('is_bookable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('lesson_slots');
    }
};
