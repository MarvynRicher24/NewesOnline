<?php
$dsn = 'mysql:host=localhost;dbname=dwwm;charset=utf8mb4';
$user = 'root'; // adapte selon ta config
$pass = '';     // idem

try {
    $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS dwwm CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $pdo->exec("USE dwwm");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS subscriber (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            avatar VARCHAR(255),
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS category (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS announcements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            subtitle VARCHAR(255),
            content TEXT NOT NULL,
            image VARCHAR(255),
            category_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_ann_category FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE SET NULL
        );
    ");

    $stmt = $pdo->prepare("INSERT IGNORE INTO admin_users (username, password) VALUES (?, ?)");
    $stmt->execute(['marvyn', password_hash('marvyn', PASSWORD_DEFAULT)]);

    $categories = ['Vehicle', 'Video game', 'History', 'Science', 'Finance'];
    $stmt = $pdo->prepare("INSERT IGNORE INTO category (name) VALUES (?)");
    foreach ($categories as $cat) {
        $stmt->execute([$cat]);
    }

    echo "Database setup complete.\n";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
