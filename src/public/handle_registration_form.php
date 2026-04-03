<?php

function validateRegistrationForm($data)
{

    $errors = [];

    if (isset($_POST['name'])) {
        $name = $_POST['name'];

        if (strlen($name) < 2) {
            $errors['name'] = 'Слишком короткое имя';
        }
    } else {
        $errors['name'] = "Имя должно быть заполнено";
    }

    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        if (strlen($email) < 2) {
            $errors['email'] = 'email должен быть больше 2 символов';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'email некорректный';
        }
    } else {
        $errors['email'] = "email должен быть заполнен";
    }

    if (isset($_POST['psw'])) {
        $password = $_POST['psw'];

        if (strlen($password) < 4) {
            $errors['psw'] = 'Пароль слишком короткий.Придумайте новый пароль';
        }
    } else {
        $errors['psw'] = 'Пароль должен быть заполнен';
    }

    if (isset($_POST['psw-repeat'])) {
        $passwordRepeat = $_POST['psw-repeat'];

        if ($password != $passwordRepeat) {
            $errors['psw-repeat'] = 'Пароли не совпадают';
        }
    } else {
        $errors['psw-repeat'] = 'Пароль должен быть заполнен';
    }
    return $errors;
}

    if(empty($errors)) {

        $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email'=>$email]);
        if($stmt->rowCount() > 0){
            $errors['email'] = "Пользователь с таким email уже существует";
        } else {

        $password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name'=>$name, 'email'=>$email, 'password'=>$password]);
            echo "Регистрация прошла успешно";
        }
        $data = $stmt->fetch();
        print_r($data);
    }

        require_once './registration_form.php';

    ?>












