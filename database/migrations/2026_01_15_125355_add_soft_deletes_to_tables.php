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
        // Adding soft deletes to learning_sessions table
    Schema::table('learning_sessions', function (Blueprint $table) {
        if (!Schema::hasColumn('learning_sessions', 'deleted_at')) {
            $table->softDeletes();
        }
    });

    // adding soft deletes to questions table
    Schema::table('questions', function (Blueprint $table) {
        if (!Schema::hasColumn('questions', 'deleted_at')) {
            $table->softDeletes();
        }
    });

    // adding soft deletes to answers table
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
