<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="detail-container">
    <h2><?= htmlspecialchars($announcement['title']) ?></h2>
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