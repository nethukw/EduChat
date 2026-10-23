create database educhat_db1;
-- ======================================================
-- 🎓 EDUCHAT FULL DATABASE & DUMMY DATA (FINAL VERSION)
-- ======================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 1. Database Creation
CREATE DATABASE IF NOT EXISTS `educhat_db1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE educhat_db1;

-- =============================================
-- 🛠️ TABLE STRUCTURES
-- =============================================

-- 1. Users Table
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:Student, 1:Teacher, 2:Advisor, 3:Admin',
  `points` int(11) NOT NULL DEFAULT 0,
  `academic_level` varchar(255) DEFAULT 'Beginner',
  `skills` json DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

select * from users;

-- 2. Learning Sessions Table
DROP TABLE IF EXISTS `learning_sessions`;
CREATE TABLE `learning_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT 'General',
  `description` text NOT NULL,
  `meeting_link` varchar(255) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 60,
  `fee_type` enum('free','shared','per_person') NOT NULL DEFAULT 'free',
  `base_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','approved','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Topic Requests Table (Vidyarthiya Manavigalu)
DROP TABLE IF EXISTS `topic_requests`;
CREATE TABLE `topic_requests` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(100) DEFAULT 'General',
  `topic` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `votes` int(11) NOT NULL DEFAULT 0,
  `status` enum('open','approved','rejected') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Forum Questions
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `subject_tag` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Forum Answers (with Advisor verification)
DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `verification_status` enum('none','verified','incorrect','needs_review') NOT NULL DEFAULT 'none',
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Quizzes
DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE `quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`session_id`) REFERENCES `learning_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Quiz Questions (MCQ)
DROP TABLE IF EXISTS `quiz_questions`;
CREATE TABLE `quiz_questions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` char(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Quiz Results
DROP TABLE IF EXISTS `quiz_results`;
CREATE TABLE `quiz_results` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Points Logs (XP Tracking)
DROP TABLE IF EXISTS `points_logs`;
CREATE TABLE `points_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `points` int(11) NOT NULL,
  `category` varchar(100) DEFAULT 'General',
  `activity_type` varchar(50) DEFAULT 'engagement',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Enrollments
DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(50) DEFAULT 'paid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`session_id`) REFERENCES `learning_sessions` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 💾 DUMMY DATA INSERTION
-- =============================================

-- 1. USERS (Pass: 'password')
INSERT INTO `users` (id, name, email, password, role, points, academic_level, created_at) VALUES
(9, 'Admin System', 'admin@educhat.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 0, 'Expert', NOW());

-- 2. SESSIONS
INSERT INTO `learning_sessions` (id, teacher_id, title, category, description, start_time, duration_minutes, fee_type, base_fee, status, created_at) VALUES
(1, 2, 'Laravel Masterclass', 'Information Technology', 'Full guide to building web apps.', DATE_ADD(NOW(), INTERVAL 2 DAY), 120, 'shared', 5000, 'approved', NOW()),
(2, 3, 'Calculus Core Concepts', 'Mathematics', 'Step by step integration guide.', DATE_ADD(NOW(), INTERVAL 1 DAY), 60, 'free', 0, 'approved', NOW());

-- 3. TOPIC REQUESTS
INSERT INTO `topic_requests` (student_id, subject, topic, description, votes, status, created_at) VALUES
(6, 'Information Technology', 'Python for Data Science', 'I want to learn Numpy and Pandas.', 15, 'open', NOW()),
(5, 'Science', 'Atomic Structures', 'Requesting a session on Bohr model.', 8, 'open', NOW());

-- 4. FORUM CONTENT
INSERT INTO `questions` (id, user_id, title, body, subject_tag, created_at) VALUES
(10, 5, 'Laravel Middleware Roles', 'How to check roles in middleware?', 'Information Technology', NOW()),
(11, 6, 'Newton second law derivation', 'Need step by step derivation for F=ma.', 'Science', NOW());

-- 5. ANSWERS (Unverified items for Advisor Sunil to Review)
INSERT INTO `answers` (question_id, user_id, body, verification_status, created_at) VALUES
(10, 2, 'You can use Auth::user()->role to check.', 'none', NOW()),
(11, 3, 'Force is the rate of change of momentum.', 'none', NOW());

-- 6. QUIZZES
INSERT INTO `quizzes` (id, session_id, title, created_at) VALUES
(1, 1, 'Laravel Level 1 Test', NOW()),
(2, 2, 'Calculus Basics', NOW());

-- 7. QUIZ QUESTIONS
INSERT INTO `quiz_questions` (quiz_id, question, option_a, option_b, option_c, option_d, correct_option, created_at) VALUES
(1, 'Artisan command to start server?', 'serve', 'run', 'start', 'exec', 'A', NOW()),
(2, 'Derivative of x^2?', '2x', 'x', '2', '3x', 'A', NOW());

-- 8. XP LOGS
INSERT INTO `points_logs` (user_id, points, category, activity_type, created_at) VALUES
(2, 1500, 'General', 'bonus', NOW()),
(3, 1200, 'General', 'bonus', NOW()),
(5, 990, 'General', 'engagement', NOW()),
(6, 450, 'General', 'engagement', NOW()),
(8, 5000, 'General', 'legacy', NOW());

-- 9. ENROLLMENTS
INSERT INTO `enrollments` (session_id, student_id, paid_amount, status, created_at) VALUES
(1, 5, 500, 'paid', NOW()),
(1, 6, 500, 'paid', NOW());

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;



SHOW TABLES;
SELECT * FROM migrations;
INSERT INTO migrations (migration, batch) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1);
INSERT IGNORE INTO migrations (migration, batch)
VALUES ('2025_11_24_141047_create_educhat_tables', 1);

