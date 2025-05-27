<?php
class Subscriber
{
    private $pdo;

    // FIND BY EMAIL
    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM subscriber WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // FIND BY ID
    public function findById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM subscriber WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // FIND BY USERNAME
    public function findByUsername($username)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM subscriber WHERE username = :username');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CREATE SUSCRIBER
    public function create($username, $email, $passwordHash, $avatar = null, $description = null)
    {
        $sql = 'INSERT INTO subscriber (username,email,password,avatar,description) VALUES (:username,:email,:password,:avatar,:description)';
        return $this->pdo->prepare($sql)->execute([
            'username' => $username,
            'email' => $email,
            'password' => $passwordHash,
            'avatar' => $avatar,
            'description' => $description
        ]);
    }

    // UPDATE SUSCRIBER
    public function updateProfile($id, $username, $email, $passwordHash = null, $avatar = null, $description = null)
    {
        $sql = 'UPDATE subscriber SET username=:username,email=:email';
        $params = ['username' => $username, 'email' => $email, 'id' => $id];
        if ($passwordHash) {
            $sql .= ',password=:password';
            $params['password'] = $passwordHash;
        }
        if ($avatar) {
            $sql .= ',avatar=:avatar';
            $params['avatar'] = $avatar;
        }
        if ($description !== null) {
            $sql .= ',description=:description';
            $params['description'] = $description;
        }
        $sql .= ' WHERE id=:id';
        return $this->pdo->prepare($sql)->execute($params);
    }

    // DELETE SUSCRIBER
    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM subscriber WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
