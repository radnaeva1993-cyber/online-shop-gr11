<?php

namespace Service;

use DTO\OrderCreateDTO;
use Model\Order;
use Model\OrderProduct;
use Model\UserProducts;



class OrderService
{
    private Order $orderModel;
    private OrderProduct $orderProductModel;
    private UserProducts $userProductModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->orderProductModel = new OrderProduct();
        $this->userProductModel = new UserProducts();
    }

    public function createOrder(OrderCreateDTO $data)
    {

        $userId = $data->getUserId();

        $orderId = $this->orderModel->create($data->getContactName(),
            $data->getContactPhone(),
            $data->getComment(),
            $userId,
            $data->getAddress());//создаем сам заказ

        $userProducts = $this->userProductModel->getAllByUserId($userId);
        // получаем товары из корзины пользователя

        foreach ($userProducts as $userProduct) {
            $productId = $userProduct->getProductId();
            $amount = $userProduct->getAmount();

            $this->orderProductModel->create($orderId, $productId, $amount);
        } //переносим товары в таблицу заказов

        $this->userProductModel->deleteByUserId($userId); // очищаем корзину пользователя

    }


    public function getUserOrders($userId)
    {

            return $this->orderModel->getOrderWithProductsByUserId($userId);

        }
}
