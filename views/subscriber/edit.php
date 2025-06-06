<?php
$pageTitle = 'Edit Profile';
include __DIR__ . '/../partials/header.php';
?>

<!-- Show flash message immediately -->
<?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="popup-success" id="flash-popup-subscriber"><?= htmlspecialchars($_SESSION['flash_message']) ?></div>
    <script>
        setTimeout(function() {
            const popup = document.getElementById('flash-popup-subscriber');
            if (popup) {
                popup.style.opacity = '0';
                setTimeout(() => popup.remove(), 500);
            }
        }, 2000);
    </script>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<a href="index.php?controller=profile&action=profile" class="buttonReturn">Return</a>
<h2>My profile</h2>

<form action="index.php?controller=profile&action=update" method="post" enctype="multipart/form-data">

    <!-- CSRF token -->
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

    <label>Username :</label>
    <input name="username" value="<?= htmlspecialchars($subscriber['username']) ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($subscriber['email']) ?>" required>

    <label>New password :</label>
    <input type="password" name="password">

    <label>Choose an Avatar :</label>
    <div class="avatar-selection">
        <?php foreach ($avatars as $avatarFile): ?>
            <label class="avatar-option">
                <input type="radio" name="avatar" value="<?= htmlspecialchars($avatarFile) ?>" <?= ($subscriber['avatar'] === $avatarFile) ? 'checked' : '' ?> required>
                <img src="public/uploads/avatar/<?= htmlspecialchars($avatarFile) ?>" alt="Avatar choice" class="avatar-choice">
            </label>
        <?php endforeach; ?>
    </div>

    <label>Description:</label>
    <textarea name="description"><?= htmlspecialchars($subscriber['description'] ?? '') ?></textarea>

    <button type="submit" class="button">Update</button>

</form>

<?php include __DIR__ . '/../partials/footer.php'; ?>