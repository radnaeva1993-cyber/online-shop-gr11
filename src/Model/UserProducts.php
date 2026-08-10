<?php

namespace Model;
class UserProducts extends Model
{

    private int $id;
    private int $userId;
    private int $productId;
    private int $amount;


    protected function getTableName(): string
    {
        return 'user_products';
    }
    public function getUserProducts( $productId, $userId): self|null
    {
        $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $product = $stmt->fetch();

        if($product === false){
            return null;
        }
        $obj =  new self();
        $obj->id = $product['id'];
        $obj->userId = $product['user_id'];
        $obj->productId = $product['product_id'];
        $obj->amount = $product['amount'];

        return $obj;
    }

    public function insertUserProducts($userId, $productId, $amount)
    {
        $stmt = $this->PDO->prepare("INSERT INTO {$this->getTableName()} (user_id, product_id, amount) VALUES (:userId, :productId, :amount)");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'amount' => $amount]);
    }

    public function updateUserProductAmount($productId, $userId, $amount)
    {

        $stmt = $this->PDO->prepare("UPDATE {$this->getTableName()} SET amount = :amount WHERE user_id = :userId and product_id = :productId");
        $stmt->execute(['amount' => $amount, 'userId' => $userId, 'productId' => $productId]);
    }

    public function getAllProductsByUserId($userId):array|null
    {

        $stmt = $this->PDO->query("SELECT * FROM {$this->getTableName()} WHERE user_id = {$userId}");
        $userProducts = $stmt->fetchAll();

        if($userProducts === false){
            return null;
        }

        $data = [];
        foreach($userProducts as $userProduct){
            $obj = new self();
            $obj->id = $userProduct['id'];
            $obj->userId = $userProduct['user_id'];
            $obj->productId = $userProduct['product_id'];
            $obj->amount = $userProduct['amount'];
            $data[] = $obj;
        }
        return $data;
    }

    public function getAllByUserId($userId): array|null
    {
        $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :userId");
        $stmt->execute([':userId' => $userId]);
        $userProducts = $stmt->fetchAll();

        $result = [];

        foreach($userProducts as $userProduct){
            $obj = new self();
            $obj->id = $userProduct['id'];
            $obj->userId = $userProduct['user_id'];
            $obj->productId = $userProduct['product_id'];
            $obj->amount = $userProduct['amount'];
            $result[] = $obj;
        }
        return $result;
    }

    public function deleteByUserId($userId)
    {
        $stmt = $this->PDO->prepare("DELETE FROM {$this->getTableName()} WHERE user_id = :userId");
        $stmt->execute([':userId' => $userId]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }






}