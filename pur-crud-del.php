<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: login.php");
    exit;
}

$sql = "SELECT purchase.id AS purchaseid,
               client.last_name,
               purchase.purchasedate,
               purchaseline.id AS lineid,
               product.productname,
               purchaseline.quantity
        FROM purchase
        JOIN client ON client.id = purchase.clientid
        JOIN purchaseline ON purchaseline.purchaseid = purchase.id
        JOIN product ON product.id = purchaseline.productid
        WHERE purchase.delivered = 0
        ORDER BY purchase.id DESC";

$stmt = $db->prepare($sql);
$stmt->execute();
$lines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestellingen beheren</title>
</head>
<body>

<h1>Nog niet afgeleverde bestellingen</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>Bestelnummer</th>
        <th>Achternaam klant</th>
        <th>Besteldatum</th>
        <th>Regel ID</th>
        <th>Productnaam</th>
        <th>Aantal</th>
        <th>Acties</th>
    </tr>

    <?php foreach ($lines as $l): ?>
        <tr>
            <td><?= $l['purchaseid'] ?></td>
            <td><?= $l['last_name'] ?></td>
            <td><?= $l['purchasedate'] ?></td>
            <td><?= $l['lineid'] ?></td>
            <td><?= $l['productname'] ?></td>
            <td><?= $l['quantity'] ?></td>
            <td>
                <a href="pur-crud-delete.php?type=line&id=<?= $l['lineid'] ?>">
                    <button>Regel</button>
                </a>
                <a href="pur-crud-delete.php?type=purchase&id=<?= $l['purchaseid'] ?>">
                    <button>Aankoop</button>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
