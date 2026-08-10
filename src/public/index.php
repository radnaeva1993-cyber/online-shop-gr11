<?php

use Controllers\UserController;
use Controllers\ProductController;
use Controllers\OrderController;
use Controllers\CartController;

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
$app->post('/registration', UserController::class ,'registrate', \Request\RegistrateRequest::class );

$app->get('/login',  UserController::class , 'getLogin');
$app->post('/login', UserController::class , 'login', \Request\LoginRequest::class );

$app->get('/catalog', ProductController::class , 'catalog');
$app->post('/increase-amount', ProductController::class,'increaseAmount', \Request\AddProductRequest::class );
$app->post('/decrease-amount', ProductController::class,'decreaseAmount', \Request\AddProductRequest::class );

$app->get('/profile',  UserController::class , 'profile');
$app->post('/profile', UserController::class , 'getProfile');

$app->get('/edit-profile', UserController::class , 'getEditProfile');
$app->post('/edit-profile', UserController::class , 'editProfile', \Request\EditProfileRequest::class );

$app->get('/cart', CartController::class , 'cart');


$app->get('/create-order',  OrderController::class , 'getCheckoutForm');
$app->post('/create-order', OrderController::class , 'handleCheckout', \Request\OrderRequest::class );

$app->get('/user-orders', OrderController::class , 'getAllOrders');

$app->post('/increaseAmount', CartController::class, 'increaseAmount', \Request\AddProductRequest::class );
$app->post('/decreaseAmount', CartController::class, 'decreaseAmount', \Request\AddProductRequest::class );

$app->get('/reviews', ProductController::class , 'getReviews');
$app->post('/reviews', ProductController::class , 'addReview', \Request\ReviewRequest::class );


$app->run();
