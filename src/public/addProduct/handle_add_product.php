<?php

if(session_status() !== PHP_SESSION_ACTIVE){
    session_start();
}

if (!isset($_SESSION['userId'])) {
    header("Location: /login");
    exit();
}

function validate(array $data)
{

    $errors = [];

    if(isset($data['product_id'])) {

        $productId = (int) $data['product_id'];

        $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :$productId");
        $stmt->execute(['product_id' => $productId]);
        $data = $stmt->fetch();

            if ($data === false) {
                $errors['product_id'] = 'Продукт не найден';
            }
        } else {
            $errors['product_id'] = 'id продукта доолжен быть указан';
        }

    if(isset($data['amount'])) {

    }
        return $errors;
    }

    $errors = validate($_POST);

if(empty($errors)) {
    $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
    $userId = $_SESSION['userId'];
    $productId = $_POST['product_id'];
    $amount = $_POST['amount'];

    $stmt = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:userID, :productId, :amount)");
    $stmt->execute(['userId'=>$userId, 'productId'=>$productId, 'amount'=>$amount]);

}

