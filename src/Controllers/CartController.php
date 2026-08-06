<?php

namespace Controllers;

use Model\Product;
use Model\UserProducts;
use Request\AddProductRequest;
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

    public function increaseAmount(AddProductRequest $request):int
    {
        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $user = $this->authService->getCurrentUser();
        $productId =  $request->getProductId();

        $this->cartService->increaseAmount($productId, $user->getId());

        header("Location: /cart");
        exit();
    }

    public function decreaseAmount(AddProductRequest $request)
    {
        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $user = $this->authService->getCurrentUser();
        $productId = $request->getProductId();

        $this->cartService->decreaseAmount($productId, $user->getId());
        header("Location: /cart");
        exit();
    }

}

