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
        // Learning Sessions table එකට එකතු කිරීම
    Schema::table('learning_sessions', function (Blueprint $table) {
        if (!Schema::hasColumn('learning_sessions', 'deleted_at')) {
            $table->softDeletes();
        }
    });

    // Questions table එකට එකතු කිරීම
    Schema::table('questions', function (Blueprint $table) {
        if (!Schema::hasColumn('questions', 'deleted_at')) {
            $table->softDeletes();
        }
    });

    // Answers table එකට එකතු කිරීම
    Schema::table('answers', function (Blueprint $table) {
        if (!Schema::hasColumn('answers', 'deleted_at')) {
            $table->softDeletes();
        }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_sessions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('questions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('answers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
