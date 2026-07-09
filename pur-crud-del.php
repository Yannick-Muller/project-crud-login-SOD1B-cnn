<?php
session_start();
require 'dbconnect.php';

// Alleen ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Alleen bestellingen die nog niet afgeleverd zijn
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
        ORDER BY purchase.id";
$stmt = $db->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestellingen beheren</title>
</head>
<body>

<?php require 'menu.php'; ?>

<h1>Openstaande bestellingen</h1>

<?php if (isset($_SESSION['delmessage'])): ?>
    <p><strong><?= htmlspecialchars($_SESSION['delmessage']) ?></strong></p>
    <?php unset($_SESSION['delmessage']); ?>
<?php endif; ?>

<table border="1" cellpadding="8">
    <tr>
        <th>Bestelnr.</th>
        <th>Klant</th>
        <th>Besteldatum</th>
        <th>Regel ID</th>
        <th>Productnaam</th>
        <th>Aantal</th>
        <th>Acties</th>
    </tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['purchaseid']) ?></td>
            <td><?= htmlspecialchars($r['last_name']) ?></td>
            <td><?= htmlspecialchars($r['purchasedate']) ?></td>
            <td><?= htmlspecialchars($r['lineid']) ?></td>
            <td><?= htmlspecialchars($r['productname']) ?></td>
            <td><?= htmlspecialchars($r['quantity']) ?></td>
            <td>
                <form method="post" action="pur-crud-delete.php" style="display:inline">
                    <input type="hidden" name="lineid" value="<?= $r['lineid'] ?>">
                    <input type="hidden" name="purchaseid" value="<?= $r['purchaseid'] ?>">
                    <button type="submit" name="action" value="regel">Regel</button>
                </form>
                <form method="post" action="pur-crud-delete.php" style="display:inline">
                    <input type="hidden" name="purchaseid" value="<?= $r['purchaseid'] ?>">
                    <button type="submit" name="action" value="aankoop">Aankoop</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
