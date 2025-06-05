<?php
class Announcement
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
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
            'title'         => $title,
            'subtitle'      => $subtitle,
            'content'       => $content,
            'category_id'   => $category_id,
            'image'         => $image
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
                'title'         => $title,
                'subtitle'      => $subtitle,
                'content'       => $content,
                'category_id'   => $category_id,
                'image'         => $image,
                'id'            => $id
            ];
        } else {
            $sql = "UPDATE announcements
                    SET title = :title, subtitle = :subtitle, content = :content, category_id = :category_id
                    WHERE id = :id";
            $params = [
                'title'         => $title,
                'subtitle'      => $subtitle,
                'content'       => $content,
                'category_id'   => $category_id,
                'id'            => $id
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
}
