<?php

function loginValidate($data)
{
    $errors = [];

    if (empty($data['email'])) {

        $errors['email'] = "email должен быть заполнен";
    }

    if (empty($data['password'])) {

        $errors['password'] = 'Пароль должен быть заполнен';
    }
    return $errors;
}

    $errors = loginValidate($_POST);

    if(empty($errors)) {
        $email = $_POST['email'];
        $password = $_POST['password'];

    $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if($user === false){
        $errors['login'] = 'Пользователь или пароль неверный';
    } else {
        $passwordDb = $user['password'];

        if(password_verify($password, $passwordDb)) {
            session_start();
            $_SESSION['userId'] = $user['id'];
            header('Location: catalog');

        } else {
            $errors['login'] = 'Пользователь или пароль неверный';
        }
    }

    }

    require_once './login/login_form.php';

?>