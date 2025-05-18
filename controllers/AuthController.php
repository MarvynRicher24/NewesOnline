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

    public function register()
    {
        $register_error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $description = $_POST['description'] ?? '';
            $avatar = null;
            if (!empty($_FILES['avatar']['name'])) {
                $avatar = basename($_FILES['avatar']['name']);
                move_uploaded_file($_FILES['avatar']['tmp_name'], 'public/uploads/' . $avatar);
            }
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

    public function connexion()
    {
        session_start();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Vérifier d'abord si c'est un admin
            $adminModel = new AdminUser($this->pdo);
            $admin = $adminModel->findByUsername($username);


            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin'] = true;
                $_SESSION['user_id'] = $admin['id'];
                // Correction : définir le rôle
                $_SESSION['role'] = 'admin';
                header('Location: index.php?controller=admin&action=index');
                exit;
            }

            // Sinon, vérifier si c'est un subscriber
            $subscriberModel = new Subscriber($this->pdo);
            $user = $subscriberModel->findByUsername($username);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin'] = false;
                $_SESSION['user_id'] = $user['id'];
                // Correction : définir le rôle
                $_SESSION['role'] = 'subscriber';
                header('Location: index.php?controller=profile&action=edit');
                exit;
            }

            $error = "Identifiants incorrects.";
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
