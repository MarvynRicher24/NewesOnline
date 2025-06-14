<?php
class Subscriber
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Find by email
    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM subscriber WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Find by id
    public function findById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM subscriber WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Find by username
    public function findByUsername($username)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM subscriber WHERE username = :username');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create subscriber
    public function create($username, $email, $passwordHash, $avatar = null, $description = null)
    {
        $sql = 'INSERT INTO subscriber (username, email, password, avatar, description)
                VALUES (:username, :email, :password, :avatar, :description)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'username'    => $username,
            'email'       => $email,
            'password'    => $passwordHash,
            'avatar'      => $avatar,
            'description' => $description
        ]);
    }

    // Update subscriber
    public function updateProfile($id, $username, $email, $passwordHash = null, $avatar = null, $description = null)
    {
        $sql = 'UPDATE subscriber SET username=:username, email=:email';
        $params = [
            'username' => $username,
            'email'    => $email,
            'id'       => $id];
        if ($passwordHash) {
            $sql .= ', password = :password';
            $params['password'] = $passwordHash;
        }
        if ($avatar) {
            $sql .= ',avatar = :avatar';
            $params['avatar'] = $avatar;
        }
        if ($description !== null) {
            $sql .= ',description = :description';
            $params['description'] = $description;
        }
        $sql .= ' WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Delete subscriber
    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM subscriber WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
