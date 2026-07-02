<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde klanten
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'klant') {
    header("Location: login.php");
    exit;
}

// Mag alleen bereikt worden vanuit pur-crud-add.php
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['productid'])) {
    header("Location: pur-crud-add.php");
    exit;
}

$productid = $_POST['productid'];
$price     = $_POST['price'];
$quantity  = $_POST['quantity'];

// Stap 1: purchase record aanmaken als die nog niet bestaat
if (!isset($_SESSION['purchaseid'])) {
    $sql = "INSERT INTO purchase (clientid, purchasedate, delivered)
            VALUES (:clientid, :purchasedate, :delivered)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':clientid'     => $_SESSION['userid'],
        ':purchasedate' => date('Y-m-d'),
        ':delivered'    => 0
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
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestelling opgeslagen</title>
</head>
<body>

<h1>Bestelling is opgeslagen</h1>
<p>Je kan een nieuw product aan de bestelling toevoegen.</p>

<a href="pur-crud-add.php">
    <button>Terug naar productoverzicht</button>
</a>

</body>
</html>
