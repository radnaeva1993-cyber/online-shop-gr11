<?php

print_r($_GET);

$name = $_GET['name'];
$email = $_GET['email'];
$password = $_GET['psw'];
$passwordRepeat = $_GET['psw-repeat'];

function IsValidData($name, $email, $password, $passwordRepeat)
{
    $flag = true;

    if((strlen($name)<=3)) {
        $flag = false;
        echo 'Недопустимая длина имени' . "\n";
    }

    if((strlen($email)<=5)) {
        $flag = false;
        echo 'Недопустимая длина почты' . "\n";
    }

    if((strlen($password)<=6)) {

        $flag = false;
        echo 'Пароль слишком короткий.Придумайте новый пароль' . "\n";
    }

    if($password != $passwordRepeat) {

        $flag = false;
        echo 'Пароли не совпадают' . "\n";
    }

    if($flag===true) {
        $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');

        $pdo->exec("INSERT INTO users (name, email, password) VALUES ('name', 'email', 'password')");

        $result = $pdo->query('SELECT * FROM users order by id desc limit 1');

        $data = $result->fetch();

        print_r($data);
    }
}

IsValidData($name, $email, $password, $passwordRepeat);






