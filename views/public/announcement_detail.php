<?php
$pageTitle = htmlspecialchars($announcement['title']);
include __DIR__ . '/../partials/header.php';
?>

<a href="index.php" class="buttonReturn">Return</a>

<!-- Show flash message immediately -->
<?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="popup-success" id="flash-popup-comment"><?= htmlspecialchars($_SESSION['flash_message']) ?></div>
    <script>
        setTimeout(function() {
            const popup = document.getElementById('flash-popup-comment');
            if (popup) {
                popup.style.opacity = '0';
                setTimeout(() => popup.remove(), 500);
            }
        }, 2000);
    </script>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

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
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <button class="delete-comment-admin-btn" data-id="<?= $c['id'] ?>" title="Delete comment">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e74c3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                <path d="M10 11v6"></path>
                                <path d="M14 11v6"></path>
                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>
                <div class="comment-text"><?= nl2br(htmlspecialchars($c['comment'])) ?></div>
            </div>
        <?php endforeach; ?>

        <!-- Delete confirmation Modal Admin -->
        <div class="deleteModal-container" id="delete-comment-modal">
            <div class="deleteModal">
                <h3>Are you sure you want to delete this comment ?</h3>
                <div class="modal-buttons">
                    <button id="confirm-delete-comment" class="confirm-delete">Delete</button>
                    <button id="cancel-delete-comment" class="cancel-delete">Cancel</button>
                </div>
            </div>
        </div>

        <!-- Delete confirmation Modal Subscriber -->
        <div class="deleteModal-container" id="delete-user-comment-modal">
            <div class="deleteModal">
                <h3>Are you sure you want to delete this comment ?</h3>
                <div class="modal-buttons">
                    <button id="confirm-delete-user-comment" class="confirm-delete">Delete</button>
                    <button id="cancel-delete-user-comment" class="cancel-delete">Cancel</button>
                </div>
            </div>
        </div>

    <?php else: ?>
        <p>No comments yet.</p>
    <?php endif; ?>
</div>

<!-- Script modal Admin -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('delete-comment-modal');
        let targetId = null;
        document.querySelectorAll('.delete-comment-admin-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                targetId = btn.getAttribute('data-id');
                modal.style.display = 'flex';
            });
        });
        document.getElementById('cancel-delete-comment').onclick = () => modal.style.display = 'none';
        document.getElementById('confirm-delete-comment').onclick = () => {
            if (targetId) {
                window.location.href = `index.php?controller=announcement&action=show&id=<?= $id ?>&delete_comment_admin=${targetId}`;
            }
        };
    });
</script>

<!-- Script modal Subscriber -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const trigger = document.getElementById('trigger-delete-user-comment');
        const modal = document.getElementById('delete-user-comment-modal');
        trigger?.addEventListener('click', () => modal.style.display = 'flex');
        document.getElementById('cancel-delete-user-comment')
            .onclick = () => modal.style.display = 'none';
        document.getElementById('confirm-delete-user-comment')
            .onclick = () => {
                window.location.href =
                    `index.php?controller=announcement&action=show&id=<?= $id ?>&delete_comment=1`;
            };
    });
</script>


<!-- Subscriber Comment Form -->
<?php if (isset($_SESSION['role'], $_SESSION['user_id']) && $_SESSION['role'] === 'subscriber'): ?>
    <div class="user-comment-form">
        <h3><?= $userComment ? 'Edit Your Comment' : 'Leave a Comment' ?></h3>
        <?php if ($userComment): ?>
            <button type="button" id="trigger-delete-user-comment" class="button delete-comment-btn">Delete my comment</button>
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