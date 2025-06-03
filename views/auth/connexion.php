<?php include __DIR__ . '/../partials/header.php'; ?>

<div>
    <a href="index.php?controller=auth&action=connexion" class="button">Connexion</a>
    <a href="index.php?controller=auth&action=register" class="button">Register</a>
</div>

<?php if (isset($_GET['action']) && $_GET['action'] === 'register'): ?>
    <!-- Inscription -->
    <div id="register-form">
        <h2 style="margin-top: 10px;">Inscription</h2>
        <form action="index.php?controller=auth&action=register" method="post" enctype="multipart/form-data">

            <label>Pseudo:
                <input name="username" required></label>

            <label>Email:
                <input type="email" name="email" required></label>

            <label>Password:
                <input type="password" name="password" required></label>

            <label>Choose your Avatar:
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <?php if (!empty($avatars)): ?>
                        <?php foreach ($avatars as $avatar): ?>

                            <label for="display: flex; flex-direction: column; align-items: center;">
                                <input type="radio" name="avatar" value="<?= htmlspecialchars($avatar) ?>" required>
                                <img src="public/uploads/avatar/<?= htmlspecialchars($avatar) ?>" alt="Avatar" style="width: 60px; height: 60px; border-radius: 50%; border: 2px solid #ccc;">
                            </label>

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