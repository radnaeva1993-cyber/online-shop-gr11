<?php

namespace Controllers;

use DTO\OrderCreateDTO;

use Service\OrderService;

class OrderController extends BaseController
{

    private OrderService $orderService;

    public function __construct()
    {
        parent::__construct();

        $this->orderService = new OrderService();


    }

    public function getCheckoutForm()
    {

        if ($this->authService->check()) {
            header("Location: /login");
            exit;
        }

        require_once './../Views/order_form.php';

    }

    public function handleCheckout(array $data)
    {

        if ($this->authService->check()) {
            header("Location: /login");
            exit;
        }

        $errors = $this->validate($data);
        $user = $_SESSION['userId'];

        if (empty($errors)) {

            $dto = new OrderCreateDTO($data['contact_name'],
                $data['contact_phone'],
                $data['comment'],  $user,
                $data['address']);

            $this->orderService->createOrder($dto);

            header('Location: catalog');
            exit;

        } else {

            require_once './../Views/order_form.php';
        }
    }

    private function validate($data)
    {
        $errors = [];

        if (isset($data['contact_name'])) {
            $name = $data['contact_name'];

            if (empty($name)) {

                $errors['contact_name'] = "Имя должно быть заполнено";
            }
        }

        if (isset($data['contact_phone'])) {
            $contactPhone = $data['contact_phone'];


            if (!preg_match('/^[0-9]{11}$/', $contactPhone)) {
                $errors['contact_phone'] = 'номер телефона должен содержать только цифры и быть длиной больше 10 символов';
            }
        }

        if (isset($data['address'])) {
            $address = $data['address'];

            if (empty($address)) {
                $errors['address'] = 'Адрес должен быть заполнен';
            }

            if (strlen($address) < 5) {
                $errors['address'] = 'Слишком короткий адрес';
            }

        }

        return $errors;
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

