<?php include __DIR__ . '/../partials/header.php'; ?>

<a href="index.php?controller=subscriber" class="buttonReturn">Return</a>
<h2>My profil</h2>
<form action="index.php?controller=profile&action=update" method="post" enctype="multipart/form-data">

    <label>Pseudo:</label>
    <input name="username" value="<?= htmlspecialchars($subscriber['username']) ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($subscriber['email']) ?>" required>

    <label>New password:</label>
    <input type="password" name="password">

    <label>Choose an Avatar:</label>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <?php foreach ($avatars as $avatar): ?>
            <label for="display: flex; flex-direction: column; align-items: center;">
                <input type="radio" name="avatar" value="<?= htmlspecialchars($avatar) ?>" <?= ($subscriber['avatar'] === $avatar) ? 'checked' : '' ?> required>
                <img src="public/uploads/avatar/<?= htmlspecialchars($avatar) ?>" alt="Avatar" style="width: 60px; height: 60px; border-radius: 50%; border: 2px solid #ccc;">
            </label>
        <?php endforeach; ?>
    </div>

    <label>Description:</label>
    <textarea name="description"><?= htmlspecialchars($subscriber['description']) ?></textarea>

    <button type="submit">Update</button>

    <?php if (!empty($error)): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>