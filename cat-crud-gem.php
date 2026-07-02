<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde gebruikers (klant of beheerder)
if (!isset($_SESSION['userrole'])) {
    header("Location: index.php");
    exit;
}

$sql = "SELECT category.id AS categorie,
               category.name AS categorieoms,
               AVG(product.price) AS gem_prijs
        FROM category
        JOIN product ON product.categoryid = category.id
        GROUP BY category.id, category.name
        ORDER BY category.id";

$stmt = $db->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Overzicht categorieën met gemiddelde prijs</title>
    <style>
        table { border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 8px 16px; }
        th { background-color: #4a6fa5; color: white; text-align: left; }
        tr:nth-child(even) { background-color: #f0f0f0; }
        td:last-child, th:last-child { text-align: right; }
    </style>
</head>
<body>

<h1>Overzicht categorieën met gemiddelde prijs</h1>

<table>
    <tr>
        <th>Categorie</th>
        <th>CategorieOms</th>
        <th>gem_prijs</th>
    </tr>

    <?php foreach ($categories as $c): ?>
        <tr>
            <td><?= $c['categorie'] ?></td>
            <td><?= htmlspecialchars($c['categorieoms']) ?></td>
            <td>&euro; <?= number_format($c['gem_prijs'], 2, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
