<?php include "underconstruct.php"; ?>

<?php
require 'dbconnect.php'; // database verbinding

// Alleen voor ingelogde beheerder
session_start();
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    echo "<h2>Geen toegang – alleen voor beheerders</h2>";
    exit;
}

try {
    $sql = "SELECT product.id,
                   product.productname,
                   category.name AS categoryname,
                   supplier.company AS suppliername,
                   product.price
            FROM product
            JOIN category ON product.categoryid = category.id
            JOIN supplier ON product.supplierid = supplier.id
            WHERE product.isactive = 'N'";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Database fout: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inactieve producten</title>
</head>
<body>

<h1>Inactieve producten (beheerder)</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Productnaam</th>
        <th>Categorie</th>
        <th>Leverancier</th>
        <th>Prijs</th>
    </tr>

    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['id']) ?></td>
            <td><?= htmlspecialchars($p['productname']) ?></td>
            <td><?= htmlspecialchars($p['categoryname']) ?></td>
            <td><?= htmlspecialchars($p['suppliername']) ?></td>
            <td><?= htmlspecialchars($p['price']) ?></td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>