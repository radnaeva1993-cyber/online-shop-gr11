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

    public function getOrderWithProductsByUserId($userId)
    {
        $sql = "SELECT o.id AS order_id,
        o.contact_name,
        o.contact_phone,
        o.comment,
        o.user_id,
        o.address,
        op.id AS order_product_id,
        op.amount,
        p.id AS product_id,
        p.name AS product_name,
        p.price,
        p.image_url,
        (op.amount * p.price) AS total_sum
        FROM orders o INNER JOIN order_products op ON o.id = op.order_id
        INNER JOIN products p ON op.product_id = p.id
        WHERE o.user_id = :userId
        ORDER BY o.id DESC, op.id ASC";

        $stmt = $this->PDO->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        $rows = $stmt->fetchAll();

        if (empty($rows)) {
            return [];
        }
        $orders = [];
        foreach ($rows as $row) {
            $orderId = $row['order_id'];
            if (!isset($orders[$orderId])) {
                $order = new Order();
                $order->id = $row['order_id'];
                $order->contactName = $row['contact_name'];
                $order->contactPhone = $row['contact_phone'];
                $order->comment = $row['comment'];
                $order->userId = $row['user_id'];
                $order->address  = $row['address'];
                $order->orderProducts = [];
                $order->total = 0;

                $orders[$orderId] = $order;
            }
            // БАГ: раньше товар заказа собирался как обычный array (['name' => ..., 'price' => ...]),
            // а вьюха src/Views/user_orders_form.php обращается к товару как к объекту
            // ($orderProduct->name, $orderProduct->getAmount(), ...). Из-за этого страница
            // "Мои заказы" падала с фатальной ошибкой "Call to a member function on array"
            // при любом заказе. Исправили: собираем настоящий объект OrderProduct с теми же
            // публичными полями/геттерами, которые уже ждала вьюха.
            $orderProduct = new OrderProduct();
            $orderProduct->name = $row['product_name'];
            $orderProduct->price = (int) $row['price'];
            $orderProduct->totalSum = (int) $row['total_sum'];
            $orderProduct->setAmount((int) $row['amount']);

            $orders[$orderId]->orderProducts[] = $orderProduct;

            $orders[$orderId]->total += $row['total_sum'];
        }
        return array_values($orders);
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