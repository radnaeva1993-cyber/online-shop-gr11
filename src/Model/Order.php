<?php

namespace Model;

class Order extends Model
{

    private int $id;
    private string $contactName;
    private string $contactPhone;
    private string $comment;
    private int $userId;
    private string $address;
    public array $orderProducts = [];
    public int $total = 0;

    protected function getTableName(): string
    {
        return 'orders';
    }
public function create( $contactName, $contactPhone, $comment, $userId, $address)
{
    $stmt = $this->PDO->prepare("INSERT INTO {$this->getTableName()} (contact_name, contact_phone, comment, user_id, address ) VALUES (:contactName, :contactPhone, :comment, :user_id, :address) RETURNING id");
    $stmt->execute(['contactName' => $contactName, 'contactPhone' => $contactPhone,  'comment' => $comment, 'user_id' => $userId , 'address' => $address]);
    $result = $stmt->fetch();
    return $result['id'];
}

public function getAllByUserId($userId)
{
 $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()}  WHERE user_id = :userId");
 $stmt->execute(['userId' => $userId]);
 $data = $stmt->fetchAll();

 $orders = [];
 foreach ($data as $order) {
     $obj = new self;
     $obj->id = $order['id'];
     $obj->contactName = $order['contact_name'];
     $obj->contactPhone = $order['contact_phone'];
     $obj->comment = $order['comment'];
     $obj->userId = $order['user_id'];
     $obj->address = $order['address'];

     $orders[] = $obj;
 }
    return $orders;

}

    public function getContactName(): string
    {
        return $this->contactName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getContactPhone(): string
    {
        return $this->contactPhone;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getComment(): string
    {
        return $this->comment;
    }


}