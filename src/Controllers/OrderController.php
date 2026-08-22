<?php

namespace Controllers;

use DTO\OrderCreateDTO;

use Request\OrderRequest;
use Service\OrderService;
use Service\CartService;
use Model\Product;
use Model\UserProducts;

class OrderController extends BaseController
{

    private OrderService $orderService;
    private CartService $cartService;

    public function __construct()
    {
        parent::__construct();

        $this->orderService = new OrderService();
        $productModel = new Product();
        $userProductModel = new UserProducts();
        $this->cartService = new CartService($userProductModel, $productModel);

    }

    public function getCheckoutForm()
    {

        if ($this->authService->check()) {
            header("Location: /login");
            exit;
        }

        require_once './../Views/order_form.php';

    }

    public function handleCheckout(OrderRequest $request)
    {

        if ($this->authService->check()) {
            header("Location: /login");
            exit;
        }

        $errors = $request->validate();
        $user = $this->authService->getCurrentUser();

        if ($user === null){
           header("Location: /login");
           exit;
        }

        $userId = $user->getId();

        if  (empty($errors)) {
        $totalPrice = $this->cartService->getSum($userId);
            if ($totalPrice < 1000) {
                $errors['cart'] = "Минимальная сумма заказа 1000 рублей. Сейчас у вас $totalPrice рублей.";
            }
        }

        if (empty($errors)) {

            $dto = new OrderCreateDTO($request->getContactName(),
                $request->getContactPhone(),
                $request->getComment(),
                $request->getAddress(), $user->getId());

            $this->orderService->createOrder($dto);

            header('Location: catalog');
            exit;

        } else {

            require_once './../Views/order_form.php';
        }
    }

    public function getAllOrders()
    {

        if ($this->authService->check()) {
            header("Location: /login");
            exit;
        }

        $user = $this->authService->getCurrentUser();

        $newUserOrders = $this->orderService->getUserOrders($user->getId());

        require './../Views/user_orders_form.php';
    }
}

