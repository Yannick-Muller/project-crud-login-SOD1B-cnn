<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde klanten
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'klant') {
    header("Location: login.php");
    exit;
}

$sql = "SELECT purchase.id AS purchaseid,
               purchase.purchasedate,
               purchase.delivered,
               product.productname,
               purchaseline.price,
               purchaseline.quantity
        FROM purchase
        JOIN purchaseline ON purchaseline.purchaseid = purchase.id
        JOIN product ON product.id = purchaseline.productid
        WHERE purchase.clientid = :clientid
        ORDER BY purchase.id DESC";

$stmt = $db->prepare($sql);
$stmt->execute([':clientid' => $_SESSION['userid']]);
$lines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mijn bestellingen</title>
</head>
<body>

<h1>Mijn bestellingen</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>Bestelnummer</th>
        <th>Besteldatum</th>
        <th>Afgeleverd</th>
        <th>Productnaam</th>
        <th>Prijs</th>
        <th>Aantal</th>
    </tr>

    <?php foreach ($lines as $l): ?>
        <tr>
            <td><?= $l['purchaseid'] ?></td>
            <td><?= $l['purchasedate'] ?></td>
            <td><?= $l['delivered'] ? 'Ja' : 'Nee' ?></td>
            <td><?= $l['productname'] ?></td>
            <td><?= $l['price'] ?></td>
            <td><?= $l['quantity'] ?></td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
