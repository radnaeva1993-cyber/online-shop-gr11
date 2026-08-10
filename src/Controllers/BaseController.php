<?php

namespace Controllers;
use Service\Auth\AuthInterface;
use Service\AuthCookieService;
use Service\AuthSessionService;

abstract class BaseController
{
    protected AuthInterface $authService;

    public function __construct()
    {
        $this->authService = new AuthSessionService();
    }
}

