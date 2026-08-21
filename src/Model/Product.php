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

    public function getOneById($productId):self|null
    {
        // БАГ: $productId подставлялся прямо в SQL-строку через query() вместо
        // параметризованного запроса. ProductController::getReviews() передавал сюда
        // сырой $_GET['product_id'] без каста — это классическая SQL-инъекция
        // (GET /reviews?product_id=1 OR 1=1 и т.п.). Переписали на prepare()/execute()
        // с именованным параметром — так значение экранируется драйвером PDO, а не
        // склеивается руками.
        $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE id = :id");
        $stmt->execute(['id' => $productId]);
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



