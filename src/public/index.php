<?php

$autoloadCore = function(string $className)
{
    $path = "../Core/{$className}.php";
    if (file_exists($path)) {
        require_once $path;
        return true;
    }
        return false;
};

$autoloadController = function(string $className)
{
    $path = "../Controllers/{$className}.php";
    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
};

$autoloadModel = function(string $className)
{
    $path = "../Model/{$className}.php";
    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
};

$autoload = function(string $className)
{
$path = str_replace("\\", "/", $className);
$path = $path . ".php";
$path = './../' . $path;

    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
};



spl_autoload_register($autoload);
spl_autoload_register($autoloadController);


$app = new Core\App();
$app->run();