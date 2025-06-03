<?php
if (session_status() === PHP_SESSION_NONE) session_start();
// $subscriber doit être passé à la vue par le contrôleur si connecté
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>NewesOnline</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="public/css/style.css">
<header>
    <div class="header-inner">
        <nav class="main-nav">
            <ul style="display: flex; gap: 20px; list-style: none; margin: 0; padding: 0;">
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php?controller=about">About</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="index.php?controller=admin">Admin Panel</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="auth-link" style="display: flex; align-items: center; margin-left: auto;">
            <?php if (isset($_SESSION['role'], $subscriber) && $_SESSION['role'] === 'subscriber'): ?>
                <a href="index.php?controller=profile&action=profile" style="display: flex; align-items: center; text-decoration: none; margin-right: 1rem;">
                    <?php if (!empty($subscriber['avatar'])): ?>
                        <img src="public/uploads/avatar/<?= htmlspecialchars($subscriber['avatar']) ?>" alt="Avatar" class="avatar" style="width:32px;height:32px;border-radius:50%;margin-right:8px;">
                    <?php endif; ?>
                    <span style="color: #2c3e50; font-weight: bold;"><?= htmlspecialchars($subscriber['username']) ?></span>
                </a>
            <?php endif; ?>
            <?php if (isset($_SESSION['role'])): ?>
                <a href="index.php?controller=auth&action=logout" class="button">Logout</a>
            <?php else: ?>
                <a href="index.php?controller=auth&action=connexion" class="button">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<main>