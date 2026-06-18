<?php

namespace Controllers;

use Model\Product;
use Model\UserProducts;
use Service\CartService;

class CartController extends BaseController
{

    private Product $productModel;
    private UserProducts $userProductModel;
    private CartService $cartService;


    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->userProductModel = new UserProducts();
        $this->cartService = new CartService();

    }

    public function cart()
    {
        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $user = $this->authService->getCurrentUser();
        $userProducts = $this->userProductModel->getAllProductsByUserId($user->getId());

        $products = [];
        foreach ($userProducts as $userProduct) {
            $productId = $userProduct->getProductId();


            $product = $this->productModel->getOneById($productId);
            $product->amount = $userProduct->getAmount();
            $products[] = $product;

        }

        require_once '../Views/cart.php';
    }

    public function increaseAmount()
    {
        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $user = $this->authService->getCurrentUser();
        $productId = (int)$_POST['product_id'];

        $this->cartService->increaseAmount($productId, $user->getId());

        header("Location: /cart");
        exit();
    }

    public function decreaseAmount()
    {
        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $user = $this->authService->getCurrentUser();
        $productId = (int)$_POST['product_id'];

        $this->cartService->decreaseAmount($productId, $user->getId());
        header("Location: /cart");
        exit();
    }


}

