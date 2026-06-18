<?php

namespace Service;

use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProducts;

class OrderService
{
    private Order $orderModel;
    private OrderProduct $orderProductModel;
    private UserProducts $userProductModel;
    private Product $productModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->orderProductModel = new OrderProduct();
        $this->userProductModel = new UserProducts();
        $this->productModel = new Product();
    }

    public function createOrder($contactName, $contactPhone, $comment, $userId, $address)
    {
        $orderId = $this->orderModel->create($contactName, $contactPhone, $comment, $userId, $address);//создаем сам заказ
        $userProducts = $this->userProductModel->getAllByUserId($userId); // получаем товары из корзины пользователя

        foreach ($userProducts as $userProduct) {
            $productId = $userProduct->getProductId();
            $amount = $userProduct->getAmount();

            $this->orderProductModel->create($orderId, $productId, $amount);
        } //переносим товары в таблицу заказов

        $this->userProductModel->deleteByUserId($userId); // очищаем корзину пользователя

    }

    public function getUserOrders($userId)
    {
        $userOrders = $this->orderModel->getAllByUserId($userId);//получаем список всех заказов

        $resultOrders = [];

        foreach ($userOrders as $userOrder) {
            $orderId = $userOrder->getId();

            $orderProducts = $this->orderProductModel->getAllByOrderId($orderId);

            $newOrderProducts = [];
            $orderTotal = 0;
            foreach ($orderProducts as $orderProduct) {//проходимся по каждому товару внутри заказа

                $productId = $orderProduct->getProductId();
                $product = $this->productModel->getOneById($productId);
                if ($product !== null) {//если товап найден в бд,берем данные
                    $orderProduct->name = $product->getName();
                    $orderProduct->price = $product->getPrice();
                    $orderProduct->totalSum = $orderProduct->getAmount() * $product->getPrice();

                    if (!isset($orderTotal)) {
                        $orderTotal = 0;
                    }

                    $orderTotal += $orderProduct->totalSum;

                    $newOrderProducts[] = $orderProduct;
                }
            }
            $userOrder->total = $orderTotal;

            $userOrder->orderProducts = $newOrderProducts;

            $resultOrders[] = $userOrder;
        }
            return $resultOrders;

        }
}
