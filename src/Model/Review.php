<?php

namespace Model;

class Review extends Model
{

    private int $id;
    private int $productId;
    private int $userId;
    private string $comment;
    private string $createdAt;
protected function getTableName(): string
{
    return "reviews";
}

public function getAllByProductId($productId): array|null
{
    $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE product_id = :product_id");
    $stmt->execute(['product_id' => $productId]);
    $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    if ($products === false) {
        return null;
    }

    $result = [];
    foreach ($products as $reviewData) {

        $obj = new self;
        $obj->id = $reviewData['id'];
        $obj->productId = $reviewData['product_id'];
        $obj->userId = $reviewData['user_id'];
        $obj->comment = $reviewData['comment'];
        $obj->createdAt = (string) $reviewData['created_at'];

        $result[] = $obj;
    }
    return $result;
}

public function create($productId, $userId, $comment)
{
    $stmt = $this->PDO->prepare("INSERT INTO {$this->getTableName()} (product_id, user_id, comment) VALUES (:product_id, :user_id, :comment)");
    $stmt -> execute([
        'product_id' => $productId,
        'user_id' => $userId,
        'comment' => $comment
    ]);
}

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }



}