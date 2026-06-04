<?php
require 'dbconnect.php';
session_start();


if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: login.php");
    exit;
}


$sql = "SELECT product.id,
               product.productname,
               product.price,
               product.isactive,
               category.name AS categoryname,
               supplier.company AS suppliername
        FROM product
        JOIN category ON product.categoryid = category.id
        JOIN supplier ON product.supplierid = supplier.id";

$stmt = $db->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Onderhoud producten</title>
</head>
<body>

<h1>Onderhoud producten</h1>


<a href="pro-crud-add.php">
    <button>Product toevoegen</button>
</a>

<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Prijs</th>
        <th>Categorie</th>
        <th>Leverancier</th>
        <th>Actief</th>
        <th>Acties</th>
    </tr>

    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['productname'] ?></td>
            <td><?= $p['price'] ?></td>
            <td><?= $p['categoryname'] ?></td>
            <td><?= $p['suppliername'] ?></td>
            <td><?= $p['isactive'] ?></td>
            <td>
                <a href="pro-crud-upd.php?id=<?= $p['id'] ?>">
                    <button>Wijzigen</button>
                </a>
                <a href="pro-crud-del.php?id=<?= $p['id'] ?>">
                    <button>Verwijderen</button>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>