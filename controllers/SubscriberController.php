<?php
class SubscriberController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        require_once __DIR__ . '/../models/Subscriber.php';
    }

    // Show profile
    public function profile()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
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
        session_start();
        if (!isset($_SESSION['user_id'])) {
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
                if ($file !== '.' && $file !== '..' && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                    $avatars[] = $file;
                }
            }
        }
        require __DIR__ . '/../views/subscriber/edit.php';
    }

    public function update()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=connexion');
            exit;
        }
        $subscriber = new Subscriber($this->pdo);
        $user = $subscriber->findById($_SESSION['user_id']);
        $username = $_POST['username'];
        $email = $_POST['email'];
        $description = $_POST['description'];
        $passwordHash = null;
        if (!empty($_POST['password'])) {
            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        $avatar = $_POST['avatar']; // Choose the file name
        $subscriber->updateProfile($user['id'], $username, $email, $passwordHash, $avatar, $description);
        // Correction : rediriger vers la bonne page
        header('Location: index.php?controller=profile&action=edit');
        exit;
    }
}
