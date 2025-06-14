<?php
require_once __DIR__ . '/../models/Announcement.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Comment.php';

// HomeController handles public pages: homepage and about page
class HomeController
{
    private $announcementModel;
    private $categoryModel;
    private $commentModel;

    public function __construct($pdo)
    {
        $this->announcementModel = new Announcement($pdo);
        $this->categoryModel     = new Category($pdo);
        $this->commentModel     = new Comment($pdo);
    }

    // Show the homepage with all announcements
    public function index()
    {
        // Limit number of announcements on one page
        $limit = 6;
        $page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Search & category filter from GET
        $search      = isset($_GET['search']) ? trim($_GET['search']) : null;
        $categoryId  = isset($_GET['category']) ? (int)$_GET['category'] : null;

        // Fetch paginated announcements
        $announcements = $this->announcementModel->getPaginated($limit, $offset, $search, $categoryId);

        // Attach average rating to each announcement
        foreach ($announcements as &$ann) {
            $ann['average_rating'] = $this->commentModel
                ->getAverageRating($ann['id']);
        }
        unset($ann);

        // Compute total pages
        $totalCount  = $this->announcementModel->getCount($search, $categoryId);
        $totalPages  = (int)ceil($totalCount / $limit);

        // Fetch categories for the filter dropdown
        $categories = $this->categoryModel->getAll();

        require __DIR__ . '/../views/public/home.php';
    }
}
