<?php
class Announcement
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    // Get a paginated list, with optional search and category filter
    public function getPaginated(int $limit, int $offset, ?string $search = null, ?int $categoryId = null): array
    {
        $sql = "SELECT a.*, c.name AS category_name
                FROM announcements a
                LEFT JOIN category c ON a.category_id = c.id";
        $where = [];
        $params = [];

        if ($search !== null && $search !== '') {
            $where[] = "(a.title LIKE :search OR a.subtitle LIKE :search OR a.content LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        if ($categoryId) {
            $where[] = "a.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= " ORDER BY a.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count total matching rows (for pagination)
    public function getCount(?string $search = null, ?int $categoryId = null): int
    {
        $sql = "SELECT COUNT(*) FROM announcements a";
        $where = [];
        $params = [];

        if ($search !== null && $search !== '') {
            $where[] = "(a.title LIKE :search OR a.subtitle LIKE :search OR a.content LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        if ($categoryId) {
            $where[] = "a.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    // Get the categories
    public function getAll()
    {
        $sql = "SELECT a.*, c.name AS category_name
                FROM announcements a
                LEFT JOIN category c ON a.category_id = c.id
                ORDER BY a.created_at DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find announcement by ID
    public function findById($id)
    {
        $sql = "SELECT a.*, c.name AS category_name
                FROM announcements a
                Left JOIN category c ON a.category_id = c.id
                WHERE a.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new announcement
    public function create($title, $subtitle, $content, $category_id, $image = null)
    {
        $sql = 'INSERT INTO announcements (title, subtitle, content, category_id, image)
                VALUES (:title, :subtitle, :content, :category_id, :image)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'title'       => $title,
            'subtitle'    => $subtitle,
            'content'     => $content,
            'category_id' => $category_id,
            'image'       => $image
        ]);
    }

    // Update an existing announcement
    public function update($id, $title, $subtitle, $content, $category_id, $image = null)
    {
        if ($image) {
            $sql = "UPDATE announcements
                    SET title = :title, subtitle = :subtitle, content = :content, category_id = :category_id, image = :image
                    WHERE id = :id";
            $params = [
                'title'       => $title,
                'subtitle'    => $subtitle,
                'content'     => $content,
                'category_id' => $category_id,
                'image'       => $image,
                'id'          => $id
            ];
        } else {
            $sql = "UPDATE announcements
                    SET title = :title, subtitle = :subtitle, content = :content, category_id = :category_id
                    WHERE id = :id";
            $params = [
                'title'       => $title,
                'subtitle'    => $subtitle,
                'content'     => $content,
                'category_id' => $category_id,
                'id'          => $id
            ];
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    // Delete an announcement
    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM announcements WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    // Average notes
    public function getAverageRating($announcementId)
    {
        $commentModel = new Comment($this->pdo);
        return $commentModel->getAverageRating($announcementId);
    }

    // Return list of comments
    public function getComments($announcementId)
    {
        $commentModel = new Comment($this->pdo);
        return $commentModel->getAllByAnnouncement($announcementId);
    }
}
