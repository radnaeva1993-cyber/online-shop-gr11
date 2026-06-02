<?php

namespace Model;
class UserProducts extends Model
{
    public function getUserProducts($productId, $userId)//getUserProducts
    {
        $stmt = $this->PDO->prepare("SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $data = $stmt->fetch();
        return $data;
    }

    public function insertUserProducts($userId, $productId, $amount)
    {
        $userId = $_SESSION['userId'];
        $stmt = $this->PDO->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:userId, :productId, :amount)");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'amount' => $amount]);
    }

    public function getUserProductByAmount($productId, $userId, $amount)
    {

        $stmt = $this->PDO->prepare("UPDATE user_products SET amount = :amount WHERE user_id = :userId and product_id = :productId");
        $stmt->execute(['amount' => $amount, 'userId' => $userId, 'productId' => $productId]);
    }

    public function getByUserId($userId)
    {

        $stmt = $this->PDO->query("SELECT * FROM user_products WHERE user_id = {$userId}");
        $userProducts = $stmt->fetchAll();
        return $userProducts;
    }


}