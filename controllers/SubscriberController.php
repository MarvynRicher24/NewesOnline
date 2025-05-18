<?php
class SubscriberController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function edit()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=connexion');
            exit;
        }
        $subscriber = new Subscriber($this->pdo);
        $user = $subscriber->findById($_SESSION['user_id']);
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
        $avatar = $user['avatar'];
        if (!empty($_FILES['avatar']['name'])) {
            $avatar = basename($_FILES['avatar']['name']);
            move_uploaded_file($_FILES['avatar']['tmp_name'], 'public/uploads/' . $avatar);
        }
        $subscriber->updateProfile($user['id'], $username, $email, $passwordHash, $avatar, $description);
        // Correction : rediriger vers la bonne page
        header('Location: index.php?controller=profile&action=edit');
        exit;
    }
}
