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
        Schema::connection('client_db')->create('initial_consultations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')->nullable()->references('id')->on('clients');
            $table->foreignUuid('user_id');
            $table->foreignUuid('intake_form_id')->references('id')->on('intake_forms');

            // セクション1：基本情報
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->string('contact')->nullable();
            $table->text('daily_posture')->nullable();

            // セクション2：身体の状態
            $table->json('symptoms');          // 複数選択 → JSON配列
            $table->text('symptom_detail')->nullable();
            $table->text('medical_history')->nullable();
            $table->text('current_treatment')->nullable();

            // セクション3：目標
            $table->tinyInteger('session_goal'); // 1:整体 2:ピラティス 3:両方
            $table->text('future_goal')->nullable();

            // セクション4：同意
            $table->boolean('consent_non_medical')->default(false);
            $table->boolean('consent_health_condition')->default(false);
            $table->boolean('consent_report_discomfort')->default(false);
            $table->timestamp('consented_at')->nullable();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('initial_consultations');
    }
};
