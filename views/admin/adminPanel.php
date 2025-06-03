<?php include __DIR__ . '/../partials/header.php'; ?>

<!-- CREATED POP-UP -->
<?php if (isset($_GET['created'])): ?>
    <div id="popup-created">Announcement created successfully!
    </div>
    <script>
        setTimeout(function() {
            var popup = document.getElementById('popup-created');
            if (popup) popup.style.opacity = '0';
            setTimeout(function() {
                if (popup) popup.remove();
            }, 500);
        }, 2000);
    </script>
<?php endif; ?>

<!-- UPDATED POP-UP -->
<?php if (isset($_GET['updated'])): ?>
    <div id="popup-success">Announcement updated successfully!
    </div>
    <script>
        setTimeout(function() {
            var popup = document.getElementById('popup-success');
            if (popup) popup.style.opacity = '0';
            setTimeout(function() {
                if (popup) popup.remove();
            }, 500); // Match the duration of the CSS transition
        }, 2000);
    </script>
<?php endif; ?>

<!-- DELETED POP-UP -->
<?php if (isset($_GET['deleted'])): ?>
    <div id="popup-deleted">Announcement deleted!
    </div>
    <script>
        setTimeout(function() {
            var popup = document.getElementById('popup-deleted');
            if (popup) popup.style.opacity = '0';
            setTimeout(function() {
                if (popup) popup.remove();
            }, 500);
        }, 2000);
    </script>
<?php endif; ?>

<h2 style="text-align: center;">Manage Announcements</h2>

<?php if (!empty($announcements)): ?>

    <div class="adminPanel-container">
        <a href="index.php?controller=admin&action=add" style="display:inline-block;">
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
                            <a href="index.php?controller=admin&action=delete&id=<?= $announcement['id'] ?>" class="button delete-btn">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de confirmation suppression -->
    <div class="deleteModal-container" id="delete-modal">
        <div class="deleteModal">
            <h3 style="margin-bottom:20px;">Are you sure you want to delete this announcement?</h3>
            <div style="display:flex; gap:16px; justify-content:center;">
                <button id="confirm-delete">Delete</button>
                <button id="cancel-delete">Cancel</button>
            </div>
        </div>
    </div>
    <script>
        let deleteUrl = '';
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                deleteUrl = this.getAttribute('href');
                document.getElementById('delete-modal').style.display = 'flex';
            });
        });
        document.getElementById('cancel-delete').onclick = function() {
            document.getElementById('delete-modal').style.display = 'none';
            deleteUrl = '';
        };
        document.getElementById('confirm-delete').onclick = function() {
            if (deleteUrl) window.location.href = deleteUrl;
        };
    </script>

<?php else: ?>
    <div class="createAnnouncement-container">
        <!-- CREATE AN ANNOUNCEMENT BUTTON -->
        <div class="buttonCreateAnnouncement">
            <a href="index.php?controller=admin&action=add" style="display:inline-block;">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" style="margin-bottom: 18px;">
                    <circle cx="12" cy="12" r="12" fill="#e3eafc" />
                    <path d="M8 12h8M12 8v8" stroke="#3498db" stroke-width="2" stroke-linecap="round" />
                </svg>
            </a>
            <p style="font-size: 1.3em; color: #555; margin-bottom: 0;">No announcements.</p>
            <p style="color: #888; margin-top: 8px;">Click to create your first one!</p>
        </div>
    </div>
<?php endif; ?>
<?php include __DIR__ . '/../partials/footer.php'; ?>