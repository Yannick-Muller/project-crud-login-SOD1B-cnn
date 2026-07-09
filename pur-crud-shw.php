<?php
session_start();
require 'dbconnect.php';

// Alleen ingelogde klanten
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'client') {
    header("Location: login.php");
    exit;
}

$clientid = $_SESSION['clientid'];

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
$stmt->execute([':clientid' => $clientid]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mijn bestellingen</title>
</head>
<body>

<?php require 'menu.php'; ?>

<h1>Mijn bestellingen</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>Bestelnummer</th>
        <th>Besteldatum</th>
        <th>Afgeleverd</th>
        <th>Productnaam</th>
        <th>Prijs (€)</th>
        <th>Aantal</th>
    </tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['purchaseid']) ?></td>
            <td><?= htmlspecialchars($r['purchasedate']) ?></td>
            <td><?= $r['delivered'] ? 'Ja' : 'Nee' ?></td>
            <td><?= htmlspecialchars($r['productname']) ?></td>
            <td><?= htmlspecialchars(number_format($r['price'], 2, ',', '.')) ?></td>
            <td><?= htmlspecialchars($r['quantity']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
