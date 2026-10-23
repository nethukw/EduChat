<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    // First, drop the table if it exists to avoid duplication errors
    Schema::dropIfExists('quiz_results');

    Schema::create('quiz_results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->integer('score');
        $table->integer('total_questions')->default(0);
        $table->integer('correct_answers')->default(0);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('quiz_results');
    }
};