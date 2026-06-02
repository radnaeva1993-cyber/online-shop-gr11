<?php

namespace Core;

use Controllers\CartController;
use Controllers\ProductController;
use Controllers\UserController;

class App
{

    private array $routes = [
        '/registration' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'registrate',
            ],
        ],
        '/login' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'login',
            ],

        ],
        '/catalog' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'catalog',
            ],
            'POST' => [
                'class' => ProductController::class,
                'method' => 'addProduct',
            ],
        ],
        '/profile' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'profile',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'getProfile',
            ],
        ],
        '/edit-profile' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getEditProfile',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'editProfile',
            ],
        ],
        '/cart' => [
            'GET' => [
                'class' => CartController::class,
                'method' => 'cart',
            ],

        ]
    ];


    public function run()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestUri])) {
            $routeMethods = $this->routes[$requestUri];
            if (isset($routeMethods[$requestMethod])) {

                $handler = $routeMethods[$requestMethod];

                $class = $handler['class'];
                $method = $handler['method'];

                $controller = new $class();
                $controller->$method();

            } else {
                echo "$requestMethod не поддерживается для $requestUri";
            }
        } else {
            http_response_code(404);
            require_once '../Views/404.php';
        }
    }
}


//        $routeMethods = [
//            'GET' => [
//                'class' => 'UserController',
//                'method' => 'getRegistrate',
//            ],
//            'POST' => [
//                'class' => 'UserController',
//                'method' => 'registrate',
//            ],
//        ];



//        $handler = [
//             'class' => 'UserController',
////                'method' => 'getRegistrate',
//        ];






