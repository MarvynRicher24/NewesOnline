<?php
if (!isset($announcement)) {
    $announcement = [];
}
?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<a href="index.php?controller=admin" class="button" style="margin-top:10px; background:#888; color:#fff; text-decoration:none; padding:6px 18px; border-radius:5px; min-width:0; display:inline-block;">Return</a>

<h2><?= isset($announcement['id']) ? 'Edit' : 'Add' ?> announcement</h2>
<form action="index.php?controller=admin&action=<?= isset($announcement['id']) ? 'edit&id=' . $announcement['id'] : 'add' ?>" method="post" enctype="multipart/form-data">
    <label>Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($announcement['title'] ?? '') ?>" required>

    <label>Subtitle:</label>
    <input type="text" name="subtitle" value="<?= htmlspecialchars($announcement['subtitle'] ?? '') ?>">

    <label>Category:</label>
    <select name="category_id" required>
        <option value="">— Select —</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id'] ?>" <?= (isset($announcement['category_id']) && $announcement['category_id'] == $category['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($category['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Content:</label>
    <textarea name="content" rows="5" required><?= htmlspecialchars($announcement['content'] ?? '') ?></textarea>

    <?php if (!empty($announcement['image'])): ?>
        <p>Current Image:<br>
            <img src="public/uploads/<?= htmlspecialchars($announcement['image']) ?>" width="150" alt="Announcement image">
        </p>
    <?php endif; ?>

    <label>Image:</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit"><?= isset($announcement['id']) ? 'Update' : 'Create' ?></button>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>