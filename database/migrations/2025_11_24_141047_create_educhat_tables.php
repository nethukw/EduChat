<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Update Users Table (Roles, Skills, Social Login)
        Schema::table('users', function (Blueprint $table) {
            // Social Login
            $table->string('google_id')->nullable();
            $table->string('github_id')->nullable();
            $table->string('avatar')->nullable();
            
            // Profile & Gamification
            $table->text('bio')->nullable();
            $table->string('academic_level')->nullable(); // Beginner, Intermediate, Advanced
            $table->json('skills')->nullable(); // e.g. ["Java", "Laravel"]
            $table->json('interests')->nullable();
            
            // Role Management
            $table->tinyInteger('role')->default(0)->comment('0:Student, 1:Teacher, 2:Advisor, 3:Admin');
            $table->integer('points')->default(0);
            $table->boolean('is_active')->default(true);
        });

        // 2. Learning Sessions (Renamed from 'sessions' to fix conflict)
        Schema::create('learning_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->dateTime('start_time');
            $table->integer('duration_minutes')->default(60);
            
            // Fee Logic (Free, Shared, Per-Person)
            $table->enum('fee_type', ['free', 'shared', 'per_person'])->default('free');
            $table->decimal('base_fee', 10, 2)->default(0); 
            $table->integer('max_students')->nullable();
            
            $table->enum('status', ['pending', 'approved', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        // 3. Enrollments (Students joining sessions)
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            // Pointing to 'learning_sessions' table
            $table->foreignId('session_id')->constrained('learning_sessions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'paid', 'attended'])->default('pending');
            $table->timestamps();
        });

        // 4. Topic Requests (Student suggestions)
        Schema::create('topic_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->string('topic');
            $table->text('description')->nullable();
            $table->integer('votes')->default(0);
            $table->enum('status', ['open', 'approved', 'rejected'])->default('open');
            $table->timestamps();
        });

        // 5. Discussion Forum (Questions)
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->string('subject_tag');
            $table->integer('views')->default(0);
            $table->boolean('is_solved')->default(false);
            $table->timestamps();
        });

        // 6. Answers (With Advisor Verification)
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('body');
            
            // Advisor Verification
            $table->enum('verification_status', ['none', 'verified', 'incorrect', 'needs_review'])->default('none');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->string('advisor_note')->nullable();
            
            $table->timestamps();
        });

        // 7. Quizzes
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            // Pointing to 'learning_sessions' table
            $table->foreignId('session_id')->nullable()->constrained('learning_sessions'); 
            $table->string('title');
            $table->timestamps();
        });

        // 8. Reviews (Feedback)
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // Pointing to 'learning_sessions' table
            $table->foreignId('session_id')->constrained('learning_sessions');
            $table->foreignId('student_id')->constrained('users');
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // 9. Reports (For Admins/Advisors)
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reported_by')->constrained('users');
            $table->morphs('reportable'); // Can report a Question, Answer, or User
            $table->string('reason');
            $table->enum('status', ['pending', 'resolved', 'dismissed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('topic_requests');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('learning_sessions'); // Dropping the renamed table
        // Note: Users table columns drop logic is skipped for simplicity in development
    }
};