<?php
class ProductController
{
    public function catalog()
    {
        session_start();
        if (isset($_SESSION['userId'])) {

//if (!isset($_COOKIE['user_id'])) {
//    header("Location: /login_form.php");
//}

            require_once '../Model/Product.php';
            $userModel = new Product();
            $products = $userModel->getProducts();

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

        if (empty($errors)) {
            $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
            $userId = $_SESSION['userId'];
            $productId = $_POST['product_id'];
            $amount = $_POST['amount'];

            require_once '../Model/Product.php';
            $userModel = new Product();
            $data = $userModel->getUserProductsByProductIdUserId($productId,$userId);

            if ($data === false) {

                $userModel->insertIntoUserProducts($userId, $productId, $amount);
            } else {
                $amount = $data['amount'] + $amount;
                $userModel->getUserProductByAmount($productId, $userId, $amount);

            }
            header("Location: /catalog");
            exit();
        }

    }

    private function validate($data)
    {
        $errors = [];

        if (isset($data['product_id'])) {

            $productId = (int)$data['product_id'];

            require_once '../Model/Product.php';
            $userModel = new Product();
            $result = $userModel->getByProductId($productId);

            if ($data === false) {
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