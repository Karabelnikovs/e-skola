CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) DEFAULT 'learner',
    language VARCHAR(5) DEFAULT 'lv',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE courses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    `order` INT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE sections (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    `order` INT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE topics (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    section_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    `order` INT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE
);

CREATE TABLE tests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id INT UNSIGNED NOT NULL,
    section_id INT UNSIGNED NULL,
    title VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL,
    passing_score INT NOT NULL,
    `order` INT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE
);

CREATE TABLE questions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    test_id INT UNSIGNED NOT NULL,
    question_text TEXT NOT NULL,
    options JSON,
    correct_answer VARCHAR(255) NOT NULL,
    `order` INT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE
);

CREATE TABLE attempts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    test_id INT UNSIGNED NOT NULL,
    attempt_number INT NOT NULL,
    score INT NOT NULL,
    passed TINYINT(1) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE
);

CREATE TABLE answers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    attempt_id INT UNSIGNED NOT NULL,
    question_id INT UNSIGNED NOT NULL,
    answer_given VARCHAR(255) NOT NULL,
    is_correct TINYINT(1) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (attempt_id) REFERENCES attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

CREATE TABLE certificates (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    course_id INT UNSIGNED NOT NULL,
    issued_at TIMESTAMP NOT NULL,
    certificate_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE user_topic_completions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    topic_id INT UNSIGNED NOT NULL,
    completed_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
);

CREATE TABLE `sessions` (
	`id` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`user_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	`ip_address` VARCHAR(45) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`user_agent` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`payload` LONGTEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`last_activity` INT(10) NOT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `sessions_user_id_index` (`user_id`) USING BTREE,
	INDEX `sessions_last_activity_index` (`last_activity`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;

ALTER TABLE courses
ADD COLUMN img VARCHAR(255);

ALTER TABLE `courses`
ADD COLUMN `title_lv` VARCHAR(255) AFTER `title`,
ADD COLUMN `title_en` VARCHAR(255) AFTER `title_lv`,
ADD COLUMN `title_ru` VARCHAR(255) AFTER `title_en`,
ADD COLUMN `title_uk` VARCHAR(255) AFTER `title_ru`,

ADD COLUMN `description_lv` TEXT AFTER `description`,
ADD COLUMN `description_en` TEXT AFTER `description_lv`,
ADD COLUMN `description_ru` TEXT AFTER `description_en`,
ADD COLUMN `description_uk` TEXT AFTER `description_ru`;

ALTER TABLE `courses`
DROP COLUMN description,
DROP COLUMN title;

ALTER TABLE topics
ADD COLUMN `course_id` BIGINT(20) UNSIGNED NOT NULL;

ALTER TABLE `topics`
ADD COLUMN `title_lv` VARCHAR(255) AFTER `title`,
ADD COLUMN `title_en` VARCHAR(255) AFTER `title_lv`,
ADD COLUMN `title_ru` VARCHAR(255) AFTER `title_en`,
ADD COLUMN `title_uk` VARCHAR(255) AFTER `title_ru`,

ADD COLUMN `content_lv` TEXT AFTER `content`,
ADD COLUMN `content_en` TEXT AFTER `content_lv`,
ADD COLUMN `content_ru` TEXT AFTER `content_en`,
ADD COLUMN `content_uk` TEXT AFTER `content_ru`;

ALTER TABLE `topics`
DROP COLUMN content,
DROP COLUMN title;

ALTER TABLE `topics` MODIFY `section_id` BIGINT(20) UNSIGNED NULL;
ALTER TABLE `tests` MODIFY `section_id` BIGINT(20) UNSIGNED NULL;

ALTER TABLE `questions`
ADD COLUMN `question_lv` VARCHAR(255) AFTER `question_text`,
ADD COLUMN `question_en` VARCHAR(255) AFTER `question_lv`,
ADD COLUMN `question_ru` VARCHAR(255) AFTER `question_en`,
ADD COLUMN `question_uk` VARCHAR(255) AFTER `question_ru`,

ADD COLUMN `options_lv` VARCHAR(255) AFTER `options`,
ADD COLUMN `options_en` VARCHAR(255) AFTER `options_lv`,
ADD COLUMN `options_ru` VARCHAR(255) AFTER `options_en`,
ADD COLUMN `options_ua` VARCHAR(255) AFTER `options_ru`
;
ALTER TABLE `questions`
DROP COLUMN question_text,
DROP COLUMN options;

ALTER TABLE `tests`
ADD COLUMN `title_lv` VARCHAR(255) AFTER `title`,
ADD COLUMN `title_en` VARCHAR(255) AFTER `title_lv`,
ADD COLUMN `title_ru` VARCHAR(255) AFTER `title_en`,
ADD COLUMN `title_ua` VARCHAR(255) AFTER `title_ru`;

ALTER TABLE `tests`
DROP COLUMN title;

CREATE TABLE dictionaries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `course_id` BIGINT(20) UNSIGNED NOT NULL,
    title_lv VARCHAR(255),
    title_en VARCHAR(255),
    title_ru VARCHAR(255),
    title_ua VARCHAR(255),
    `order` INT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);
CREATE TABLE translations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dictionary_id INT UNSIGNED NOT NULL,
    phrase_en VARCHAR(255),
	 phrase_lv VARCHAR(255),
	 phrase_ua VARCHAR(255),
	 phrase_ru VARCHAR(255),
    `order` INT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (dictionary_id) REFERENCES dictionaries(id) ON DELETE CASCADE
);

ALTER TABLE `topics`
CHANGE COLUMN `content_uk` `content_ua` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
CHANGE COLUMN `title_uk` `title_ua` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci';

ALTER TABLE `courses`
CHANGE COLUMN `title_uk` `title_ua` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
CHANGE COLUMN `description_uk` `description_ua` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci';

ALTER TABLE tests
DROP COLUMN TYPE;

ALTER TABLE tests
ADD COLUMN type VARCHAR(255) NOT NULL;


ALTER TABLE certificates
ADD COLUMN is_read INT NOT NULL;


ALTER TABLE `questions`
MODIFY COLUMN `options_lv` TEXT COLLATE 'utf8mb4_unicode_ci',
MODIFY COLUMN `options_en` TEXT COLLATE 'utf8mb4_unicode_ci',
MODIFY COLUMN `options_ru` TEXT COLLATE 'utf8mb4_unicode_ci',
MODIFY COLUMN `options_ua` TEXT COLLATE 'utf8mb4_unicode_ci';
