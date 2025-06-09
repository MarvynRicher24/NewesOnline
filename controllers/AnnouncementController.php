<?php
require_once __DIR__ . '/../models/Announcement.php';
require_once __DIR__ . '/../models/Comment.php';

// AnnouncementController handles displaying a single announcement detail
class AnnouncementController
{
    private $pdo;
    private $announcementModel;
    private $commentModel;

    public function __construct($pdo)
    {
        $this->pdo               = $pdo;
        $this->announcementModel = new Announcement($pdo);
        $this->commentModel      = new Comment($pdo);
    }

    // Show detail of an announcement + create/edit/delete comments
    public function show()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if (!$id) {
            header('Location: index.php');
            exit;
        }

        // Catch announcement
        $announcement = $this->announcementModel->findById($id);
        if (!$announcement) {
            header('Location: index.php');
            exit;
        }

        // Admin Delete functionality
        if (isset($_GET['delete_comment_admin']) && is_numeric($_GET['delete_comment_admin'])) {
            if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                $commentId = intval($_GET['delete_comment_admin']);
                $this->commentModel->delete($commentId);
                $_SESSION['flash_message'] = 'Comment successfully deleted';
            }
            header("Location: index.php?controller=announcement&action=show&id={$id}");
            exit;
        }

        // Delete
        if (isset($_GET['delete_comment']) && $_GET['delete_comment'] == '1') {
            if (isset($_SESSION['role'], $_SESSION['user_id']) && $_SESSION['role'] === 'subscriber') {
                $existing = $this->commentModel->findBySubscriberAndAnnouncement($_SESSION['user_id'], $id);
                if ($existing) {
                    $this->commentModel->delete($existing['id']);
                    $_SESSION['flash_message'] = 'Comment deleted';
                }
            }
            header("Location: index.php?controller=announcement&action=show&id={$id}");
            exit;
        }

        // POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
            // CSRF token validation
            if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                die('CSRF validation failed');
            }

            // Subscriber connected verification
            if (isset($_SESSION['role'], $_SESSION['user_id']) && $_SESSION['role'] === 'subscriber') {
                $rating = intval($_POST['rating']);
                $commentText = trim($_POST['comment']);

                // Minimal validation
                if ($rating < 1 || $rating > 5 || empty($commentText)) {
                    $_SESSION['flash_message'] = 'Please provide a rating (1-5) and a comment';
                } else {
                    // Only one comment verification
                    $existing = $this->commentModel->findBySubscriberAndAnnouncement($_SESSION['user_id'], $id);
                    if ($existing) {
                        // Update
                        $this->commentModel->update($existing['id'], $rating, $commentText);
                        $_SESSION['flash_message'] = 'Your comment has been updated';
                    } else {
                        // Creation
                        $this->commentModel->create($_SESSION['user_id'], $id, $rating, $commentText);
                        $_SESSION['flash_message'] = 'Your comment has been posted';
                    }
                }
            }
            // Redirection
            header("Location: index.php?controller=announcement&action=show&id={$id}");
            exit;
        }
        // Load Average note
        $averageRating = $this->announcementModel->getAverageRating($id);

        // Load comments
        $allComments = $this->announcementModel->getComments($id);

        // Load subscriber comment
        $userComment = null;
        if (isset($_SESSION['role'], $_SESSION['user_id']) && $_SESSION['role'] === 'subscriber') {
            $userComment = $this->commentModel->findBySubscriberAndAnnouncement($_SESSION['user_id'], $id);
        }

        require __DIR__ . '/../views/public/announcement_detail.php';
    }
}
