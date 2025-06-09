<?php
$pageTitle = 'Latest Announcements';
include __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../../models/Comment.php';
global $pdo;
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

                    <?php
                    $cm = new Comment($pdo);
                    $avg = $cm->getAverageRating($announcement['id']);
                    $fullCount = $avg !== null ? floor($avg) : 0;
                    $halfCount = $avg !== null && ($avg - $fullCount) >= 0.5 ? 1 : 0;
                    $emptyCount = 5 - $fullCount - $halfCount;
                    $fullPath = 'public/uploads/stars/fullStars.png';
                    $halfPath = 'public/uploads/stars/halfStars.png';
                    $emptyPath = 'public/uploads/stars/emptyStars.png';
                    ?>

                    <div class="stars-readonly">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="icon-star <?= $i <= round($avg) ? 'filled' : '' ?>" viewBox="0 0 24 24">
                                <polygon points="12,2 15,9 22,9 17,14 19,21 12,17 5,21 7,14 2,9 9,9" />
                            </svg>
                        <?php endfor; ?>
                        <span class="visually-hidden">
                            <?= $avg !== null ? htmlspecialchars($avg) . '/5' : '—/5' ?>
                        </span>

                    </div>


                    <a href="index.php?controller=announcement&action=show&id=<?= $announcement['id'] ?>" class="button">Read More</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php else: ?>
    <p>No announcements.</p>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>