<?php
$pageTitle = htmlspecialchars($announcement['title']);
include __DIR__ . '/../partials/header.php';
?>

<a href="index.php" class="buttonReturn">Return</a>

<div class="detail-container">
    <h2><?= htmlspecialchars($announcement['title']) ?></h2>

    <?php if (!empty($announcement['subtitle'])): ?>
        <h3><?= htmlspecialchars($announcement['subtitle']) ?></h3>
    <?php endif; ?>

    <?php if ($announcement['category_name']): ?>
        <div class="card-category"><?= htmlspecialchars($announcement['category_name']) ?></div>
    <?php endif; ?>

    <div class="detail-meta">Posted on <?= date('F j, Y', strtotime($announcement['created_at'])) ?></div>

    <?php if ($announcement['image']): ?>
        <img src="public/uploads/<?= htmlspecialchars($announcement['image']) ?>" class="detail-image">
    <?php endif; ?>

    <p>
        <?= nl2br(htmlspecialchars($announcement['content'])) ?>
    </p>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>