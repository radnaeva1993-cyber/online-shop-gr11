<?php

namespace Service;

use Model\UserProducts;
use Model\Product;


class CartService
{

    private UserProducts $userProductModel;
    private Product $productModel;
    private LoggerService $logger;

    public function __construct(UserProducts $userProductModel, Product $productModel)
    {
        $this->userProductModel = $userProductModel;
        $this->productModel = $productModel;
        $this->logger = new LoggerService();
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

        if ($totalPrice > 0 && $totalPrice < 1000) {
            $exception = new \Exception( "Минимальная сумма заказа 1000 рублей. Сейчас у вас $totalPrice рублей." );
            $this->logger->error($exception);
            throw $exception;
        }

        return $totalPrice;

    }
}