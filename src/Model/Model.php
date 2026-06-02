<?php
namespace Model;
use PDO;
class Model
{
    protected PDO $PDO;
    public function __construct()
    {
        $this->PDO = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');
    }
}