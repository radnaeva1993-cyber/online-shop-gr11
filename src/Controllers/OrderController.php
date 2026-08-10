<?php

namespace Controllers;

use DTO\OrderCreateDTO;

use Request\OrderRequest;
use Service\OrderService;

class OrderController extends BaseController
{

    private OrderService $orderService;

    public function __construct()
    {
        parent::__construct();

        $this->orderService = new OrderService($this->authService);


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

