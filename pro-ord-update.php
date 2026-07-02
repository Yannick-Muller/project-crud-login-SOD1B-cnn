<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: beh-log.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: pro-ord-get.php");
    exit;
}

$id        = $_POST['id'];
$productid = $_POST['productid'];
$price     = $_POST['price'];
$quantity  = $_POST['quantity'];

$sql = "UPDATE purchaseline
        SET productid = :productid,
            price = :price,
            quantity = :quantity
        WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->execute([
    ':productid' => $productid,
    ':price'     => $price,
    ':quantity'  => $quantity,
    ':id'        => $id
]);

header("Location: pro-ord-get.php");
exit;
