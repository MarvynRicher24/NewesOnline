<?php
require_once __DIR__ . '/../models/Announcement.php';

// AnnouncementController handles displaying a single announcement detail
class AnnouncementController {
    private $announcementModel;
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->announcementModel = new Announcement($pdo);
    }

    // Show detail of an announcement
    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php'); exit;
        }
            $announcement = $this->announcementModel->findById($id);
            if (!$announcement) {
                header('Location: index.php'); exit;
            }
            require __DIR__ . '/../views/public/announcement_detail.php';
    }
}