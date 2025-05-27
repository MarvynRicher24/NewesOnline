<?php
require_once __DIR__ . '/../models/Announcement.php';
require_once __DIR__ . '/../models/Category.php';

// AdminController handles admin panel actions (announcement management)
class AdminController
{
    private $pdo;
    private $announcementModel;
    private $categoryModel;

    public function __construct($pdo)
    {
        session_start();
        // Ensure admin is logged in for all admin actions
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=connexion');
            exit;
        }
        $this->pdo = $pdo;
        require_once __DIR__ . '/../models/Announcement.php';
        require_once __DIR__ . '/../models/Category.php';
        $this->announcementModel = new Announcement($pdo);
        $this->categoryModel = new Category($pdo);
    }

    // List all announcements with edit/delete options
    public function index()
    {
        $announcements = $this->announcementModel->getAll();
        require __DIR__ . '/../views/admin/adminPanel.php';
    }

    // Show form to add a new announcement or handle form submission
    public function add()
    {
        $categories = $this->categoryModel->getAll();
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $subtitle = $_POST['subtitle'];
            $content = $_POST['content'];
            $category_Id = $_POST['category_id'];
            $image = null;

            // Handle file upload if an image was provided
            if (!empty($_FILES['image']['name'])) {
                $image = uniqid() . '_' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], 'public/uploads/' . $image);
            }

            $this->announcementModel->create($title, $subtitle, $content, $category_Id, $image);
            header('Location: index.php?controller=admin& created=1');
            exit;
        }
        // Show add form
        require __DIR__ . '/../views/admin/announcement_form.php';
    }

    // Show form to edit an announcement or handle update
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $subtitle = $_POST['subtitle'];
            $content = $_POST['content'];
            $category_Id = $_POST['category_id'];

            // Handle file upload if an image was provided
            $image = null;
            if (!empty($_FILES['image']['name'])) {
                $image = uniqid() . '_' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], 'public/uploads/' . $image);
            }
            $this->announcementModel->update($id, $title, $subtitle, $content, $category_Id, $image);
            header('Location: index.php?controller=admin&updated=1');
            exit;
        }

        $announcement = $this->announcementModel->findById($id);
        $categories = $this->categoryModel->getAll();
        require __DIR__ . '/../views/admin/announcement_form.php';
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->announcementModel->delete($id);
        }
        header('Location:index.php?controller=admin&deleted=1');
        exit;
    }
}
