<?php include __DIR__ . '/../partials/header.php'; ?>

<div>
    <button class="button" style="border: none; margin-top: 10px; background: #888; color: #fff; text-decoration:none; padding:8px 18px; border-radius:5px;" onclick="showForm('login')">Connexion</button>
    <button class="button" style="border: none; margin-top: 10px; background: #888; color: #fff; text-decoration:none; padding:8px 18px; border-radius:5px;" onclick="showForm('register')">Inscription</button>
</div>

<div id="login-form" style="display:<?= (empty($_GET['action']) || $_GET['action'] === 'connexion') ? 'block' : 'none' ?>">
    <form action="index.php?controller=auth&action=connexion" method="post">
        <label>Username:</label><input name="username" required>
        <label>Password:</label><input type="password" name="password" required>
        <button type="submit">Connexion</button>
        <?php if (!empty($error)): ?><p class="error"><?=htmlspecialchars($error)?></p><?php endif; ?>
    </form>
</div>

<div id="register-form" style="display:<?= ($_GET['action'] === 'register') ? 'block' : 'none' ?>">
    <form action="index.php?controller=auth&action=register" method="post" enctype="multipart/form-data">
        <label>Pseudo:</label><input name="username" required>
        <label>Email:</label><input type="email" name="email" required>
        <label>Password:</label><input type="password" name="password" required>
        <label>Avatar:</label><input type="file" name="avatar">
        <label>Description:</label><textarea name="description"></textarea>
        <button type="submit">Inscription</button>
        <?php if (!empty($register_error)): ?><p class="error"><?=htmlspecialchars($register_error)?></p><?php endif; ?>
    </form>
</div>

<script>
function showForm(form) {
    document.getElementById('login-form').style.display = (form === 'login') ? 'block' : 'none';
    document.getElementById('register-form').style.display = (form === 'register') ? 'block' : 'none';
}
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>