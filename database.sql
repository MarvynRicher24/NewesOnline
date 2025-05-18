/* -- Create database (if not exists) and use it
CREATE DATABASE dwwm;

-- Table for admin users
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subscriber (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for announcements
CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255),
    content TEXT NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admin_users (username, password)
VALUES ('marvyn', 'marvyn');

-- Table for category
CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `category`(`id`) VALUES ('Vehicle'), ('Video game'), ('History'), ('Science'), ('Finance');

LINK BETWEEN ANNOUNCEMENTS AND CATEGORY

ALTER TABLE announcements
    ADD COLUMN category_id INT NULL,
    ADD CONSTRAINT fk_ann_category
    FOREIGN KEY (category_id) REFERENCES category(id)
    ON DELETE SET NULL;


SCRIPT TO HASH THE PASSWORD

<?php
// scripts/hash_admin.php
require __DIR__ . '/../config/database.php';  // adjust path as needed

$username = 'marvyn';
$plain  = 'marvyn';
$hash   = password_hash($plain, PASSWORD_DEFAULT);

// Update the DB
$stmt = $pdo->prepare("UPDATE admin_users SET password = :hash WHERE username = :user");
$stmt->execute([
    'hash' => $hash,
    'user' => $username
]);

echo "Password for '{$username}' has been hashed and updated.\n";

RUN THIS COMMAND LINE TO RUN

php scripts/hash_admin.php

*/