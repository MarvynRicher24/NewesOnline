<?php
// tests/BaseTestCase.php
namespace Tests;

use PHPUnit\Framework\TestCase;
use PDO;

class BaseTestCase extends TestCase
{
    /** @var PDO */
    protected $pdo;

    protected function setUp(): void
    {
        // 1. Connect to in-memory SQLite
        $dsn = getenv('DB_DSN');
        $this->pdo = new PDO($dsn);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 2. Enable FKs
        $this->pdo->exec('PRAGMA foreign_keys = ON');

        // 3. Create SQLite‑compatible schema
        $schema = <<<'SQL'
        -- Admin users
        CREATE TABLE admin_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        -- Subscribers
        CREATE TABLE subscriber (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            avatar TEXT,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        -- Categories
        CREATE TABLE category (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        -- Announcements
        CREATE TABLE announcements (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            subtitle TEXT,
            content TEXT NOT NULL,
            image TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            category_id INTEGER,
            FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE SET NULL
        );

        -- Comments
        CREATE TABLE comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            subscriber_id INTEGER NOT NULL,
            announcement_id INTEGER NOT NULL,
            rating INTEGER NOT NULL CHECK (rating BETWEEN 1 AND 5),
            comment TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (subscriber_id) REFERENCES subscriber(id) ON DELETE CASCADE,
            FOREIGN KEY (announcement_id) REFERENCES announcements(id) ON DELETE CASCADE,
            UNIQUE (subscriber_id, announcement_id)
        );

        -- Seed categories
        INSERT INTO category (name) VALUES
            ('Vehicle'),
            ('Video game'),
            ('History'),
            ('Science'),
            ('Finance');

        -- Seed admin user (password will be overwritten below)
        INSERT INTO admin_users (username, password) VALUES ('marvyn', '');

        SQL;

        $this->pdo->exec($schema);

        // 4. Hash default admin password for authentication tests
        $stmt = $this->pdo->prepare(
            "UPDATE admin_users SET password = :hash WHERE username = 'marvyn'"
        );
        $stmt->execute(['hash' => password_hash('marvyn', PASSWORD_DEFAULT)]);
    }
}
