<?php


$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

//регистрация
if ($requestUri === '/registration') {

    require_once '../Controllers/UserController.php';
    $user = new UserController();

    if ($requestMethod === 'GET') {
        $user->getRegistrate();
    } elseif ($requestMethod === 'POST') {
        $user->registrate();
    } else {
        echo "HTTP метод $requestMethod не работает";
    }

    //логин
} elseif ($requestUri === '/login') {
    require_once '../Controllers/UserController.php';
    $user = new UserController();
    if ($requestMethod === 'GET') {
        $user->getLogin();
    } elseif ($requestMethod === 'POST') {
        $user->login();
    } else {
        echo "HTTP метод $requestMethod не работает";
    }

     //каталог
} elseif ($requestUri === '/catalog') {
    require_once '../Controllers/ProductController.php';
    $product = new ProductController();

    if ($requestMethod === 'GET') {
        $product->catalog();
    }elseif ($requestMethod === 'POST') {
        $product->addProduct();

    } else {
        echo "HTTP метод $requestMethod не работает";
    }

    // выдача профиля
} elseif ($requestUri === '/profile') {
    require_once '../Controllers/UserController.php';
    $user = new UserController();
    if ($requestMethod === 'POST') {
        $user->getProfile();
    } elseif ($requestMethod === 'GET') {
        $user->profile();
    } else {
        echo "HTTP метод $requestMethod не работает";
    }

    // изменение профиля
} elseif ($requestUri === '/edit-profile') {
    require_once '../Controllers/UserController.php';
    $user = new UserController();
    if ($requestMethod === 'GET') {
        $user->getEditProfile();
    } elseif ($requestMethod === 'POST') {
        $user->editProfile();
    } else {
        echo "HTTP метод $requestMethod не работает";
    }

} elseif ($requestUri === '/cart') {
    if ($requestMethod === 'POST') {
        require_once '../cart/cart_page.php';
    } elseif ($requestMethod === 'GET') {
        require_once './cart/cart.php';
    } else {
        echo "HTTP метод $requestMethod не работает";
    }

} else {
    http_response_code(404);
    require_once './404.php';
}
