<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde klanten
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'klant') {
    header("Location: login.php");
    exit;
}

// Alleen eigen bestellingen die nog niet afgeleverd zijn
$sql = "SELECT purchase.id AS purchaseid,
               purchase.purchasedate,
               purchaseline.id AS lineid,
               product.productname,
               purchaseline.quantity
        FROM purchase
        JOIN purchaseline ON purchaseline.purchaseid = purchase.id
        JOIN product ON product.id = purchaseline.productid
        WHERE purchase.clientid = :clientid
          AND purchase.delivered = 0
        ORDER BY purchase.id DESC";

$stmt = $db->prepare($sql);
$stmt->execute([':clientid' => $_SESSION['userid']]);
$lines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestelling wijzigen</title>
</head>
<body>

<h1>Bestelling wijzigen</h1>

<?php if (empty($lines)): ?>
    <p>Je hebt geen openstaande (nog niet afgeleverde) bestellingen om te wijzigen.</p>
<?php else: ?>

<form method="post" action="pur-crud-upd01.php">
    <table border="1" cellpadding="8">
        <tr>
            <th>Bestelnummer</th>
            <th>Besteldatum</th>
            <th>Productnaam</th>
            <th>Aantal</th>
        </tr>

        <?php foreach ($lines as $l): ?>
            <tr>
                <td><?= $l['purchaseid'] ?></td>
                <td><?= $l['purchasedate'] ?></td>
                <td><?= $l['productname'] ?></td>
                <td>
                    <input type="hidden" name="lineid[]" value="<?= $l['lineid'] ?>">
                    <input type="number" name="quantity[]" min="1" value="<?= $l['quantity'] ?>" required>
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
