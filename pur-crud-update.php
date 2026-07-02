<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde klanten
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'klant') {
    header("Location: login.php");
    exit;
}

// Mag alleen bereikt worden vanuit pur-crud-upd01.php
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['lineid'])) {
    header("Location: pur-crud-upd.php");
    exit;
}

$lineids    = $_POST['lineid'];
$quantities = $_POST['quantity'];

$sql = "UPDATE purchaseline
        SET quantity = :quantity
        WHERE id = :lineid";
$stmt = $db->prepare($sql);

foreach ($lineids as $i => $lineid) {
    $stmt->execute([
        ':quantity' => $quantities[$i],
        ':lineid'   => $lineid
    ]);
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestelling gewijzigd</title>
</head>
<body>

<h1>Je wijziging is opgeslagen</h1>

<a href="pur-crud-upd.php">
    <button>Terug naar mijn bestellingen</button>
</a>

</body>
</html>
