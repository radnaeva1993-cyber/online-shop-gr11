<?php

namespace Core;

use Request\AddProductRequest;
use Request\EditProfileRequest;
use Request\LoginRequest;
use Request\OrderRequest;
use Request\RegistrateRequest;
use Request\ReviewRequest;
use Service\CartService;

class App
{

    private array $routes = [];

    public function run()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestUri])) {
            $routeMethods = $this->routes[$requestUri];
            if (isset($routeMethods[$requestMethod])) {

                $handler = $routeMethods[$requestMethod];

                $class = $handler['class'];
                $method = $handler['method'];

                $controller = new $class();

                $requestClass = $handler['request'];
                if( $requestClass !== null){
                   $request =  new $requestClass($_POST);
                   $controller->$method($request);
                } else {
                    $controller->$method();
                }

                $controller->$method();

            } else {
                echo "$requestMethod не поддерживается для $requestUri";
            }
        } else {
            http_response_code(404);
            require_once '../Views/404.php';
        }
    }

//    public function addRoute(string $route, string $routeMethod, string $className, string $method)
//    {
//        $this->routes[$route][$routeMethod] = [
//            'class' => $className,
//            'method' => $method,
//            ];
//    }

    public function get(string $route, string $className, string $method, string $requestClass = null)
    {
        $this->routes[$route]['GET'] = [
            'class' => $className,
            'method' => $method,
            'request' => $requestClass
        ];
    }
    public function post(string $route, string $className, string $method, string $requestClass = null)
    {
        $this->routes[$route]['POST'] = [
            'class' => $className,
            'method' => $method,
            'request' => $requestClass,
        ];
    }

    public function put(string $route, string $className, string $method)
    {
        $this->routes[$route]['PUT'] = [
            'class' => $className,
            'method' => $method,
        ];
    }

    public function delete(string $route, string $className, string $method)
    {
        $this->routes[$route]['DELETE'] = [
            'class' => $className,
            'method' => $method,
        ];
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






