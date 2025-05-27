<?php include __DIR__ . '/../partials/header.php'; ?>
<h2>My Profile</h2>
<div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
    <?php if (!empty($subscriber['avatar'])): ?>
        <img src="public/uploads/avatar/<?= htmlspecialchars($subscriber['avatar']) ?>" alt="Avatar" style="width:80px;height:80px;border-radius:50%;border:2px solid #3498db;">
    <?php endif; ?>
    <div>
        <div style="font-size: 1.3em; font-weight: bold;"><?= htmlspecialchars($subscriber['username']) ?></div>
        <div style="color: #888;"><?= htmlspecialchars($subscriber['email']) ?></div>
    </div>
</div>
<a href="index.php?controller=profile&action=edit" class="button">Edit my profile</a>
<?php include __DIR__ . '/../partials/footer.php'; ?>