<?php

session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=dwwm;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Db connexion error : ' . $e->getMessage());
}

$subscriber = null;
if (isset($_SESSION['role']) && $_SESSION['role'] === 'subscriber' && isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/models/Subscriber.php';
    $subscriberModelInit = new Subscriber($pdo);
    $subscriber = $subscriberModelInit->findById($_SESSION['user_id']);
}

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

require_once __DIR__ . '/models/Subscriber.php';
require_once __DIR__ . '/models/AdminUser.php';

switch ($controller) {
    case 'auth':
        require_once __DIR__ . '/controllers/AuthController.php';
        $ctrl = new AuthController($pdo);
        if (method_exists($ctrl, $action)) {
            $ctrl->$action();
        } else {
            echo "Action not found";
        }
        break;

    case 'profile':
        require_once __DIR__ . '/controllers/SubscriberController.php';
        $ctrl = new SubscriberController($pdo);
        if (method_exists($ctrl, $action)) {
            $ctrl->$action();
        } else {
            echo "Action not found";
        }
        break;

    case 'admin':
        require_once __DIR__ . '/controllers/AdminController.php';
        $ctrl = new AdminController($pdo);
        if (method_exists($ctrl, $action)) {
            $ctrl->$action();
        } else {
            $ctrl->index();
        }
        break;

    case 'about':
        require_once __DIR__ . '/controllers/AboutController.php';
        $ctrl = new AboutController();
        $ctrl->index();
        break;

    case 'announcement':
        require_once __DIR__ . '/controllers/AnnouncementController.php';
        $ctrl = new AnnouncementController($pdo);
        if ($action === 'show') {
            $ctrl->show();
        }
        break;

    default:
        require_once __DIR__ . '/controllers/HomeController.php';
        $ctrl = new HomeController($pdo);
        $ctrl->index();
        break;
}
