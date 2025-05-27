<?php include __DIR__ . '/../partials/header.php'; ?>

<!-- CREATED POP-UP -->
<?php if (isset($_GET['created'])): ?>
    <div id="popup-created" style="
            position: fixed;
            top: 30px;
            right: 30px;
            background: #3498db;
            color: #fff;
            padding: 16px 32px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            font-size: 1.1em;
            z-index: 9999;
            opacity: 0.95;
            transition: opacity 0.5s;
            ">Announcement created successfully!
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
    <div id="popup-success" style="
            position: fixed;
            top: 30px;
            right: 30px;
            background: #4BB543;
            color: #fff;
            padding: 16px 32px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            font-size: 1.1em;
            z-index: 9999;
            opacity: 0.95;
            transition: opacity 0.5s;
            ">Announcement updated successfully!
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
    <div id="popup-deleted" style="
            position: fixed;
            top: 30px;
            right: 30px;
            background: #e74c3c;
            color: #fff;
            padding: 16px 32px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            font-size: 1.1em;
            z-index: 9999;
            opacity: 0.95;
            transition: opacity 0.5s;
            ">Announcement deleted!
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

    <div style="
            background: #f8f9fa;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(52,152,219,0.08);
            padding: 40px 60px;
            text-align: center;
            ">
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
                                <img src="public/uploads/<?= htmlspecialchars($announcement['image']) ?>" width="80">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?controller=admin&action=edit&id=<?= $announcement['id'] ?>" class="button">Edit</a>
                            <a href="index.php?controller=admin&action=delete&id=<?= $announcement['id'] ?>" class="button delete-btn" data-id="<? $announcement['id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de confirmation suppression -->
    <div id="delete-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; padding:32px 24px; border-radius:10px; box-shadow:0 2px 16px rgba(0,0,0,0.2); min-width:300px; text-align:center;">
            <h3 style="margin-bottom:20px;">Are you sure you want to delete this announcement?</h3>
            <div style="display:flex; gap:16px; justify-content:center;">
                <button id="confirm-delete" style="background:#e74c3c; color:#fff; border:none; padding:10px 24px; border-radius:5px; cursor:pointer;">Delete</button>
                <button id="cancel-delete" style="background:#ccc; color:#333; border:none; padding:10px 24px; border-radius:5px; cursor:pointer;">Cancel</button>
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
    <div style="
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 40vh;
    ">
        <!-- CREATE AN ANNOUNCEMENT BUTTON -->
        <div style="
            background: #f8f9fa;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(52,152,219,0.08);
            padding: 40px 60px;
            text-align: center;
        ">
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