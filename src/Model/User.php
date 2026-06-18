<?php
namespace Model;

class User extends Model
{

    private int $id;
    private string $name;
    private string $email;
    private string $password;

    protected function getTableName(): string
    {
        return 'users';
    }

    public function getByEmail(string $email): self|null
    {
        $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if($user === false){
            return null;
        }

       $obj = new self;
        $obj->id = $user['id'];
        $obj->name = $user['name'];
        $obj->email = $user['email'];
        $obj->password = $user['password'];

        return $obj;
    }

    public function updateNameEmailPasswordById($newName, $newEmail, $hashedPassword)
    {

        $stmt = $this->PDO->prepare("UPDATE {$this->getTableName()} SET name = :name, email = :email, password = :password WHERE id = :id");
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
        UPDATE {$this->getTableName()}
        SET name = :name, email = :email
        WHERE id = :id
    ");
        $stmt->execute([
            'name' => $newName,
            'email' => $newEmail,
            'id' => $_SESSION['userId'],
        ]);
    }

    public function getNameEmailById($userId): self|null
    {
        $stmt = $this->PDO->prepare("SELECT name, email FROM {$this->getTableName()} WHERE id = :id");
        $userId = $_SESSION['userId'];
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();


        if($user === false){
            return null;
        }

        $obj = new self;

        $obj->name = $user['name'];
        $obj->email = $user['email'];

        return $obj;
    }

    public function getById($userId):self|null
    {
        $userId = $_SESSION['userId'];
        $stmt = $this->PDO->query("SELECT * FROM {$this->getTableName()} WHERE id = " . $userId);
        $user = $stmt->fetch();

        if($user === false){
            return null;
        }

        $obj = new self;
        $obj->id = $user['id'];
        $obj->name = $user['name'];
        $obj->email = $user['email'];
        $obj->password = $user['password'];
        return $obj;
    }

    public function insertIntoUser(string $name, string $email, string $password): void
    {
        $stmt = $this->PDO->prepare("INSERT INTO {$this->getTableName()} (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }


}

