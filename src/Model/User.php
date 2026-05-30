<?php

class User
{
    public function getByEmail(string $email)

    {
        $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch();
        return $user;

    }

    public function updateNameEmailPasswordById($newName, $newEmail, $hashedPassword)
    {
        $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');

        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
        $stmt->execute([
            'name' => $newName,
            'email' => $newEmail,
            'password' => $hashedPassword,
            'id' => $_SESSION['userId'],
        ]);
    }

    public function updateNameEmailById($newName, $newEmail)
    {
        $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');

        $stmt = $pdo->prepare("
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
        $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');

        $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = :id");
        $userId = $_SESSION['userId'];
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function getById($userId)
    {
        $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
        $userId = $_SESSION['userId'];
        $stmt = $pdo->query("SELECT * FROM users WHERE id = " . $userId);
        $user = $stmt->fetch();
        return $user;
    }

    public function insertIntoUser($name, $email, $password)
    {
        $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);
        $result = $stmt->fetch();
        print_r($result);
    }
}