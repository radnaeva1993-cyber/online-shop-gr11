<?php

$pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');

$pdo->exec("INSERT INTO users (name, email, password) VALUES ('dolgor', 'dolgor@gmail.com', '12345678')");

$statement = $pdo->query('SELECT * FROM users');
$data = $statement->fetch();
echo '<pre>';
print_r($data);
