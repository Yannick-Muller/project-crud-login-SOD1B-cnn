<?php
session_start();
require 'dbconnect.php';

// Alleen ingelogde klanten mogen bestellen
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'client') {
    header("Location: login.php");
    exit;
}

// Alleen actieve producten tonen
$sql = "SELECT product.id,
               product.productname,
               product.price,
               category.name AS categoryname
        FROM product
        JOIN category ON product.categoryid = category.id
        WHERE product.isactive = 'J'
        ORDER BY product.productname";
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

<?php require 'menu.php'; ?>

<h1>Product bestellen</h1>
<h2>LET OP: je kan maar één product tegelijk bestellen</h2>

<?php if (isset($_SESSION['ordermessage'])): ?>
    <p><strong><?= htmlspecialchars($_SESSION['ordermessage']) ?></strong></p>
    <?php unset($_SESSION['ordermessage']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['orderror'])): ?>
    <p style="color:red;"><strong><?= htmlspecialchars($_SESSION['orderror']) ?></strong></p>
    <?php unset($_SESSION['orderror']); ?>
<?php endif; ?>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Categorie</th>
        <th>Prijs (€)</th>
        <th>Aantal</th>
        <th>Actie</th>
    </tr>
    <?php foreach ($products as $p): ?>
        <tr>
            <form method="post" action="pur-crud-adding.php">
                <td><?= htmlspecialchars($p['id']) ?></td>
                <td><?= htmlspecialchars($p['productname']) ?></td>
                <td><?= htmlspecialchars($p['categoryname']) ?></td>
                <td><?= htmlspecialchars(number_format((float) $p['price'], 2, ',', '.')) ?></td>
                <td>
                    <!-- Prijs wordt NIET vanuit dit formulier vertrouwd: pur-crud-adding.php
                         haalt de actuele prijs zelf op uit de database. -->
                    <input type="hidden" name="productid" value="<?= (int) $p['id'] ?>">
                    <input type="number" name="quantity" min="1" value="1" required>
                </td>
                <td><button type="submit">Bestellen</button></td>
            </form>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
