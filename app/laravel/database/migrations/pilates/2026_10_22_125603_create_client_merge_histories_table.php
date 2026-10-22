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
        
        Schema::connection('client_db')->create('client_merge_histories', function (Blueprint $table) {
        $table->uuid('id')->primary();

        // 当時のClient ID。物理削除後も保持するためFKは付けない
        $table->uuid('source_client_id')->index();
        $table->uuid('target_client_id')->index();

        // 当時のUserとの紐づきを記録
        $table->uuid('source_user_id')->nullable();
        $table->uuid('target_user_id')->nullable();

        $table->text('reason');

        $table->enum('status', [
            'pending',
            'migrated',
            'verified',
            'completed',
            'failed',
        ])->default('pending');

        // 移行対象ごとの件数、検証結果など
        $table->json('migration_summary')->nullable();
        $table->text('failure_detail')->nullable();

        // 操作した管理者。thinkmotion_db.adminsを参照
        $table->uuid('initiated_by_admin_id');
        $table->uuid('verified_by_admin_id')->nullable();
        $table->uuid('deleted_by_admin_id')->nullable();

        $table->foreign('initiated_by_admin_id')->references('id')->on('thinkmotion_db.admins')->restrictOnDelete();

        $table->foreign('verified_by_admin_id')->references('id')->on('thinkmotion_db.admins')->restrictOnDelete();

        $table->foreign('deleted_by_admin_id')->references('id')->on('thinkmotion_db.admins')->restrictOnDelete();

        $table->timestamp('started_at');
        $table->timestamp('migrated_at')->nullable();
        $table->timestamp('verified_at')->nullable();
        $table->timestamp('source_deleted_at')->nullable();

        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('client_merge_histories');
    }
};
