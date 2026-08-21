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

    // БАГ: раньше сервису передавался AuthInterface, и createOrder() заново лез в него
    // за текущим пользователем (лишний запрос к БД), хотя тот же самый userId уже
    // приходил в OrderController от request'а. Причина, по которой authService вообще
    // понадобился — DTO не хранил userId (см. DTO\OrderCreateDTO). Теперь DTO носит
    // userId сам, поэтому зависимость от AuthInterface здесь больше не нужна.
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
