<?php

namespace Controllers;

use DTO\OrderCreateDTO;
use Model\Product;
use Model\UserProducts;
use Request\OrderRequest;
use Service\CartService;
use Service\OrderService;

class OrderController extends BaseController
{

    private OrderService $orderService;
    private CartService $cartService;

    public function __construct()
    {
        parent::__construct();

        // OrderService больше не принимает authService — см. комментарий в OrderService.
        $this->orderService = new OrderService();

        // CartService понадобился здесь, чтобы посчитать сумму корзины перед оформлением
        // заказа (см. handleCheckout ниже) — раньше эта проверка ошибочно жила в
        // CartController::cart() и мешала просто ОТКРЫТЬ корзину.
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

        // БАГ: минимальная сумма заказа (1000 руб.) раньше проверялась внутри
        // CartService::getSum() и падала исключением уже при просмотре /cart, а на самом
        // оформлении заказа вообще не проверялась — можно было оформить заказ на 1 руб.
        // Это неправильное место: проверять сумму нужно именно здесь, перед созданием
        // заказа, и показывать ошибку так же, как остальные ошибки валидации формы,
        // а не 404-страницей.
        if (empty($errors)) {
            $totalPrice = $this->cartService->getSum($userId);

            if ($totalPrice < 1000) {
                $errors['cart'] = "Минимальная сумма заказа 1000 рублей. Сейчас у вас $totalPrice рублей.";
            }
        }

        if (empty($errors)) {

            $dto = new OrderCreateDTO($request->getContactName(),
                $request->getContactPhone(),
                $request->getComment(),
                $request->getAddress(), $userId);

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

