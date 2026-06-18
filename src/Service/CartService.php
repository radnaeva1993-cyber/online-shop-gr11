<?php

namespace Service;

use Model\UserProducts;

class CartService
{

    private UserProducts $userProductModel;

    public function __construct()
    {
        $this->userProductModel = new UserProducts();
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
}