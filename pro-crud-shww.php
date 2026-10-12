<?php
require 'dbconnect.php';

// Haal ALLE actieve producten op (J = actief in jouw database)
$sql = "SELECT 
            p.id,
            p.productname,
            p.price,
            p.isactive,
            c.name AS categoryname
        FROM product p
        LEFT JOIN category c ON p.categoryid = c.id
        WHERE p.isactive = 'J'
        ORDER BY p.id";

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
        <th>Categorie</th>
        <th>Prijs (€)</th>
        <th>Status</th>
    </tr>

    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['id']) ?></td>
            <td><?= htmlspecialchars($p['productname']) ?></td>
            <td><?= htmlspecialchars($p['categoryname']) ?></td>
            <td><?= htmlspecialchars(number_format($p['price'], 2, ',', '.')) ?></td>
            <td><?= htmlspecialchars($p['isactive']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>