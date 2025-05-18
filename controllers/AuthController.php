<?php

require_once __DIR__ . '/../models/Subscriber.php';
require_once __DIR__ . '/../models/AdminUser.php';

// AuthController handles user authentication and registration
class AuthController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        require_once __DIR__ . '/../models/Subscriber.php';
    }

    // Connexion
    public function connexion()
    {
        session_start();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connexion'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Verify if it's an admin
            $adminModel = new AdminUser($this->pdo);
            $admin = $adminModel->findByUsername($username);

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin'] = true;
                $_SESSION['user_id'] = $admin['id'];
                // Define the role
                $_SESSION['role'] = 'admin';
                header('Location: index.php?controller=admin&action=index');
                exit;
            }

            // if else, verify if it's a subscriber
            $subscriberModel = new Subscriber($this->pdo);
            $user = $subscriberModel->findByUsername($username);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin'] = false;
                $_SESSION['user_id'] = $user['id'];
                // Define the role
                $_SESSION['role'] = 'subscriber';
                header('Location: index.php?controller=profile&action=edit');
                exit;
            }

            $error = "Identifiants incorrects.";
        }
        require __DIR__ . '/../views/auth/connexion.php';
    }

    // Inscription
    public function register()
    {
        // Recover avatar
        $avatarDir = realpath(__DIR__ . '/../public/uploads/avatar/');
        $avatars = [];
        if ($avatarDir && is_dir($avatarDir)) {
            foreach (scandir($avatarDir) as $file) {
                if ($file !== '.' && $file !== '..' && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                    $avatars[] = $file;
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
            $description = $_POST['description'] ?? '';
            $avatar = $_POST['avatar']; // Select the name of the file
            $subscriber = new Subscriber($this->pdo);
            if ($subscriber->findByUsername($username)) {
                $register_error = "Ce pseudo existe déjà.";
            } else {
                $subscriber->create($username, $email, password_hash($password, PASSWORD_DEFAULT), $avatar, $description);
                header('Location: index.php?controller=auth&action=connexion');
                exit;
            }
        }
        require __DIR__ . '/../views/auth/connexion.php';
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
