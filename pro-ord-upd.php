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

$stmt = $db->prepare("SELECT * FROM purchaseline WHERE id = :id");
$stmt->execute([':id' => $id]);
$line = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$line) {
    echo "Bestelregel niet gevonden.";
    exit;
}

$stmt = $db->prepare("SELECT id, productname, price FROM product ORDER BY productname");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestelregel wijzigen</title>
</head>
<body>

<h1>Bestelregel wijzigen</h1>

<form method="post" action="pro-ord-update.php">
    <input type="hidden" name="id" value="<?= $line['id'] ?>">

    <label>Product:</label><br>
    <select name="productid" required>
        <?php foreach ($products as $p): ?>
            <option value="<?= $p['id'] ?>" <?= $p['id'] == $line['productid'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['productname']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Prijs:</label><br>
    <input type="number" step="0.01" name="price" value="<?= $line['price'] ?>" required><br><br>

    <label>Aantal:</label><br>
    <input type="number" name="quantity" min="1" value="<?= $line['quantity'] ?>" required><br><br>

    <button type="submit">Opslaan</button>
</form>

<a href="pro-ord-get.php">
    <button>Annuleren</button>
</a>

</body>
</html>
