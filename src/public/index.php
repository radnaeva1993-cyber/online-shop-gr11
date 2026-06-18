<?php

use Controllers\UserController;
use Controllers\ProductController;
use Controllers\OrderController;
use Controllers\CartController;
use Core\Autoloader;
require_once './../Core/Autoloader.php';

$path = dirname(__DIR__);
\Core\Autoloader::register($path);



//$autoloadCore = function(string $className)
//{
//    $path = "../Core/{$className}.php";
//    if (file_exists($path)) {
//        require_once $path;
//        return true;
//    }
//        return false;
//};
//
//$autoloadController = function(string $className)
//{
//    $path = "../Controllers/{$className}.php";
//    if (file_exists($path)) {
//        require_once $path;
//        return true;
//    }
//    return false;
//};
//
//$autoloadModel = function(string $className)
//{
//    $path = "../Model/{$className}.php";
//    if (file_exists($path)) {
//        require_once $path;
//        return true;
//    }
//    return false;
//};
//
//
//spl_autoload_register($autoloadController);


$app = new Core\App();
$app->get('/registration', UserController::class , 'getRegistrate');
$app->post('/registration', UserController::class ,'registrate');

$app->get('/login',  UserController::class , 'getLogin');
$app->post('/login', UserController::class , 'login');

$app->get('/catalog', ProductController::class , 'catalog');
$app->post('/catalog', ProductController::class,'addProduct');

$app->get('/profile',  UserController::class , 'profile');
$app->post('/profile', UserController::class , 'getProfile');

$app->get('/edit-profile', UserController::class , 'getEditProfile');
$app->post('/edit-profile', UserController::class , 'editProfile');

$app->get('/cart', CartController::class , 'cart');


$app->get('/create-order',  OrderController::class , 'getCheckoutForm');
$app->post('/create-order', OrderController::class , 'handleCheckout');

$app->get('/user-orders', OrderController::class , 'getAllOrders');

$app->post('/increaseAmount', CartController::class, 'increaseAmount');
$app->post('/decreaseAmount', CartController::class, 'decreaseAmount');

$app->run();
