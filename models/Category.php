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
        $sql = "INSERT INTO category (name) VALUES (:name)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $this->pdo->lastInsertId();
    }
}