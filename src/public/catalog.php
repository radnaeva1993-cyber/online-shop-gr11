<?php

$pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');

$stmt = $pdo->query('SELECT * FROM products' );
$products = $stmt->fetchAll();

require_once './catalog_page.php';

