<?php
require 'dbconnect.php';

$id = $_GET['id'];

$sql = "SELECT * FROM product WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['productname'];
    $price = $_POST['price'];
    $category = $_POST['categoryid'];
    $supplier = $_POST['supplierid'];
    $active = $_POST['isactive'];

    $sql = "UPDATE product 
            SET productname = :name,
                price = :price,
                categoryid = :category,
                supplierid = :supplier,
                isactive = :active
            WHERE id = :id";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':price' => $price,
        ':category' => $category,
        ':supplier' => $supplier,
        ':active' => $active,
        ':id' => $id
    ]);

    header("Location: pro-crud-get.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Product wijzigen</title>
</head>
<body>
<h1>Product wijzigen</h1>

<form method="post">
    Naam: <input type="text" name="productname" value="<?= $product['productname'] ?>"><br><br>
    Prijs: <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>"><br><br>
    Categorie ID: <input type="number" name="categoryid" value="<?= $product['categoryid'] ?>"><br><br>
    Leverancier ID: <input type="number" name="supplierid" value="<?= $product['supplierid'] ?>"><br><br>
    Actief (J/N): <input type="text" name="isactive" value="<?= $product['isactive'] ?>"><br><br>

    <button type="submit">Opslaan</button>
</form>

</body>
</html>