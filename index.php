<?php

$conn = pg_connect("host=db dbname=mydb user=dolgor password='12345678'");

if ($conn) {
   echo "Connected to PostgreSQL successfully!";
} else {
   echo "Connection failed!";
}

?>


