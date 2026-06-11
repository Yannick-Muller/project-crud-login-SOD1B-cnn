<?php
require 'dbconnect.php';
session_start();


if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: login.php");
    exit;
}


$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Geen product geselecteerd.";
    exit;
}


$sql = "SELECT * FROM product WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product niet gevonden.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productname = $_POST['productname'];
    $price = $_POST['price'];
    $categoryid = $_POST['categoryid'];
    $supplierid = $_POST['supplierid'];
    $isactive = $_POST['isactive'];

    $update = "UPDATE product 
               SET productname = :productname,
                   price = :price,
                   categoryid = :categoryid,
                   supplierid = :supplierid,
                   isactive = :isactive
               WHERE id = :id";
    $stmt = $db->prepare($update);
    $stmt->execute([
        ':productname' => $productname,
        ':price' => $price,
        ':categoryid' => $categoryid,
        ':supplierid' => $supplierid,
        ':isactive' => $isactive,
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
    <label>Naam:</label><br>
    <input type="text" name="productname" value="<?= $product['productname'] ?>" required><br><br>

    <label>Prijs:</label><br>
    <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required><br><br>

    <label>Categorie ID:</label><br>
    <input type="number" name="categoryid" value="<?= $product['categoryid'] ?>" required><br><br>

    <label>Leverancier ID:</label><br>
    <input type="number" name="supplierid" value="<?= $product['supplierid'] ?>" required><br><br>

    <label>Actief (J/N):</label><br>
    <input type="text" name="isactive" maxlength="1" value="<?= $product['isactive'] ?>" required><br><br>

    <button type="submit">Opslaan wijziging</button>
</form>

</body>
</html>