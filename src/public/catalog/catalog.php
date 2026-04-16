<?php

session_start();
if(isset($_SESSION['userId'])) {

//if (!isset($_COOKIE['user_id'])) {
//    header("Location: /login_form.php");
//}

$pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
// если пользователь найденБ выдаем каталог

$stmt = $pdo->query('SELECT * FROM products' );
$products = $stmt->fetchAll();
require_once './catalog/catalog_page.php';
} else {
    header("Location: /login");
}

