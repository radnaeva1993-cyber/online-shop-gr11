<?php

namespace Service;

use Model\Error;

class LoggerService
{
    public static function error(\Throwable $exception): void
    {
        $errorModel = new Error();
        $errorModel->create(
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
        );
    }
}