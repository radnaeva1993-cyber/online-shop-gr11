<?php

namespace Service;

use Model\User;
use Service\Auth\AuthInterface;

class AuthSessionService implements AuthInterface
{
    protected User $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }
    public function check(): bool
    {

        $this->startSession();
        return !isset($_SESSION['userId']);

    }

    public function checkSession(): bool
    {
        $this->startSession();
        return isset($_SESSION['userId']);
    }

    public function getCurrentUser(): ?User
    {
        $this->startSession();
        if($this->checkSession())
        {
            $userId = $_SESSION['userId'];
            return $this->userModel->getById($userId);
        } else {
            return null;
        }
    }

    public function auth(string $email, string $password): bool
    {
        $user = $this->userModel->getByEmail($email);// getByEmail озвращает объект,а не массив

        if ($user === null) {
            return false;
        } else {
            $passwordDb = $user->getPassword();

            if (password_verify($password, $passwordDb)) {
                $this->startSession();
                $_SESSION['userId'] = $user->getId();
                return true;
            } else {
                return false;
            }
        }
    }

    public function logout()
    {
        $this->startSession();
        session_destroy();
    }


    public function startSession()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}