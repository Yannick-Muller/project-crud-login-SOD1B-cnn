<?php
require 'dbconnect.php';

// Haal alle producten op met categorie en leverancier
$sql = "SELECT 
            p.id,
            p.productname,
            p.price,
            p.isactive,
            c.name AS categoryname,
            s.suppliername AS suppliername
        FROM product p
        LEFT JOIN category c ON p.categoryid = c.id
        LEFT JOIN supplier s ON p.supplierid = s.id
        ORDER BY p.id";

$stmt = $db->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Producten overzicht</title>
</head>
<body>
<h1>Alle producten</h1>

<a href="pro-crud-add.php">Nieuw product toevoegen</a>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Categorie</th>
        <th>Leverancier</th>
        <th>Prijs (€)</th>
        <th>Status</th>
        <th>Acties</th>
    </tr>

    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['id']) ?></td>
            <td><?= htmlspecialchars($p['productname']) ?></td>
            <td><?= htmlspecialchars($p['categoryname']) ?></td>
            <td><?= htmlspecialchars($p['suppliername']) ?></td>
            <td><?= htmlspecialchars(number_format($p['price'], 2, ',', '.')) ?></td>
            <td><?= htmlspecialchars($p['isactive']) ?></td>
            <td>
                <a href="pro-crud-upd.php?id=<?= $p['id'] ?>">Wijzigen</a> |
                <a href="pro-crud-del.php?id=<?= $p['id'] ?>">Verwijderen</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>