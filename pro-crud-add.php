<?php
require 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['productname'];
    $price = $_POST['price'];
    $category = $_POST['categoryid'];
    $supplier = $_POST['supplierid'];
    $active = $_POST['isactive'];

    $sql = "INSERT INTO product (productname, price, categoryid, supplierid, isactive)
            VALUES (:name, :price, :category, :supplier, :active)";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':price' => $price,
        ':category' => $category,
        ':supplier' => $supplier,
        ':active' => $active
    ]);

    header("Location: pro-crud-get.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Product toevoegen</title>
</head>
<body>
<h1>Nieuw product toevoegen</h1>

<form method="post">
    Naam: <input type="text" name="productname" required><br><br>
    Prijs: <input type="number" step="0.01" name="price" required><br><br>
    Categorie ID: <input type="number" name="categoryid" required><br><br>
    Leverancier ID: <input type="number" name="supplierid" required><br><br>
    Actief (J/N): <input type="text" name="isactive" required><br><br>

    <button type="submit">Opslaan</button>
</form>

</body>
</html>