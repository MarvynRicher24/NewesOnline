<?php
$pageTitle = 'Connection/Register';
include __DIR__ . '/../partials/header.php';
?>

<!-- Flash pop-up for "registration success" -->
<?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="popup-success" id="flash-popup-auth"><?= htmlspecialchars($_SESSION['flash_message']) ?></div>
    <script>
        setTimeout(function() {
            const popup = document.getElementById('flash-popup-auth');
            if (popup) {
                popup.style.opacity = '0';
                setTimeout(() => popup.remove(), 500);
            }
        }, 2000);
    </script>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<div class="auth-container">
    <div class="class auth-button">
        <a href="index.php?controller=auth&action=connection" class="button <?= (isset($_GET['action']) && $_GET['action'] === 'connection') || !isset($_GET['action']) ? 'active' : '' ?>">Login</a>
        <a href="index.php?controller=auth&action=register" class="button <?= (isset($_GET['action']) && $_GET['action'] === 'register') ? 'active' : '' ?>">Register</a>
    </div>
</div>

<?php if (isset($_GET['action']) && $_GET['action'] === 'register'): ?>
    <!-- Register form -->
    <div class="form-container">
        <h2>Register</h2>
        <form action="index.php?controller=auth&action=register" method="post" enctype="multipart/form-data">
            <!-- CSRF token -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <label>Username :</label>
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
                            <input type="radio" name="avatar" value="<?= htmlspecialchars($avatarFile) ?>" <?= (isset($_POST['avatar']) && $_POST['avatar'] === $avatarFile) ? 'checked' : '' ?> required>
                            <img src="public/uploads/avatar/<?= htmlspecialchars($avatarFile) ?>" alt="Avatar choice" class="avatar-choice">
                        </label>

                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No avatars available.</p>
                <?php endif; ?>
            </div>

            <label>Description :</label>
            <textarea name="description"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>

            <button type="submit" name="register" class="button">Inscription</button>

            <?php if (!empty($register_error)): ?>
                <p class="error"><?= htmlspecialchars($register_error) ?></p>
            <?php endif; ?>
        </form>
    </div>

<?php else: ?>

    <!-- Connection -->
    <div class="form-container">
        <h2>Login</h2>
        <form action="index.php?controller=auth&action=connection" method="post">

            <!-- CSRF token -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <label>Username :</label>
            <input type="text" name="usernameOrEmail" required value="<?= isset($_POST['usernameOrEmail']) ? htmlspecialchars($_POST["usernameOrEmail"]) : '' ?>">

            <label>Password :</label>
            <input type="password" name="password" required>

            <button class="button" name="connection" type="submit">Connection</button>

            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </form>
    </div>

<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>