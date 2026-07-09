<?php
session_start();
require 'dbconnect.php';

// Alleen ingelogde klanten
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'client') {
    header("Location: login.php");
    exit;
}

// Mag alleen bereikt worden via het formulier op pur-crud-add.php (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['productid'])) {
    header("Location: pur-crud-add.php");
    exit;
}

$productid = (int) $_POST['productid'];
$quantity  = (int) ($_POST['quantity'] ?? 1);

if ($quantity < 1) {
    $quantity = 1;
}

// Prijs NOOIT uit het formulier vertrouwen: altijd server-side opzoeken.
// Meteen ook controleren dat het product bestaat en nog actief is.
$sql = "SELECT id, price FROM product WHERE id = :id AND isactive = 'J'";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $productid]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    $_SESSION['orderror'] = "Dit product bestaat niet (meer) of is niet actief.";
    header("Location: pur-crud-add.php");
    exit;
}

$price = $product['price'];

// Stap 1: purchase record aanmaken (alleen als er nog geen open bestelling in SESSION staat)
if (!isset($_SESSION['purchaseid'])) {
    $clientid     = $_SESSION['clientid'];
    $purchasedate = date('Y-m-d');

    $sql = "INSERT INTO purchase (clientid, purchasedate, delivered)
            VALUES (:clientid, :purchasedate, 0)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':clientid'     => $clientid,
        ':purchasedate' => $purchasedate
    ]);

    $_SESSION['purchaseid'] = $db->lastInsertId();
}

// Stap 2: purchaseline record aanmaken
$sql = "INSERT INTO purchaseline (purchaseid, productid, price, quantity)
        VALUES (:purchaseid, :productid, :price, :quantity)";
$stmt = $db->prepare($sql);
$stmt->execute([
    ':purchaseid' => $_SESSION['purchaseid'],
    ':productid'  => $productid,
    ':price'      => $price,
    ':quantity'   => $quantity
]);

// Stap 3: bevestiging
$_SESSION['ordermessage'] = "Bestelling is opgeslagen. Je kan een nieuw product aan de bestelling toevoegen.";

header("Location: pur-crud-add.php");
exit;
