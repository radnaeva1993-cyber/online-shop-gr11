<?php

namespace Model;

class Product extends Model
{

    private int $id;
    private string $name;
    private string $description;
    private int $price;
    private string $image_url;
    public int $amount = 0;

    protected function getTableName(): string
    {
        return 'products';
    }
    public function getProducts(): array|null
    {
        $stmt = $this->PDO->query("SELECT * FROM {$this->getTableName()}");
        $products = $stmt->fetchAll();

        if($products === false){
            return null;
        }

        $data = [];
        foreach ($products as $product) {

            $obj = new self;
            $obj->id = $product['id'];
            $obj->name = $product['name'];
            $obj->description = (string) $product['description'];
            $obj->price = $product['price'];
            $obj->image_url = (string) $product['image_url'];


            $data[] = $obj;
        }
        return $data;

    }
public function getByProductId($productId):self|null
{
    $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE id = :productId");
    $stmt->execute(['productId' => $productId]);
    $product = $stmt->fetch();

    if($product === false){
        return null;
    }

    $obj = new self;
    $obj->id = $product['id'];
    $obj->name = $product['name'];
    $obj->description = (string) $product['description'];
    $obj->price = $product['price'];
    $obj->image_url = $product['image_url'];

    return $obj;
}

    public function getOneById($productId):self|null
    {
        $stmt = $this->PDO->query("SELECT * FROM {$this->getTableName()} WHERE id = {$productId}");
        $product = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($product === false){
            return null;
        }

        $obj = new self;
        $obj->id = $product['id'];
        $obj->name = $product['name'];
        $obj->description = (string) $product['description'];
        $obj->price = $product['price'];
        $obj->image_url = $product['image_url'];

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription():string
    {
        return $this->description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getImageUrl(): string
    {
        return $this->image_url;
    }
}



