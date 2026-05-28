<?php
require 'dbconnect.php';

$sql = "SELECT * FROM product WHERE isactive = 'N'";
$stmt = $db->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inactieve producten</title>
</head>
<body>
<h1>Inactieve producten</h1>
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th><th>Naam</th><th>Prijs</th><th>Status</th>
    </tr>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['id']) ?></td>
            <td><?= htmlspecialchars($p['productname']) ?></td>
            <td><?= htmlspecialchars($p['price']) ?></td>
            <td><?= htmlspecialchars($p['isactive']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>