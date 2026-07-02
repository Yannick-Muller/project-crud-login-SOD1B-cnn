<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: beh-log.php");
    exit;
}

$sql = "SELECT purchase.id AS purchaseid,
               client.last_name,
               purchase.purchasedate,
               purchase.delivered,
               COUNT(purchaseline.id) AS aantalregels,
               SUM(purchaseline.quantity) AS totaal_aantal,
               SUM(purchaseline.price * purchaseline.quantity) AS totaalbedrag
        FROM purchase
        JOIN client ON client.id = purchase.clientid
        LEFT JOIN purchaseline ON purchaseline.purchaseid = purchase.id
        GROUP BY purchase.id, client.last_name, purchase.purchasedate, purchase.delivered
        ORDER BY purchase.id DESC";

$stmt = $db->prepare($sql);
$stmt->execute();
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Overzicht alle aankopen</title>
    <style>
        table { border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 8px 16px; }
        th { background-color: #4a6fa5; color: white; text-align: left; }
        tr:nth-child(even) { background-color: #f0f0f0; }
        td:nth-child(5), td:nth-child(6), td:nth-child(7),
        th:nth-child(5), th:nth-child(6), th:nth-child(7) { text-align: right; }
    </style>
</head>
<body>

<h1>Overzicht alle aankopen</h1>

<table>
    <tr>
        <th>Bestelnummer</th>
        <th>Achternaam klant</th>
        <th>Besteldatum</th>
        <th>Afgeleverd</th>
        <th>Aantal regels</th>
        <th>Totaal aantal producten</th>
        <th>Totaalbedrag</th>
    </tr>

    <?php foreach ($purchases as $p): ?>
        <tr>
            <td><?= $p['purchaseid'] ?></td>
            <td><?= htmlspecialchars($p['last_name']) ?></td>
            <td><?= $p['purchasedate'] ?></td>
            <td><?= $p['delivered'] ? 'Ja' : 'Nee' ?></td>
            <td><?= $p['aantalregels'] ?></td>
            <td><?= $p['totaal_aantal'] ?? 0 ?></td>
            <td>&euro; <?= number_format($p['totaalbedrag'] ?? 0, 2, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
