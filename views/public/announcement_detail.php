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

<!-- Average Rating -->
<div class="average-rating">
    <div class="stars-readonly">
        <?php $avg = $averageRating ?? 0; ?>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <svg class="icon-star <?= $i <= round($avg) ? 'filled' : '' ?>" viewBox="0 0 24 24">
                <polygon points="12,2 15,9 22,9 17,14 19,21 12,17 5,21 7,14 2,9 9,9" />
            </svg>
        <?php endfor; ?>
    </div>
    <span class="rating-number"><?= $avg ? "$avg/5" : '—/5' ?></span>
</div>

<!-- Comments Section -->
<div class="comments-section">
    <h3>Comments</h3>
    <?php if (!empty($allComments)): ?>
        <?php foreach ($allComments as $c): ?>
            <div class="comment-block">
                <div class="comment-header">
                    <div class="stars-readonly">
                        <?php for ($j = 1; $j <= 5; $j++): ?>
                            <svg class="icon-star <?= $j <= $c['rating'] ? 'filled' : '' ?>" viewBox="0 0 24 24">
                                <polygon points="12,2 15,9 22,9 17,14 19,21 12,17 5,21 7,14 2,9 9,9" />
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <span class="comment-author"><?= htmlspecialchars($c['username']) ?></span>
                    <span class="comment-date"><?= date('F j, Y', strtotime($c['created_at'])) ?></span>

                    <!-- Admin comment deleting functionality -->
                     
                </div>
                <div class="comment-text"><?= nl2br(htmlspecialchars($c['comment'])) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No comments yet.</p>
    <?php endif; ?>
</div>

<!-- Subscriber Comment Form -->
<?php if (isset($_SESSION['role'], $_SESSION['user_id']) && $_SESSION['role'] === 'subscriber'): ?>
    <div class="user-comment-form">
        <h3><?= $userComment ? 'Edit Your Comment' : 'Leave a Comment' ?></h3>
        <?php if ($userComment): ?>
            <a href="index.php?controller=announcement&action=show&id=<?= $id ?>&delete_comment=1" class="button delete-comment-btn">Delete My Comment</a>
        <?php endif; ?>

        <form action="index.php?controller=announcement&action=show&id=<?= $id ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <div class="rating-input-inline">
                <?php $current = $userComment['rating'] ?? 0; ?>
                <?php for ($k = 1; $k <= 5; $k++): ?>
                    <button type="button" class="star-btn <?= $k <= $current ? 'filled' : '' ?>" data-value="<?= $k ?>" aria-label="<?= $k ?> stars">
                        <svg class="icon-star" viewBox="0 0 24 24">
                            <polygon points="12,2 15,9 22,9 17,14 19,21 12,17 5,21 7,14 2,9 9,9" />
                        </svg>
                    </button>
                <?php endfor; ?>
                <input type="hidden" name="rating" id="rating-value" value="<?= $current ?>" required>
            </div>

            <label for="comment">Your Comment:</label>
            <textarea name="comment" id="comment" rows="4" required><?= htmlspecialchars($userComment['comment'] ?? '') ?></textarea>

            <button type="submit" name="submit_comment" class="button"><?= $userComment ? 'Update' : 'Post' ?></button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.rating-input-inline .star-btn');
            const hidden = document.getElementById('rating-value');
            function setRating(r) {
                hidden.value = r;
                buttons.forEach(btn => {
                    btn.classList.toggle('filled', parseInt(btn.dataset.value) <= r);
                });
            }
            buttons.forEach(btn => {
                btn.addEventListener('click', () => setRating(parseInt(btn.dataset.value)));
            });
        });
    </script>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>
