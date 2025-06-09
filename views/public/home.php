<?php
$pageTitle = 'Latest Announcements';
include __DIR__ . '/../partials/header.php';
?>

<!-- Search & Filter Form -->
<form method="get" action="index.php" class="search-filter-form">
    <input type="hidden" name="controller" value="home">
    <input type="hidden" name="action" value="index">

    <input
        type="text"
        name="search"
        placeholder="Search announcements..."
        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

    <select name="category">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
            <option
                value="<?= $cat['id'] ?>"
                <?= (isset($_GET['category']) && (int)$_GET['category'] === (int)$cat['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="button">Filter</button>
</form>

<!-- Latest announcements -->
<h2>Latest Announcements</h2>

<?php if (!empty($announcements)): ?>
    <div class="announcements-grid improved-grid">

        <?php foreach ($announcements as $announcement): ?>
            <div class="announcement-card improve-card">
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

                    <!-- Average rating -->
                    <?php $avg = $announcement['average_rating']; ?>
                    <div class="stars-readonly">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="icon-star <?= $i <= round($avg) ? 'filled' : '' ?>"
                                viewBox="0 0 24 24">
                                <polygon points="12,2 15,9 22,9 17,14
                                                 19,21 12,17 5,21 7,14 2,9 9,9" />
                            </svg>
                        <?php endfor; ?>
                        <span class="rating-number"><?= $avg !== null ? $avg . '/5' : '—/5' ?></span>
                    </div>

                    <!-- Read more button -->
                    <a href="index.php?controller=announcement&action=show&id=<?= $announcement['id'] ?>" class="button">Read More</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination links -->
    <?php if ($totalPages > 1): ?>
        <nav class="pagination">
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <?php
                    // Preserve search & category in pagination links
                    $qs = http_build_query(array_merge($_GET, ['page' => $p]));
                ?>
                <a
                    href="index.php?<?= $qs ?>"
                    class="<?= $p === $page ? 'active' : '' ?>">
                    <?= $p ?>
                </a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>

<?php else: ?>
    <p>No announcements</p>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>