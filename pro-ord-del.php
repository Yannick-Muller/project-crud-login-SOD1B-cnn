<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: beh-log.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Geen bestelregel geselecteerd.";
    exit;
}

$sql = "SELECT purchaseline.id, product.productname, purchaseline.quantity,
               purchase.id AS purchaseid, client.last_name
        FROM purchaseline
        JOIN product ON product.id = purchaseline.productid
        JOIN purchase ON purchase.id = purchaseline.purchaseid
        JOIN client ON client.id = purchase.clientid
        WHERE purchaseline.id = :id";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $id]);
$line = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$line) {
    echo "Bestelregel niet gevonden.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestelregel verwijderen</title>
</head>
<body>

<h1>Bestelregel verwijderen</h1>

<p>
    Weet u zeker dat u <strong><?= $line['quantity'] ?>x <?= htmlspecialchars($line['productname']) ?></strong>
    bij bestelling #<?= $line['purchaseid'] ?> (<?= htmlspecialchars($line['last_name']) ?>) wilt verwijderen?
</p>

<a href="pro-ord-delete.php?id=<?= $line['id'] ?>">
    <button>Ja, verwijderen</button>
</a>
<a href="pro-ord-get.php">
    <button>Breek af</button>
</a>

</body>
</html>
