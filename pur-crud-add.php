<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde klanten
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'klant') {
    header("Location: login.php");
    exit;
}

$sql = "SELECT product.id,
               product.productname,
               product.price,
               category.name AS categoryname
        FROM product
        JOIN category ON product.categoryid = category.id
        WHERE product.isactive = 'J'";

$stmt = $db->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Product bestellen</title>
</head>
<body>

<h1>Product bestellen</h1>

<h2>LET OP: je kan maar één product tegelijk bestellen</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Categorie</th>
        <th>Prijs</th>
        <th>Aantal</th>
        <th>Actie</th>
    </tr>

    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['productname'] ?></td>
            <td><?= $p['categoryname'] ?></td>
            <td><?= $p['price'] ?></td>
            <td>
                <form method="post" action="pur-crud-adding.php">
                    <input type="hidden" name="productid" value="<?= $p['id'] ?>">
                    <input type="hidden" name="price" value="<?= $p['price'] ?>">
                    <input type="number" name="quantity" min="1" value="1" required>
            </td>
            <td>
                    <button type="submit">Bestellen</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
