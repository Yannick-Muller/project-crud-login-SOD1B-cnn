<?php
require 'dbconnect.php';
session_start();


if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productname = $_POST['productname'];
    $price = $_POST['price'];
    $categoryid = $_POST['categoryid'];
    $supplierid = $_POST['supplierid'];
    $isactive = $_POST['isactive'];

    $sql = "INSERT INTO product (productname, price, categoryid, supplierid, isactive)
            VALUES (:productname, :price, :categoryid, :supplierid, :isactive)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':productname' => $productname,
        ':price' => $price,
        ':categoryid' => $categoryid,
        ':supplierid' => $supplierid,
        ':isactive' => $isactive
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

<h1>Product toevoegen</h1>

<form method="post">
    <label>Naam:</label><br>
    <input type="text" name="productname" required><br><br>

    <label>Prijs:</label><br>
    <input type="number" step="0.01" name="price" required><br><br>

    <label>Categorie ID:</label><br>
    <input type="number" name="categoryid" required><br><br>

    <label>Leverancier ID:</label><br>
    <input type="number" name="supplierid" required><br><br>

    <label>Actief (J/N):</label><br>
    <input type="text" name="isactive" maxlength="1" required><br><br>

    <button type="submit">Opslaan</button>
</form>

</body>
</html>