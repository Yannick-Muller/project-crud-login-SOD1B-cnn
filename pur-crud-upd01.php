<?php
session_start();
require 'dbconnect.php';

// Alleen ingelogde klanten
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'client') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['quantity'])) {
    header("Location: pur-crud-upd.php");
    exit;
}

$clientid   = (int) $_SESSION['clientid'];
$quantities = $_POST['quantity']; // [lineid => nieuw aantal]
$lineids    = array_map('intval', array_keys($quantities));

if (empty($lineids)) {
    header("Location: pur-crud-upd.php");
    exit;
}

$placeholders = implode(',', array_fill(0, count($lineids), '?'));

$sql = "SELECT purchaseline.id AS lineid,
               purchase.id AS purchaseid,
               purchase.clientid,
               purchase.delivered,
               product.productname
        FROM purchaseline
        JOIN purchase ON purchase.id = purchaseline.purchaseid
        JOIN product ON product.id = purchaseline.productid
        WHERE purchaseline.id IN ($placeholders)";
$stmt = $db->prepare($sql);
$stmt->execute($lineids);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Beveiliging: alleen eigen, nog niet afgeleverde regels mogen gewijzigd worden
$safeRows = [];
foreach ($rows as $r) {
    if ($r['clientid'] == $clientid && $r['delivered'] == 0) {
        $newquantity = (int) $quantities[$r['lineid']];
        if ($newquantity < 1) {
            $newquantity = 1;
        }
        $r['newquantity'] = $newquantity;
        $safeRows[] = $r;
    }
}

// Bewaren voor de opslagstap (pur-crud-update.php)
$_SESSION['pendingupdate'] = $safeRows;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Controle wijziging</title>
</head>
<body>

<?php require 'menu.php'; ?>

<h1>Controleer je wijziging</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>Bestelnummer</th>
        <th>Productnaam</th>
        <th>Nieuw aantal</th>
    </tr>
    <?php foreach ($safeRows as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['purchaseid']) ?></td>
            <td><?= htmlspecialchars($r['productname']) ?></td>
            <td><?= htmlspecialchars($r['newquantity']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<form method="post" action="pur-crud-update.php">
    <button type="submit">Bevestigen</button>
</form>
<a href="pur-crud-upd.php"><button type="button">Annuleren</button></a>

</body>
</html>
