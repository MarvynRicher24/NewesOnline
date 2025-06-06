<?php
require_once __DIR__ . '/../models/Subscriber.php';

class SubscriberController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Show profile
    public function profile()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'subscriber') {
            header('Location: index.php?controller=auth&action=connexion');
            exit;
        }

        $subscriberModel = new Subscriber($this->pdo);
        $subscriber = $subscriberModel->findById($_SESSION['user_id']);
        require __DIR__ . '/../views/subscriber/profile.php';
    }

    // Edit profile
    public function edit()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'subscriber') {
            header('Location: index.php?controller=auth&action=connexion');
            exit;
        }

        $subscriberModel = new Subscriber($this->pdo);
        $subscriber = $subscriberModel->findById($_SESSION['user_id']);

        // Recover avatar list available
        $avatarDir = __DIR__ . '/../public/uploads/avatar/';
        $avatars = [];
        if (is_dir($avatarDir)) {
            foreach (scandir($avatarDir) as $file) {
                if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                    if ($file !== '.' && $file !== '..') {
                        $avatars[] = $file;
                    }
                }
            }
        }
        require __DIR__ . '/../views/subscriber/edit.php';
    }

    public function update()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'subscriber') {
            header('Location: index.php?controller=auth&action=connexion');
            exit;
        }

        // CSRF check
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                empty($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                // Token missing or invalid: reject the request
                die('CSRF validation failed.');
            }
        }

        $subscriberModel = new Subscriber($this->pdo);
        $validator       = $subscriberModel->findById($_SESSION['user_id']);
        if (!$validator) {
            header('Location: index.php?controller=auth&action=connexion');
            exit;
        }

        $username        = trim($_POST['username']);
        $email           = trim($_POST['email']);
        $description     = trim($_POST['description'] ?? '');
        $avatar          = $_POST['avatar'] ?? null;
        $newPasswordHash = null;

        if (!empty($_POST['password'])) {
            $newPasswordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        // Minimal validation
        if (empty($username) || empty($email) || empty($avatar)) {
            $_SESSION['flash_message'] = "Please fill in all required fields";
            header('Location: index.php?controller=profile&action=edit');
            exit;
        }
        // Update
        $subscriberModel->updateProfile(
            $_SESSION['user_id'],
            $username,
            $email,
            $newPasswordHash,
            $avatar,
            $description
        );
        $_SESSION['flash_message'] = "Profile updated";
        header('Location: index.php?controller=profile&action=profile');
        exit;
    }
}
