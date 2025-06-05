<?php
$pageTitle = 'My profile';
include __DIR__ . '/../partials/header.php';
?>
<h2>My Profile</h2>
<div class="profile-container">
    <?php if (!empty($subscriber['avatar'])): ?>
        <img src="public/uploads/avatar/<?= htmlspecialchars($subscriber['avatar']) ?>" alt="Avatar <?= htmlspecialchars($subscriber['username']) ?>">
    <?php endif; ?>

    <div class="profile-info">
        <p><strong>Pseudo :</strong> <?= htmlspecialchars($subscriber['username']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($subscriber['email']) ?></p>
        <?php if (!empty($subscriber['description'])): ?>
            <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($subscriber['description'])) ?></p>
        <?php endif; ?>
    </div>
</div>

<a href="index.php?controller=profile&action=edit" class="button">Edit my profile</a>

<?php include __DIR__ . '/../partials/footer.php'; ?>