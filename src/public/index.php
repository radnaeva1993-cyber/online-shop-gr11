<?php

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

//регистрация
if ($requestUri === '/registration') {
    if ($requestMethod === 'GET') {
        require_once './registration/registration_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './registration/handle_registration_form.php';
    } else {
        echo "HTTP метод $requestMethod не работает";
    }

    //логин
} elseif ($requestUri === '/login') {
    if ($requestMethod === 'GET') {
        require_once './login/login_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './login/handle_login.php';
    } else {
        echo "HTTP метод $requestMethod не работает";
    }

     //каталог
} elseif ($requestUri === '/catalog') {
    if ($requestMethod === 'POST') {
        require_once './catalog/catalog_page.php';
    } elseif ($requestMethod === 'GET') {
        require_once './catalog/catalog.php';
    } else {
        echo "HTTP метод $requestMethod не работает";
    }

    // выдача профиля
} elseif ($requestUri === '/profile') {
    if ($requestMethod === 'POST') {
        require_once './profile/profile_page.php';
    } elseif ($requestMethod === 'GET') {
        require_once './profile/profile.php';
    } else {
        echo "HTTP метод $requestMethod не работает";
    }

    // изменение профиля
} elseif ($requestUri === '/edit-profile') {
    if ($requestMethod === 'GET') {
        require_once './editProfile/edit_profile_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './editProfile/handle_edit_profile.php';
    } else {
        echo "HTTP метод $requestMethod не работает";
    }
} elseif ($requestUri === '/Add-product') {
    if ($requestMethod === 'GET') {
        require_once './addProduct/add_product_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './addProduct/handle_add_product.php';
    } else {
        echo "HTTP метод $requestMethod не работает";
    }
} elseif ($requestUri === '/cart') {
    if ($requestMethod === 'POST') {
        require_once './cart/cart_page.php';
    } elseif ($requestMethod === 'GET') {
        require_once './cart/cart.php';
    } else {
        echo "HTTP метод $requestMethod не работает";
    }

} else {
    http_response_code(404);
    require_once './404.php';
}
