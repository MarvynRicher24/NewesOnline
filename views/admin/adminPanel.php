<?php
$pageTitle = 'Admin Panel';
include __DIR__ . '/../partials/header.php';
?>

<!-- Flash pop-up for create / update / delete -->
<?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="popup-success" id="flash-popup-admin"><?= htmlspecialchars($_SESSION['flash_message']) ?></div>
    <script>
        setTimeout(function() {
            const popup = document.getElementById('flash-popup-admin');
            if (popup) {
                popup.style.opacity = 'O';
                setTimeout(() => popup.remove(), 500);
            }
        }, 2000);
    </script>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<h2 style="text-align: center;">Manage Announcements</h2>

<?php if (!empty($announcements)): ?>

    <div class="adminPanel-container">
        <a href="index.php?controller=admin&action=add" class="buttonCreate">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" style="margin-bottom: 18px;">
                <circle cx="12" cy="12" r="12" fill="#e3eafc" />
                <path d="M8 12h8M12 8v8" stroke="#3498db" stroke-width="2" stroke-linecap="round" />
            </svg>
        </a>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($announcements as $announcement): ?>
                    <tr>
                        <td><?= htmlspecialchars($announcement['title']) ?></td>
                        <td><?= htmlspecialchars($announcement['category_name'] ?? '') ?></td>
                        <td>
                            <?php if (!empty($announcement['image'])): ?>
                                <img src="public/uploads/<?= htmlspecialchars($announcement['image']) ?>" width="80" alt="Announcement image">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?controller=admin&action=edit&id=<?= $announcement['id'] ?>" class="button">Edit</a>
                            <button data-href="index.php?controller=admin&action=delete&id=<?= $announcement['id'] ?>" class="button delete-btn">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de confirmation suppression -->
    <div class="deleteModal-container" id="delete-modal">
        <div class="deleteModal">
            <h3>Are you sure you want to delete this announcement?</h3>
            <div class="modal-buttons">
                <button id="confirm-delete">Delete</button>
                <button id="cancel-delete">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Modal confirmation -->
    <script>
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const deleteUrl = this.dataset.href;
                const modal = document.getElementById('delete-modal');
                modal.style.display = 'flex';

                document.getElementById('confirm-delete').onclick = function() {
                    window.location.href = deleteUrl;
                };
                document.getElementById('cancel-delete').onclick = function() {
                    modal.style.display = 'none';
                };
            });
        });
    </script>

<?php else: ?>
    <div class="createAnnouncement-container">
        <!-- Create an announcement button -->
        <div class="buttonCreateAnnouncement">
            <a href="index.php?controller=admin&action=add">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="12" fill="#e3eafc" />
                    <path d="M8 12h8M12 8v8" stroke="#3498db" stroke-width="2" stroke-linecap="round" />
                </svg>
            </a>
            <p>No announcements.</p>
            <p>Click to create your first one !</p>
        </div>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>