<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde klanten
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'klant') {
    header("Location: login.php");
    exit;
}

// Mag alleen bereikt worden vanuit pur-crud-upd.php
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['lineid'])) {
    header("Location: pur-crud-upd.php");
    exit;
}

$lineids   = $_POST['lineid'];
$quantities = $_POST['quantity'];

// Productnamen ophalen zodat de klant weet wat hij wijzigt
$sql = "SELECT purchaseline.id AS lineid, product.productname
        FROM purchaseline
        JOIN product ON product.id = purchaseline.productid
        WHERE purchaseline.id = :lineid";
$stmt = $db->prepare($sql);

$rows = [];
foreach ($lineids as $i => $lineid) {
    $stmt->execute([':lineid' => $lineid]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $rows[] = [
        'lineid'      => $lineid,
        'productname' => $row['productname'],
        'quantity'    => $quantities[$i]
    ];
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wijziging controleren</title>
</head>
<body>

<h1>Controleer je wijziging</h1>

<form method="post" action="pur-crud-update.php">
    <table border="1" cellpadding="8">
        <tr>
            <th>Productnaam</th>
            <th>Nieuw aantal</th>
        </tr>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= $r['productname'] ?></td>
                <td>
                    <?= $r['quantity'] ?>
                    <input type="hidden" name="lineid[]" value="<?= $r['lineid'] ?>">
                    <input type="hidden" name="quantity[]" value="<?= $r['quantity'] ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <button type="submit">Bevestigen</button>
</form>

<a href="pur-crud-upd.php">
    <button>Annuleren</button>
</a>

</body>
</html>
