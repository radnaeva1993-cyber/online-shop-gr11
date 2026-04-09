<?php

function loginValidate($data)
{
    $errors = [];

    if (isset($data['email'])) {
        $email = $data['email'];

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'email некорректный';
        }
    } else {
        $errors['email'] = "email должен быть заполнен";
    }

    if (isset($data['password'])) {
        $password = $data['password'];

    } else {

        $errors['password'] = 'Пароль должен быть заполнен';

        return $errors;
    }
}

    $data = $_POST;
    $errors = loginValidate($data);

    if(empty($errors)) {

    $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $data['email']]);
    $user = $stmt->fetch();

    if($user) {
        if($user && password_verify($data['password'], $user['password'])) {
            echo "Успешный вход";
        } else {
        $errors['login'] = 'Неверный пароль';
    }
    } else {
        $errors['login'] = 'Пользователь с таким email не существует';
    }
    }

require_once './login_form.php';

?>