<?php

namespace Request;

class RegistrateRequest
{

    public function __construct(private array $data)
    {}
public function getPassword(): string {

        return $this->data['psw'];
}

public function getName(): string
{
return $this->data['name'];
}

public function getEmail(): string
{
return $this->data['email'];
}

public function getPasswordRepeat(): string
{
    return $this->data['psw-repeat'];
}

    public function validate()
    {

        $errors = [];

        if (isset($this->data['name'])) {
            $name = $this->data['name'];

            if (strlen($name) < 2) {
                $errors['name'] = 'Слишком короткое имя';
            }
        } else {
            $errors['name'] = "Имя должно быть заполнено";
        }

        if (isset($this->data['email'])) {
            $email = $this->data['email'];

            if (strlen($email) < 2) {
                $errors['email'] = 'email должен быть больше 2 символов';
            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $errors['email'] = 'email некорректный';
            }
        } else {
            $errors['email'] = "email должен быть заполнен";
        }

        if (isset($this->data['psw'])) {
            $password = $this->data['psw'];

            if (strlen($password) < 4) {
                $errors['psw'] = 'Пароль слишком короткий.Придумайте новый пароль';
            }
        } else {
            $errors['psw'] = 'Пароль должен быть заполнен';
        }

        if (isset($this->data['psw-repeat'])) {
            $passwordRepeat = $this->data['psw-repeat'];

            if ($password != $passwordRepeat) {
                $errors['psw-repeat'] = 'Пароли не совпадают';
            }
        } else {
            $errors['psw-repeat'] = 'Пароль должен быть заполнен';
        }
        return $errors;
    }
}