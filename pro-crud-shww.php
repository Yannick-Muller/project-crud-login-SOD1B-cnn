<?php
require 'dbconnect.php';

$sql = "SELECT 
            p.id,
            p.productname,
            p.allergens,
            c.name AS categoryname,
            p.price,
            p.isactive
        FROM product p
        JOIN category c ON p.categoryid = c.id
        WHERE p.isactive = 'Y'";

$stmt = $db->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Actieve producten</title>
</head>
<body>
<h1>Actieve producten</h1>
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Allergenen</th>
        <th>Categorie</th>
        <th>Prijs (€)</th>
        <th>Status</th>
    </tr>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['id']) ?></td>
            <td><?= htmlspecialchars($p['productname']) ?></td>
            <td><?= htmlspecialchars($p['allergens']) ?></td>
            <td><?= htmlspecialchars($p['categoryname']) ?></td>
            <td><?= htmlspecialchars(number_format($p['price'], 2, ',', '.')) ?></td>
            <td><?= htmlspecialchars($p['isactive']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>