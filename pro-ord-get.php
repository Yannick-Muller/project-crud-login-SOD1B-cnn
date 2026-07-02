<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: beh-log.php");
    exit;
}

$sql = "SELECT purchaseline.id AS lineid,
               product.productname,
               purchase.id AS purchaseid,
               purchase.purchasedate,
               client.last_name,
               purchaseline.price,
               purchaseline.quantity
        FROM purchaseline
        JOIN product ON product.id = purchaseline.productid
        JOIN purchase ON purchase.id = purchaseline.purchaseid
        JOIN client ON client.id = purchase.clientid
        ORDER BY product.productname, purchase.id";

$stmt = $db->prepare($sql);
$stmt->execute();
$lines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Producten met bestellingen</title>
</head>
<body>

<h1>Overzicht producten met bestellingen</h1>

<a href="pro-ord-add.php">
    <button>Bestelregel toevoegen</button>
</a>

<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>Regel ID</th>
        <th>Productnaam</th>
        <th>Bestelnummer</th>
        <th>Besteldatum</th>
        <th>Achternaam klant</th>
        <th>Prijs</th>
        <th>Aantal</th>
        <th>Acties</th>
    </tr>

    <?php foreach ($lines as $l): ?>
        <tr>
            <td><?= $l['lineid'] ?></td>
            <td><?= htmlspecialchars($l['productname']) ?></td>
            <td><?= $l['purchaseid'] ?></td>
            <td><?= $l['purchasedate'] ?></td>
            <td><?= htmlspecialchars($l['last_name']) ?></td>
            <td>&euro; <?= number_format($l['price'], 2, ',', '.') ?></td>
            <td><?= $l['quantity'] ?></td>
            <td>
                <a href="pro-ord-upd.php?id=<?= $l['lineid'] ?>">
                    <button>Wijzigen</button>
                </a>
                <a href="pro-ord-del.php?id=<?= $l['lineid'] ?>">
                    <button>Verwijderen</button>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
