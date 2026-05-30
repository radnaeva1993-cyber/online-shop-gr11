<?php

class Product
{
    public function getProducts()
    {
        $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
// если пользователь найден выдаем каталог

        $stmt = $pdo->query('SELECT * FROM products');
        $products = $stmt->fetchAll();
        return $products;

    }
 public function getUserProductsByProductIdUserId($productId,$userId)
 {
     $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
     $stmt = $pdo->prepare("SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId");
     $stmt->execute(['productId' => $productId, 'userId' => $userId]);
     $data = $stmt->fetch();
     return $data;
}

public function insertIntoUserProducts($userId, $productId, $amount)
{
    $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
    $userId = $_SESSION['userId'];
    $stmt = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:userId, :productId, :amount)");
    $stmt->execute(['userId' => $userId, 'productId' => $productId, 'amount' => $amount]);
}

public function getUserProductByAmount($productId, $userId, $amount)
{
    $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
    $stmt = $pdo->prepare("UPDATE user_products SET amount = :amount WHERE user_id = :userId and product_id = :productId");
    $stmt->execute(['amount' => $amount, 'userId' => $userId, 'productId' => $productId]);
}

public function getByProductId($productId)
{
    $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :productId");
    $stmt->execute(['productId' => $productId]);
    $data = $stmt->fetch();
    return $data;
}
}