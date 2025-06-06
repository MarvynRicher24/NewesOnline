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

    <!-- Average note -->
    <div class="average-rating-detail">
        <?php if ($averageRating !== null): ?>
            <strong>Average rating :</strong> <?= htmlspecialchars($averageRating) ?> / 5
        <?php else: ?>
            <strong>Average rating :</strong> No rating yet
        <?php endif; ?>
    </div>

    <!-- List comments -->
    <div class="comments-section">
        <h3>Comments</h3>
        <?php if (!empty($allComments)): ?>
            <?php foreach ($allComments as $c): ?>
                <div class="comment-block">
                    <div class="comment-header">
                        <span class="comment-author"><?= htmlspecialchars($c['username']) ?></span>
                        <span class="comment-rating">Rating : <?= htmlspecialchars($c['rating']) ?>/5</span>
                        <span class="comment-date"><?= date('F j, Y', strtotime($c['created_at'])) ?></span>
                    </div>
                    <div class="comment-text">
                        <?= nl2br(htmlspecialchars($c['comment'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet</p>
        <?php endif; ?>
    </div>

    <!-- Comment form for subscriber -->
    <?php if (isset($_SESSION['role'], $_SESSION['user_id']) && $_SESSION['role'] === 'subscriber'): ?>
        <div class="user-comment-form">
            <h3>
                <?php if ($userComment): ?>
                    Edit your comment
                <?php else: ?>
                    Leave a comment
                <?php endif; ?>
            </h3>

            <?php if ($userComment): ?>
                <!-- Delete comment button -->
                <a href="index.php?controller=announcement&action=show&id=<?= $id ?>&delete_comment=1" class="button delete-comment-btn" style="background: #e74c3c; color: #fff; margin-bottom: 1rem;">Delete my comment</a>
            <?php endif; ?>

            <form action="index.php?controller=announcement&action=show&id=<?= $id ?>" method="POST">
                <!-- CSRF token -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <label for="rating">Rating (1-5) :</label>
                <select name="rating" id="rating" required>
                    <option value="">- Select -</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>"
                            <? ($userComment && intval($userComment['rating']) === $i) ? 'selected' : '' ?>>
                            <?= $i ?> star<?= $i > 1 ? 's' : '' ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <label for="comment">Your comment :</label>
                <textarea name="comment" id="comment" rows="4" required><?= $userComment ? htmlspecialchars($userComment['comment']) : '' ?></textarea>

                <button type="submit" name="submit_comment" class="button">
                    <?= $userComment ? 'Update your comment' : 'Post your comment' ?>
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>