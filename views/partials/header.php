    <?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>NewesOnline</title>
        <link rel="stylesheet" href="public/css/style.css">
    </head>

    <body>
        <header>
            <div class="container header-inner">
                <div class="branding"><a href="index.php"><img src="./public/uploads/mustang-logo.png" class="logo"> <span class="site-title">NewesOnline</span></a></div>
                <nav class="main-nav">
                    <a href="index.php">Home</a>
                    <a href="index.php?controller=about">About</a>
                    <!-- navigation to the admin panel -->
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="index.php?controller=admin&action=index">Admin Panel</a>
                    <!-- Navigation to the profile panel -->
                    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'subscriber'): ?>
                        <a href="index.php?controller=profile&action=edit">My Profile</a>
                    <?php endif; ?>
                </nav>
                <div class="auth-link">
                    <?php if (isset($_SESSION['role'])): ?>
                        <a href="index.php?controller=auth&action=logout">Déconnexion</a>
                    <?php else: ?>
                        <a href="index.php?controller=auth&action=connexion">Connexion</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>
        <main class="container">