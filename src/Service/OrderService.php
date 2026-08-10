<?php

namespace Service;

use DTO\OrderCreateDTO;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProducts;
use Service\Auth\AuthInterface;


class OrderService
{
    private Order $orderModel;
    private OrderProduct $orderProductModel;
    private UserProducts $userProductModel;
    private Product $productModel;
    private AuthInterface $authService;

    public function __construct(AuthInterface $authService)
    {
        $this->authService = $authService;
        $this->orderModel = new Order();
        $this->orderProductModel = new OrderProduct();
        $this->userProductModel = new UserProducts();
        $this->productModel = new Product();
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
