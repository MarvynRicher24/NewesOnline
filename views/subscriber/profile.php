<?php
$pageTitle = 'My profile';
include __DIR__ . '/../partials/header.php';
?>

<!-- Show pop-up edit success -->
<?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="popup-success" id="flash-popup-profile"><?= htmlspecialchars($_SESSION['flash_message']) ?></div>
    <script>
        setTimeout(function() {
            const popup = document.getElementById('flash-popup-profile');
            if (popup) {
                popup.style.opacity = '0';
                setTimeout(() => popup.remove(), 500);
            }
        }, 2000);
    </script>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<h2>My Profile</h2>
<div class="profile-container">
    <?php if (!empty($subscriber['avatar'])): ?>
        <img src="public/uploads/avatar/<?= htmlspecialchars($subscriber['avatar']) ?>" alt="Avatar <?= htmlspecialchars($subscriber['username']) ?>">
    <?php endif; ?>

    <div class="profile-info">
        <p><strong>Username :</strong> <?= htmlspecialchars($subscriber['username']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($subscriber['email']) ?></p>
        <?php if (!empty($subscriber['description'])): ?>
            <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($subscriber['description'])) ?></p>
        <?php endif; ?>
    </div>
</div>

<a href="index.php?controller=profile&action=edit" class="button">Edit my profile</a>

<?php include __DIR__ . '/../partials/footer.php'; ?>