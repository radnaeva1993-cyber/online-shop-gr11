<?php

namespace Request;

class LoginRequest
{
    public function __construct(private array $data)
    {}

    public function getPassword(): string
    {
        return $this->data['password'];
    }

    public function getEmail(): string
    {
        return $this->data['email'];
    }

    public function validate()
    {
        $errors = [];

        if (empty($this->data['email'])) {

            $errors['email'] = "email должен быть заполнен";
        }

        if (empty($this->data['password'])) {

            $errors['password'] = 'Пароль должен быть заполнен';
        }
        return $errors;

    }
}