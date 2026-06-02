<?php

namespace Controllers;

use Model\Product;
use Model\UserProducts;
class CartController
{

    private Product $userModel;
    private UserProducts $userProductModel;
    public function __construct()
    {
        $this->userModel = new Product();
        $this->userProductModel = new UserProducts();
    }
    public function cart()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit();
        }

        $userId = $_SESSION['userId'];

        $userProducts = $this->userProductModel->getByUserId($userId);

        $products = [];
        foreach ($userProducts as $userProduct) {
            $productId = $userProduct['product_id'];

            $product = $this->userModel->getProductId($productId);
            $product['amount'] = $userProduct['amount'];
            $products[] = $product;

        }

        require_once '../Views/cart.php';
    }
}