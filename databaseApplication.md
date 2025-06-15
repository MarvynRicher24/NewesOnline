-- 1) Create database if it doesn’t exist, then use it
CREATE DATABASE IF NOT EXISTS dwwm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dwwm;

-- 2) Table for admin users
CREATE TABLE IF NOT EXISTS admin_users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3) Table for subscribers
CREATE TABLE IF NOT EXISTS subscriber (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50) NOT NULL UNIQUE,
    email       VARCHAR(255) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    avatar      VARCHAR(255),
    description TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4) Table for category
CREATE TABLE IF NOT EXISTS category (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5) Table for announcements
CREATE TABLE IF NOT EXISTS announcements (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(255) NOT NULL,
    subtitle     VARCHAR(255),
    content      TEXT NOT NULL,
    image        VARCHAR(255),
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    category_id  INT NULL,
    CONSTRAINT fk_ann_category
        FOREIGN KEY (category_id) REFERENCES category(id)
        ON DELETE SET NULL
);

-- 6) Table for commments / notes
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subscriber_id INT NOT NULL,
    announcement_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_sub_ann (subscriber_id, announcement_id),
    CONSTRAINT fk_comment_sub
        FOREIGN KEY (subscriber_id) REFERENCES subscriber(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_comment_ann
        FOREIGN KEY (announcement_id) REFERENCES announcements(id)
        ON DELETE CASCADE
);

-- Insert default categories
INSERT INTO category (name) VALUES
    ('Vehicle'),
    ('Video game'),
    ('History'),
    ('Science'),
    ('Finance');

-- Insert one admin user (password to be hashed in a PHP script)
   You will run the separate PHP script to replace the plaintext password with a hash.
INSERT INTO admin_users (username, password)
VALUES ('marvyn', 'marvyn');

-- Note: after importing this SQL, run the following PHP snippet to hash the admin password:
 <?php
 // scripts/hash_admin.php
 require __DIR__ . '/../config/database.php'; // adjust path if needed
--
 $username = 'marvyn';
 $plain    = 'marvyn';
 $hash     = password_hash($plain, PASSWORD_DEFAULT);
 $stmt     = $pdo->prepare("UPDATE admin_users SET password = :hash WHERE username = :user");
 $stmt->execute([
     'hash' => $hash,
     'user' => $username
 ]);
 echo "Password for '{$username}' has been hashed and updated.\n";

--

 // Then run: php scripts/hash_admin.php