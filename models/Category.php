<?php
class Category
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        return $this->pdo->query('SELECT * FROM category ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name)
    {
        $stmt = $this->pdo->prepare("INSERT INTO category (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);
        return $this->pdo->lastInsertId();
    }
}
