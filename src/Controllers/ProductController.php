<?php

namespace Controllers;

use Model\Product;
use Model\UserProducts;
class ProductController extends BaseController
{
    private Product $productModel;
    private UserProducts $userProductModel;
    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->userProductModel = new UserProducts();
    }

    public function catalog()
    {

        if ($this->authService->checkSession()) {

//if (!isset($_COOKIE['user_id'])) {
//    header("Location: /login_form.php");
//}
            $products = $this->productModel->getProducts();

            require_once '../Views/catalog.php';
        } else {
            header("Location: /login");
            exit;
        }
    }

    public function addProduct()
    {

        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $errors = $this->validate($_POST);

        $products = $this->productModel->getProducts();

        if (empty($errors)) {
//            $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
            $user = $this->authService->getCurrentUser();
            $productId = (int) $_POST['product_id'];
            $amount = (int) $_POST['amount'];

            $data = $this->userProductModel->getUserProducts($productId, $user->getId());

            if ($data === null) {

                $this->userProductModel->insertUserProducts($user->getId(), $productId, $amount);
            } else {
                $amount = $data->getAmount() + $amount;
                $this->userProductModel->updateUserProductAmount($productId, $user->getId(), $amount);

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

            $product = $this->productModel->getByProductId($productId);

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

