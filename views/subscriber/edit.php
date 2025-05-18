<?php
include __DIR__ . '/../partials/header.php'; ?>
<h2>Mon profil</h2>
<form action="index.php?controller=profile&action=update" method="post" enctype="multipart/form-data">
    <label>Pseudo:</label><input name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
    <label>Email:</label><input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    <label>Nouveau mot de passe:</label><input type="password" name="password">
    <label>Avatar:</label>
    <?php if (!empty($user['avatar'])): ?>
        <img src="public/uploads/<?= htmlspecialchars($user['avatar']) ?>" width="80"><br>
    <?php endif; ?>
    <input type="file" name="avatar">
    <label>Description:</label>
    <textarea name="description"><?= htmlspecialchars($user['description']) ?></textarea>
    <button type="submit">Mettre à jour</button>
    <?php if (!empty($error)): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>