INSERT IGNORE INTO migrations (migration, batch)
VALUES ('2025_11_24_165429_create_quiz_questions_table', 1);

INSERT IGNORE INTO migrations (migration, batch) VALUES
('2025_11_24_165429_create_quiz_questions_table', 1),
('2025_11_24_141047_create_educhat_tables', 1),
('0001_01_01_000000_create_users_table', 1);


INSERT INTO users (id, name, email, password, role, points, academic_level, created_at, updated_at) VALUES


(2, 'Mr. Nimal Perera', 'nimal@educhat.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
1, 0, 'MSc Computer Science', NOW(), NOW()),

(3, 'Ms. Kanthi Silva', 'kanthi@educhat.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
1, 0, 'BSc Mathematics', NOW(), NOW()),

(5, 'Kasun Bandara', 'kasun@educhat.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
0, 0, 'AL Undergraduate', NOW(), NOW()),

(6, 'Amila De Silva', 'amila@educhat.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
0, 0, 'AL Student', NOW(), NOW()),

(8, 'Dr. Sunil Perera', 'sunil@educhat.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
2, 0, 'Academic Quality Expert', NOW(), NOW());


INSERT INTO learning_sessions
(id, teacher_id, title, description, start_time, duration_minutes, fee_type, base_fee, max_students, status, created_at, updated_at)
VALUES
(1, 2, 'Laravel Masterclass for Beginners',
'Learn Laravel 11 from scratch.',
DATE_ADD(NOW(), INTERVAL 2 DAY), 120, 'shared', 5000, 50, 'approved', NOW(), NOW()),

(2, 3, 'Calculus Integration Session',
'Solving past paper questions.',
DATE_ADD(NOW(), INTERVAL 1 DAY), 90, 'free', 0, 100, 'approved', NOW(), NOW());




INSERT INTO enrollments (session_id, student_id, paid_amount, status, created_at, updated_at) VALUES
(1, 5, 1000, 'paid', NOW(), NOW()),
(1, 6, 1000, 'paid', NOW(), NOW()),
(2, 5, 0, 'paid', NOW(), NOW());


INSERT INTO points (user_id, points, type, subject, created_at) VALUES
(5, 5, 'question_posted', 'Laravel', NOW()),
(5, 10, 'answer_verified', 'Laravel', NOW()),
(6, 5, 'question_posted', 'Maths', NOW()),
(6, 15, 'quiz_completed', 'Maths', NOW()),
(5, 10, 'answer_verified', 'Laravel', NOW());


DESCRIBE users;
INSERT INTO points (user_id, points, type, subject, created_at) VALUES
(5, 5, 'question_posted', 'Laravel', NOW()),
(5, 10, 'answer_verified', 'Laravel', NOW()),
(6, 5, 'question_posted', 'Maths', NOW()),
(6, 15, 'quiz_completed', 'Maths', NOW()),
(5, 10, 'answer_verified', 'Laravel', NOW());

describe points;
INSERT INTO points (user_id, subject, points, reason, created_at, updated_at) VALUES
(5, 'Laravel', 5, 'Posted a discussion question', NOW(), NOW()),
(5, 'Laravel', 10, 'Answer verified by advisor', NOW(), NOW()),
(5, 'Laravel', 5, 'Quiz completed', NOW(), NOW());

INSERT INTO points (user_id, subject, points, reason, created_at, updated_at) VALUES
(6, 'Maths', 5, 'Posted a discussion question', NOW(), NOW()),
(6, 'Maths', 15, 'Quiz completed', NOW(), NOW()),
(6, 'Maths', 10, 'Answer verified by advisor', NOW(), NOW());


INSERT INTO points (user_id, subject, points, reason, created_at, updated_at) VALUES
(5, 'Laravel', 20, 'Old verified answer', DATE_SUB(NOW(), INTERVAL 1 MONTH), NOW()),
(6, 'Maths', 10, 'Old quiz completion', DATE_SUB(NOW(), INTERVAL 1 MONTH), NOW());


SELECT 
    users.name,
    SUM(points.points) AS total_points
FROM points
JOIN users ON users.id = points.user_id
GROUP BY users.id
ORDER BY total_points DESC;

USE educhat_db1;

-- 1. Create a table to track every XP transaction
CREATE TABLE IF NOT EXISTS `points_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `points` int(11) NOT NULL,
  `category` varchar(100) DEFAULT 'General',
  `activity_type` varchar(50) DEFAULT 'quiz', -- quiz, forum, bonus
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Insert some dummy XP data for testing
INSERT INTO `points_logs` (`user_id`, `points`, `category`, `activity_type`, `created_at`, `updated_at`) VALUES
(5, 50, 'Information Technology', 'quiz', NOW(), NOW()),
(5, 20, 'Mathematics', 'quiz', NOW(), NOW()),
(6, 100, 'Information Technology', 'quiz', NOW(), NOW()),
(6, 10, 'General', 'forum', NOW(), NOW()),
(5, 30, 'Information Technology', 'quiz', DATE_SUB(NOW(), INTERVAL 10 DAY), NOW()); -- For monthly test

CREATE TABLE quiz_results (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    quiz_id BIGINT UNSIGNED NOT NULL,
    score INT NOT NULL,
    total_questions INT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_quiz_student
        FOREIGN KEY (student_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_quiz_result
        FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
        ON DELETE CASCADE
);

