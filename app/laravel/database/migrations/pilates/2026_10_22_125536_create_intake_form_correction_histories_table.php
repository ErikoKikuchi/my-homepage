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
        Schema::connection('client_db')->create('intake_form_correction_histories', function (Blueprint $table) {
        $table->uuid('id')->primary();

        $table->foreignUuid('intake_form_id')
            ->constrained('intake_forms')
            ->restrictOnDelete();

        $table->foreignUuid('initial_consultation_id')
            ->constrained('initial_consultations')
            ->restrictOnDelete();

        // 訂正直前・直後の回答内容
        $table->json('before_values');
        $table->json('after_values');

        $table->text('reason');

        // 操作した管理者。thinkmotion_db.adminsを参照
        $table->uuid('corrected_by_admin_id')->index();
        $table->foreign('corrected_by_admin_id')->references('id')->on('thinkmotion_db.admins')->restrictOnDelete();
        $table->timestamp('corrected_at');

        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('intake_form_correction_histories');
    }
};
