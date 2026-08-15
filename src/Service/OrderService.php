<?php

namespace Service;

use DTO\OrderCreateDTO;
use Model\Order;
use Model\OrderProduct;
use Model\UserProducts;
use Service\Auth\AuthInterface;


class OrderService
{
    private Order $orderModel;
    private OrderProduct $orderProductModel;
    private UserProducts $userProductModel;
    private AuthInterface $authService;

    public function __construct(AuthInterface $authService)
    {
        $this->authService = $authService;
        $this->orderModel = new Order();
        $this->orderProductModel = new OrderProduct();
        $this->userProductModel = new UserProducts();
    }

    public function createOrder(OrderCreateDTO $data)
    {

        $user = $this->authService->getCurrentUser();
        $orderId = $this->orderModel->create($data->getContactName(),
            $data->getContactPhone(),
            $data->getComment(),
            $user->getId(),
            $data->getAddress());//создаем сам заказ

        $userProducts = $this->userProductModel->getAllByUserId($user->getId());
        // получаем товары из корзины пользователя



        foreach ($userProducts as $userProduct) {
            $productId = $userProduct->getProductId();
            $amount = $userProduct->getAmount();

            $this->orderProductModel->create($orderId, $productId, $amount);
        } //переносим товары в таблицу заказов

        $this->userProductModel->deleteByUserId($user->getId()); // очищаем корзину пользователя

    }


    public function getUserOrders($userId)
    {

            return $this->orderModel->getOrderWithProductsByUserId($userId);

        }
}
