<?php

namespace Model;

class Product extends Model
{

    public function getProducts()
    {
        $stmt = $this->PDO->query('SELECT * FROM products');
        $products = $stmt->fetchAll();
        return $products;

    }
public function getByProductId($productId)
{
    $stmt = $this->PDO->prepare("SELECT * FROM products WHERE id = :productId");
    $stmt->execute(['productId' => $productId]);
    $product = $stmt->fetch();
    return $product;
}

    public function getProductId($productId)
    {
        $stmt = $this->PDO->query("SELECT * FROM products WHERE id = {$productId}");
        $product = $stmt->fetch();
        return $product;
    }

}