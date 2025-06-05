<?php
$pageTitle = 'Connexion';
include __DIR__ . '/../partials/header.php';
?>

<div class="auth-container">
    <div class="class auth-button">
        <a href="index.php?controller=auth&action=connexion" class="button <?= (isset($_GET['action']) && $_GET['action'] === 'connexion') || !isset($_GET['action']) ? 'active' : '' ?>">Connexion</a>
        <a href="index.php?controller=auth&action=register" class="button <?= (isset($_GET['action']) && $_GET['action'] === 'register') ? 'active' : '' ?>">Register</a>
    </div>
</div>

<?php if (isset($_GET['action']) && $_GET['action'] === 'register'): ?>
    <!-- Inscription form -->
    <div class="form-container">
        <h2>Inscription</h2>
        <form action="index.php?controller=auth&action=register" method="post" enctype="multipart/form-data">

            <label>Pseudo :</label>
            <input name="username" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST["username"]) : '' ?>">


            <label>Email :</label>
                <input type="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST["email"]) : '' ?>">

            <label>Password :</label>
                <input type="password" name="password" required>

            <label>Choose your Avatar :</label>
                <div class="avatar-selection">
                    <?php if (!empty($avatars)): ?>
                        <?php foreach ($avatars as $avatarFile): ?>

                            <label class="avatar-option">
                                <input type="radio" name="avatar" value="<?= htmlspecialchars($avatar) ?>" required>
                                <img src="public/uploads/avatar/<?= htmlspecialchars($avatar) ?>" alt="Avatar" style="width: 60px; height: 60px; border-radius: 50%; border: 2px solid #ccc;">
                            

                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No avatars available.</p>
                    <?php endif; ?>
                </div>

                <label>Description:<textarea name="description"></textarea></label>
                <button type="submit">Inscription</button>

                <?php if (!empty($register_error)): ?>
                    <p class="error"><?= htmlspecialchars($register_error) ?></p>
                <?php endif; ?>
        </form>
    </div>

<?php else: ?>

    <!-- Connexion -->
    <div id="login-form">
        <h2 style="margin-top: 10px;">Login</h2>
        <form action="index.php?controller=auth&action=connexion" method="post">

            <label>Username:
                <input name="username" required></label>

            <label>Password:
                <input type="password" name="password" required></label>

            <button class="button" name="connexion" type="submit">Connexion</button>

            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </form>
    </div>

<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>