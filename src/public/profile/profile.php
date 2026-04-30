<?php

session_start();

if (isset($_SESSION['userId'])) {
    $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
    $stmt = $pdo->query("SELECT * FROM users WHERE id = " . $_SESSION['userId']);
    $user = $stmt->fetch();
    require_once './profile/profile_page.php';

} else {
    header('Location: /login');
}