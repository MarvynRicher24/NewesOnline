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
        // Ensure admin is logged in for all admin actions
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=connexion');
            exit;
        }

        $this->pdo               = $pdo;
        $this->announcementModel = new Announcement($pdo);
        $this->categoryModel     = new Category($pdo);
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
        $error      = '';

        // CSRF check on POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                empty($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                // Token missing or invalid: reject the request
                die('CSRF validation failed.');
            }
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title      = trim(string: $_POST['title']);
            $subtitle   = trim(string: $_POST['subtitle']);
            $content    = trim(string: $_POST['content']);
            $categoryId = intval($_POST['category_id']);

            // Minimum validation
            if (empty($title) || empty($content) || !$categoryId) {
                $error = "Please fill in all available fields";
            } else {
                // Image upload
                $image = null;
                if (!empty($_FILES['image']['name'])) {
                    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                        $error = "The file is too large (max 2mo)";
                    } else {
                        $check = getimagesize($_FILES['image']['tmp_name']);
                        if ($check === false) {
                            $error = "Uploaded file is not a valid image";
                        } else {
                            $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
                            $target    = __DIR__ . '/../public/uploads/' . $imageName;
                            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                                $image = $imageName;
                            } else {
                                $error = "Error during image recording";
                            }
                        }
                    }
                }

                if (empty($error)) {
                    $this->announcementModel->create($title, $subtitle, $content, $categoryId, $image);
                    $_SESSION['flash_message'] = 'Successfully created announcement';
                    header('Location: index.php?controller=admin&action=index');
                    exit;
                }
            }
        }
        // Show add form
        require __DIR__ . '/../views/admin/announcement_form.php';
    }

    // Show form to edit an announcement or handle update
    public function edit()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if (!$id) {
            header('Location: index.php?controller=admin&action=index');
            exit;
        }

        $announcement = $this->announcementModel->findById($id);
        if (!$announcement) {
            header('Location: index.php?controller=admin&action=index');
            exit;
        }

        $categories = $this->categoryModel->getAll();
        $error      = '';

        // CSRF check
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                empty($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                // Token missing or invalid: reject the request
                die('CSRF validation failed.');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title      = trim($_POST['title']);
            $subtitle   = trim($_POST['subtitle']);
            $content    = trim($_POST['content']);
            $categoryId = intval($_POST['category_id']);

            if (empty($title) || empty($content) || !$categoryId) {
                $error = "Please fill in all required files";
            } else {
                // Handle file upload if an image was provided
                $image = null;
                if (!empty($_FILES['image']['name'])) {
                    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                        $error = "The file is too large (max 2mo)";
                    } else {
                        $check = getimagesize($_FILES['image']['tmp_name']);
                        if ($check === false) {
                            $error = "Error, the downloaded file is not a valid image";
                        } else {
                            $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
                            $target = __DIR__ . '/../public/uploads/' . $imageName;
                            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                                $image = $imageName;
                            } else {
                                $error = "Error during image recording";
                            }
                        }
                    }
                }
                if (empty($error)) {
                    $this->announcementModel->update($id, $title, $subtitle, $content, $categoryId, $image);
                    $_SESSION['flash_message'] = 'Announcement successfully updated';
                    header('Location: index.php?controller=admin&action=index');
                    exit;
                }
            }
        }
        require __DIR__ . '/../views/admin/announcement_form.php';
    }

    public function delete()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id) {
            $this->announcementModel->delete($id);
            $_SESSION['flash_message'] = 'Announcement deleted';
        }
        header('Location:index.php?controller=admin&action=index');
        exit;
    }
}
