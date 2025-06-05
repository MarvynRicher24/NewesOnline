<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'NewesOnline' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>

    <header>
        <div class="header-inner">
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php" <?= (!isset($_GET['controller']) || $_GET['controller'] === 'home') ? 'class="active"' : '' ?>>Home</a></li>
                    <li><a href="index.php?controller=about" <?= (isset($_GET['controller']) && $_GET['controller'] === 'about') ? 'class="active"' : '' ?>>About</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li><a href="index.php?controller=admin" <?= (isset($_GET['controller']) && $_GET['controller'] === 'admin') ? 'class="active"' : '' ?>>Admin Panel</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="auth-link">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'subscriber' && !empty($subscriber)): ?>
                    <a href="index.php?controller=profile&action=profile" class="profile-link">
                        <?php if (!empty($subscriber['avatar'])): ?>
                            <img src="public/uploads/avatar/<?= htmlspecialchars($subscriber['avatar']) ?>" alt="Avatar <?= htmlspecialchars($subscriber['username']) ?>" class="avatar">
                        <?php endif; ?>
                        <span><?= htmlspecialchars($subscriber['username']) ?></span>
                    </a>
                <?php endif; ?>

                <?php if (isset($_SESSION['role'])): ?>
                    <a href="index.php?controller=auth&action=logout" class="button">Logout</a>
                <?php else: ?>
                    <a href="index.php?controller=auth&action=connexion" class="button <?= (isset($_GET['action']) && $_GET['action'] === 'connexion') ? 'active' : '' ?>">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main>