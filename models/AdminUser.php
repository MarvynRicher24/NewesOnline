<?php
class AdminUser {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch an admin user by username
    public function findByUsername($username) {
        $stmt = $this->pdo->prepare('SELECT * FROM admin_users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}