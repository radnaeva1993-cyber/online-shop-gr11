<?php

namespace Service;

use Model\UserProducts;
use Model\Product;


class CartService
{

    private UserProducts $userProductModel;
    private Product $productModel;

    public function __construct(UserProducts $userProductModel, Product $productModel)
    {
        $this->userProductModel = $userProductModel;
        $this->productModel = $productModel;
        // LoggerService раньше создавался здесь только для того, чтобы залогировать
        // исключение "меньше 1000 руб.", которое мы убрали из getSum() (см. ниже).
        // Больше в этом сервисе логирование не нужно — убрали неиспользуемую зависимость.
    }

    public function increaseAmount(int $productId, int $userId)
    {

        $userProduct = $this->userProductModel->getUserProducts($productId, $userId);

        if ($userProduct !== null) {
            $newAmount = $userProduct->getAmount() + 1;
            $this->userProductModel->updateUserProductAmount($productId, $userId, $newAmount);
        } else {
            $this->userProductModel->insertUserProducts($userId, $productId, 1);
        }

    }

    public function decreaseAmount(int $productId, int $userId)
    {
        $userProduct = $this->userProductModel->getUserProducts($productId, $userId);

        if ($userProduct !== null && $userProduct->getAmount() > 1) {
            $newAmount = $userProduct->getAmount() - 1;
            $this->userProductModel->updateUserProductAmount($productId, $userId, $newAmount);
        }
    }

    public function getSum(int $userId): float
    {
        $userProducts = $this->userProductModel->getAllProductsByUserId($userId);
        $totalPrice = 0;
        foreach ($userProducts as $userProduct) {
            $productId = $userProduct->getProductId();
            $product = $this->productModel->getOneById($productId);

            if ($product !== null) {
                $totalPrice += $product->getPrice() * $userProduct->getAmount();
            }
        }

        // БАГ: раньше здесь проверялась минимальная сумма заказа (1000 руб.) и при её
        // нарушении бросалось исключение. Но getSum() вызывается и из CartController::cart()
        // просто чтобы ПОКАЗАТЬ корзину — из-за этого нельзя было даже открыть страницу
        // корзины, если в ней лежало меньше 1000 руб. (App::run() ловит исключение и
        // показывает 404). При этом на самом оформлении заказа (OrderService::createOrder)
        // эта сумма вообще не проверялась, поэтому оформить заказ на 1 рубль было можно.
        // Правило "минимум 1000 руб." — это бизнес-правило именно ОФОРМЛЕНИЯ заказа, а не
        // просмотра корзины, поэтому его перенесли в OrderController::handleCheckout()
        // (см. комментарий там). Здесь getSum() теперь просто считает сумму и ничего не решает.
        return $totalPrice;
    }
}