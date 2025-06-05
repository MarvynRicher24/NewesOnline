<?php
$pageTitle = 'latest Announcements';
include __DIR__ . '/../partials/header.php';
?>

<h2>Latest Announcements</h2>

<?php if (!empty($announcements)): ?>
    <div class="announcements-grid">

        <?php foreach ($announcements as $announcement): ?>
            <div class="announcement-card">
                <?php if (!empty($announcement['category_name'])): ?>
                    <div class="card-category"><?= htmlspecialchars($announcement['category_name']) ?></div>
                <?php endif; ?>

                <?php if (!empty($announcement['image'])): ?>
                    <img src="public/uploads/<?= htmlspecialchars($announcement['image']) ?>" alt="Announcement image">
                <?php endif; ?>

                <div class="card-content">
                    <h3><?= htmlspecialchars($announcement['title']) ?></h3>

                    <?php if (!empty($announcement['subtitle'])): ?>
                        <h4><?= htmlspecialchars($announcement['subtitle']) ?></h4>
                    <?php endif; ?>

                    <p><?= nl2br(htmlspecialchars(substr($announcement['content'], 0, 100))) ?>…</p>
                    <div class="meta-date">Posted on <?= date('F j, Y', strtotime($announcement['created_at'])) ?></div>
                    <a href="index.php?controller=announcement&action=show&id=<?= $announcement['id'] ?>" class="button">Read More</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php else: ?>
    <p>No announcements.</p>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>