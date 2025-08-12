-- Sample MySQL bootstrap for WazaElimu
-- Creates DB (if not exists), selects it, and inserts sample rows

-- Adjust database name if you prefer a different one
CREATE DATABASE IF NOT EXISTS `wazaelimu` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `wazaelimu`;

SET FOREIGN_KEY_CHECKS=0;

-- Users (admin user with password: "password")
INSERT INTO `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`)
VALUES
('Admin', 'admin@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW())
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

-- Regions (optional if your app uses them)
INSERT INTO `regions` (`name`, `created_at`, `updated_at`) VALUES
('Dar es Salaam', NOW(), NOW()),
('Arusha', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Categories
INSERT INTO `categories` (`name`, `icon`, `created_at`, `updated_at`) VALUES
('Books', NULL, NOW(), NOW()),
('Past Papers', NULL, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Subcategories (with year)
INSERT INTO `subcategories` (`name`, `category_id`, `year`, `created_at`, `updated_at`) VALUES
('Mathematics', (SELECT id FROM `categories` WHERE `name`='Books' LIMIT 1), 2024, NOW(), NOW()),
('Physics', (SELECT id FROM `categories` WHERE `name`='Books' LIMIT 1), 2025, NOW(), NOW()),
('Form Four Exams', (SELECT id FROM `categories` WHERE `name`='Past Papers' LIMIT 1), 2023, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Subjects
INSERT INTO `subjects` (`name`, `created_at`, `updated_at`) VALUES
('Mathematics', NOW(), NOW()),
('Physics', NOW(), NOW()),
('Chemistry', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Classes (school_classes)
INSERT INTO `school_classes` (`name`, `subject_id`, `description`, `created_at`, `updated_at`) VALUES
('Form One - A', (SELECT id FROM `subjects` WHERE `name`='Mathematics' LIMIT 1), 'Starter class', NOW(), NOW()),
('Form Two - B', (SELECT id FROM `subjects` WHERE `name`='Physics' LIMIT 1), 'Science stream', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Class-Subject pivot (additional assignments)
INSERT INTO `school_class_subject` (`school_class_id`, `subject_id`, `created_at`, `updated_at`) VALUES
((SELECT id FROM `school_classes` WHERE `name`='Form One - A' LIMIT 1), (SELECT id FROM `subjects` WHERE `name`='Chemistry' LIMIT 1), NOW(), NOW()),
((SELECT id FROM `school_classes` WHERE `name`='Form Two - B' LIMIT 1), (SELECT id FROM `subjects` WHERE `name`='Mathematics' LIMIT 1), NOW(), NOW());

-- Materials (slug + URL)
INSERT INTO `materials` (`title`, `slug`, `url`, `created_at`, `updated_at`) VALUES
('Algebra Basics PDF', 'algebra-basics-pdf', '/m/algebra-basics-pdf', NOW(), NOW()),
('Kinematics Notes', 'kinematics-notes', '/m/kinematics-notes', NOW(), NOW())
ON DUPLICATE KEY UPDATE `slug` = VALUES(`slug`);

SET FOREIGN_KEY_CHECKS=1;
