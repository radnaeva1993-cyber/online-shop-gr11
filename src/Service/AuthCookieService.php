<?php

namespace Service;

use Model\User;
use Service\Auth\AuthInterface;

class AuthCookieService implements AuthInterface
{
    protected User $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }
    public function check(): bool
    {

        return !isset($_COOKIE['userId']);

    }

    public function checkSession(): bool
    {
        return isset($_COOKIE['userId']);
    }
    public function getCurrentUser(): ?User
    {
        if($this->checkSession())
        {
            $userId = $_COOKIE['userId'];
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

                setcookie('userId', $user->getId(), time() + (86400 * 30), "/");

                return true;
            } else {
                return false;
            }
        }
    }

    public function logout()
    {
       setcookie('userId', null, time() - (86400 * 30), "/");
    }



}