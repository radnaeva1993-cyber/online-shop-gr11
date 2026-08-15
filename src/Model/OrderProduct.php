<?php

namespace Model;

class OrderProduct extends Model
{

    private int $orderId;
    private int $productId;
    private int $amount;
    private $id;

    public string $name = '';

    public int $price = 0;

    public int $totalSum = 0;


    protected function getTableName(): string
    {
        return 'order_products';
    }

    public function create(int $orderId, int $productId, int $amount)
    {
        $stmt = $this->PDO->prepare("INSERT INTO {$this->getTableName()}(order_id, product_id,amount) VALUES (:orderId, :productId, :amount)");
        $stmt->execute(['orderId' => $orderId, 'productId' => $productId, 'amount' => $amount]);
    }

    public function getAllByOrderId(int $orderId)
    {
        $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE order_id = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        $orderProducts = $stmt->fetchAll();

        $data = [];
        foreach ($orderProducts as $orderProduct) {

            $obj = new self;
            $obj->id = $orderProduct['id'];
            $obj->orderId = $orderProduct['order_id'];
            $obj->productId = $orderProduct['product_id'];
            $obj->amount = $orderProduct['amount'];
            $data[] = $obj;

        }
        return $data;
    }


    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

}