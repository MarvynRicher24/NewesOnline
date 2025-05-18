<?php
require_once __DIR__ . '/../models/Announcement.php';

// HomeController handles public pages: homepage and about page
class HomeController {
    private $announcementModel;

    public function __construct($pdo) {
        $this->announcementModel = new Announcement($pdo);
    }

    // Show the homepage with all announcements
    public function index() {
        $announcements = $this->announcementModel->getAll();
        require __DIR__ . '/../views/public/home.php';
    }

    // Show the About page
    public function about() {
        require __DIR__ . '/../views/public/about.php';
    }
}