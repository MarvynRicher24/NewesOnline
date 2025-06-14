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
    }

    // Connection
    public function connection()
    {
        $error = '';

        // Only validate CSRF if this is a POST submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                empty($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                // Token missing or invalid: reject the request
                die('CSRF validation failed.');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connection'])) {
            $usernameOrEmail = $_POST['usernameOrEmail'];
            $password        = $_POST['password'];

            // Verify if it's an admin
            $adminModel = new AdminUser($this->pdo);
            $admin      = $adminModel->findByUsername($usernameOrEmail);
            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['role']    = 'admin';
                $_SESSION['user_id'] = $admin['id'];
                header('Location: index.php?controller=admin&action=index');
                exit;
            }

            // if else, verify if it's a subscriber
            $subscriberModel = new Subscriber($this->pdo);
            $user            = $subscriberModel->findByUsername($usernameOrEmail);
            if (!$user) {
                $user = $subscriberModel->findByEmail($usernameOrEmail);
            }
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['role']    = 'subscriber';
                $_SESSION['user_id'] = $user['id'];
                header('Location: index.php?controller=profile&action=profile');
                exit;
            }

            $error = "Incorrect login";
        }
        require __DIR__ . '/../views/auth/connection.php';
    }

    // Register
    public function register()
    {
        $register_error = '';
        // Avatar selection
        $avatarDir = realpath(__DIR__ . '/../public/uploads/avatar/');
        $avatars = [];
        if ($avatarDir && is_dir($avatarDir)) {
            foreach (scandir($avatarDir) as $file) {
                if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                    if ($file !== '.' && $file !== '..') {
                        $avatars[] = $file;
                    }
                }
            }
        }

        // CSRF validation
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                empty($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                // Token missing or invalid: reject the request
                die('CSRF validation failed.');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
            $username    = $_POST['username'];
            $email       = $_POST['email'];
            $rawPassword = $_POST['password'];
            $avatar      = $_POST['avatar']; // Select the name of the file
            $description = $_POST['description'] ?? '';

            // Minimal validation
            if (empty($username) || empty($email) || empty($rawPassword) || empty($avatar)) {
                $register_error = "All fields are mandatory.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $register_error = "The email is invalid";
            } else {
                $subscriberModel = new Subscriber($this->pdo);

                // Username and email verification
                if ($subscriberModel->findByUsername($username)) {
                    $register_error = "This username already exists";
                } elseif ($subscriberModel->findByEmail($email)) {
                    $register_error = "This email already exists";
                } else {
                    $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT); // Hash the password

                    //Subscriber creation
                    $ok = $subscriberModel->create($username, $email, $hashedPassword, $avatar, $description);
                    if ($ok) {
                        // Flash message
                        $_SESSION['flash_message'] = 'Registration successful, you can now log in';
                        header('Location: index.php?controller=auth&action=connection');
                        exit;
                    } else {
                        $register_error = "An error occurred during account creation";
                    }
                }
            }
        }
        require __DIR__ . '/../views/auth/connection.php';
    }

    // Disconnection
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
