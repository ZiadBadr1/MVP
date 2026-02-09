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
        Schema::create('bulk_user_failures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bulk_user_import_id')->constrained('bulk_user_imports')->cascadeOnDelete();
            $table->string('email')->nullable();
            $table->json('reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_user_failures');
    }
};
