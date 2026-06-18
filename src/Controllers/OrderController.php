<?php

namespace Controllers;

use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProducts;
use Service\OrderService;

class OrderController extends BaseController
{

    private Order $orderModel;
    private UserProducts $userProductModel;
    private OrderProduct $orderProductModel;
    private Product $productModel;
    private OrderService $orderService;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new Order();
        $this->userProductModel = new UserProducts();
        $this->orderProductModel = new OrderProduct();
        $this->productModel = new Product();
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

    public function handleCheckout()
    {

        if ($this->authService->check()) {
            header("Location: /login");
            exit;
        }

        $errors = $this->validate($_POST);

        if (empty($errors)) {
            $contactName = $_POST['contact_name'];
            $contactPhone = $_POST['contact_phone'];
            $comment = $_POST['comment'];
            $userId = $_SESSION['userId'];
            $address = $_POST['address'];

            $this->orderService->createOrder($contactName, $contactPhone, $comment, $userId, $address);

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

