<?php
$pageTitle = isset($announcement['id']) ? 'Edit announcement' : 'Create announcement';
include __DIR__ . '/../partials/header.php';
?>

<a href="index.php?controller=admin&action=index" class="buttonReturn">Return</a>

<h2><?= isset($announcement['id']) ? 'Edit this announcement' : 'Add a new announcement' ?></h2>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars(($error)) ?></p>
<?php endif; ?>

<form action="index.php?controller=admin&action=<?= isset($announcement['id']) ? 'edit&id=' . $announcement['id'] : 'add' ?>" method="post" enctype="multipart/form-data">

    <!-- CSRF token -->
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    
    <label>Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($announcement['title'] ?? '') ?>" maxlength="255" required>

    <label>Subtitle:</label>
    <input type="text" name="subtitle" value="<?= htmlspecialchars($announcement['subtitle'] ?? '') ?>" maxlength="255">

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

    <button type="submit" class="button"><?= isset($announcement['id']) ? 'Update' : 'Create' ?></button>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>