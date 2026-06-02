<?php
namespace Model;

class User extends Model
{

    public function getByEmail(string $email)
    {
        $stmt = $this->PDO->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch();
        return $user;
    }

    public function updateNameEmailPasswordById($newName, $newEmail, $hashedPassword)
    {

        $stmt = $this->PDO->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
        $stmt->execute([
            'name' => $newName,
            'email' => $newEmail,
            'password' => $hashedPassword,
            'id' => $_SESSION['userId'],
        ]);
    }

    public function updateNameEmailById($newName, $newEmail)
    {

        $stmt = $this->PDO->prepare("
        UPDATE users
        SET name = :name, email = :email
        WHERE id = :id
    ");
        $stmt->execute([
            'name' => $newName,
            'email' => $newEmail,
            'id' => $_SESSION['userId'],
        ]);
    }

    public function getNameEmailById($userId)
    {
        $stmt = $this->PDO->prepare("SELECT name, email FROM users WHERE id = :id");
        $userId = $_SESSION['userId'];
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function getById($userId)
    {
        $userId = $_SESSION['userId'];
        $stmt = $this->PDO->query("SELECT * FROM users WHERE id = " . $userId);
        $user = $stmt->fetch();
        return $user;
    }

    public function insertIntoUser($name, $email, $password)
    {
        $stmt = $this->PDO->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);
        $result = $stmt->fetch();
        print_r($result);
    }
}