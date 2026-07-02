<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: beh-log.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: pro-ord-add.php");
    exit;
}

$purchaseid = $_POST['purchaseid'];
$productid  = $_POST['productid'];
$price      = $_POST['price'];
$quantity   = $_POST['quantity'];

$sql = "INSERT INTO purchaseline (purchaseid, productid, price, quantity)
        VALUES (:purchaseid, :productid, :price, :quantity)";
$stmt = $db->prepare($sql);
$stmt->execute([
    ':purchaseid' => $purchaseid,
    ':productid'  => $productid,
    ':price'      => $price,
    ':quantity'   => $quantity
]);

header("Location: pro-ord-get.php");
exit;
