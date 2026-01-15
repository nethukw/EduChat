<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    // 1. Add Category to Sessions (For Filtering)
    Schema::table('learning_sessions', function (Blueprint $table) {
        $table->string('category')->nullable()->after('title'); // IT, Math, Science
    });

    // 2. Create Materials Table (For Uploads)
    Schema::create('materials', function (Blueprint $table) {
        $table->id();
        $table->foreignId('session_id')->constrained('learning_sessions')->onDelete('cascade');
        $table->string('title');
        $table->string('file_path'); // Path in storage
        $table->timestamps();
    });

    // 3. Create Notifications Table (Laravel Standard)
    Schema::create('notifications', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->string('type');
        $table->morphs('notifiable');
        $table->text('data');
        $table->timestamp('read_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
