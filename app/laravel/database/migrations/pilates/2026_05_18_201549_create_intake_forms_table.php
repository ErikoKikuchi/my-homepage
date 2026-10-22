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
        Schema::connection('client_db')->create('intake_forms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')->nullable()->constrained('clients')
            ->nullOnDelete();
            $table->string('import_source')->nullable(); // google_forms
            $table->string('import_source_key')->nullable(); // 取込元回答の識別キー
            $table->timestamp('imported_at')->nullable();
            $table->unique(['import_source', 'import_source_key']);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('intake_forms');
    }
};
