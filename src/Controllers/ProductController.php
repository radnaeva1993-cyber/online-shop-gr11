<?php

namespace Controllers;

use Model\Product;
use Model\UserProducts;
class ProductController
{
    private Product $userModel;
    private UserProducts $userProductModel;
    public function __construct()
    {
        $this->userModel = new Product();
        $this->userProductModel = new UserProducts();
    }

    public function catalog()
    {
        session_start();
        if (isset($_SESSION['userId'])) {

//if (!isset($_COOKIE['user_id'])) {
//    header("Location: /login_form.php");
//}
            $products = $this->userModel->getProducts();

            require_once '../Views/catalog.php';
        } else {
            header("Location: /login");
            exit;
        }
    }

    public function addProduct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit();
        }

        $errors = $this->validate($_POST);

        $products = $this->userModel->getProducts();

        if (empty($errors)) {
//            $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
            $userId = $_SESSION['userId'];
            $productId = $_POST['product_id'];
            $amount = $_POST['amount'];

            $data = $this->userProductModel->getUserProducts($productId, $userId);

            if ($data === false) {

                $this->userProductModel->insertUserProducts($userId, $productId, $amount);
            } else {
                $amount = $data['amount'] + $amount;
                $this->userProductModel->getUserProductByAmount($productId, $userId, $amount);

            }
            header("Location: /catalog");
            exit();
        }
        require_once '../Views/catalog.php';

    }

    private function validate($data)
    {
        $errors = [];

        if (isset($data['product_id'])) {

            $productId = (int)$data['product_id'];

            $product = $this->userModel->getByProductId($productId);

            if ($product === false) {
                $errors['product_id'] = 'Продукт не найден';
            }
        } else {
            $errors['product_id'] = 'id продукта должен быть указан';
        }

        if (isset($data['amount'])) {
            $amount = (int)$data['amount'];
            if ($amount < 0) {
                $errors['amount'] = 'Количество не может быть меньше 0';
            }
        }
        return $errors;
    }
}

