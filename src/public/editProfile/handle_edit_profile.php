<?php

session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: /login');
    exit;
}

$newName     = $_POST['name'];
$newEmail    = $_POST['email'];
$newPassword = $_POST['password'];

if ($newName === '' || $newEmail === '') {
    header('Location: /edit-profile');
    exit;
}

$pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');

if ($newPassword !== '') {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        UPDATE users
        SET name = :name, email = :email, password = :password
        WHERE id = :id
    ");
    $stmt->execute([
        'name'     => $newName,
        'email'    => $newEmail,
        'password' => $hashedPassword,
        'id'       => $_SESSION['userId'],
    ]);
} else {
    $stmt = $pdo->prepare("
        UPDATE users
        SET name = :name, email = :email
        WHERE id = :id
    ");
    $stmt->execute([
        'name'  => $newName,
        'email' => $newEmail,
        'id'    => $_SESSION['userId'],
    ]);
}

header('Location: /profile');
exit;