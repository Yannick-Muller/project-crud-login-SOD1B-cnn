<?php
session_start();
require 'dbconnect.php';

// Alleen ingelogde klanten
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'client') {
    header("Location: login.php");
    exit;
}

$clientid = $_SESSION['clientid'];

// Alleen eigen bestellingen die nog niet afgeleverd zijn
$sql = "SELECT purchaseline.id AS lineid,
               purchase.id AS purchaseid,
               purchase.purchasedate,
               product.productname,
               purchaseline.quantity
        FROM purchase
        JOIN purchaseline ON purchaseline.purchaseid = purchase.id
        JOIN product ON product.id = purchaseline.productid
        WHERE purchase.clientid = :clientid
          AND purchase.delivered = 0
        ORDER BY purchase.id DESC";
$stmt = $db->prepare($sql);
$stmt->execute([':clientid' => $clientid]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestelling wijzigen</title>
</head>
<body>

<?php require 'menu.php'; ?>

<h1>Mijn openstaande bestellingen wijzigen</h1>

<?php if (isset($_SESSION['ordermessage'])): ?>
    <p><strong><?= htmlspecialchars($_SESSION['ordermessage']) ?></strong></p>
    <?php unset($_SESSION['ordermessage']); ?>
<?php endif; ?>

<?php if (empty($rows)): ?>
    <p>Je hebt geen openstaande bestellingen om te wijzigen.</p>
<?php else: ?>
    <form method="post" action="pur-crud-upd01.php">
    <table border="1" cellpadding="8">
        <tr>
            <th>Bestelnummer</th>
            <th>Besteldatum</th>
            <th>Productnaam</th>
            <th>Aantal</th>
        </tr>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['purchaseid']) ?></td>
                <td><?= htmlspecialchars($r['purchasedate']) ?></td>
                <td><?= htmlspecialchars($r['productname']) ?></td>
                <td>
                    <input type="number" min="1"
                           name="quantity[<?= $r['lineid'] ?>]"
                           value="<?= htmlspecialchars($r['quantity']) ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <button type="submit">Opslaan</button>
    </form>
<?php endif; ?>

</body>
</html>
