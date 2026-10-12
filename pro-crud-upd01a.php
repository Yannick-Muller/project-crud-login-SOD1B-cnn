<?php
session_start();
require 'dbconnect.php';


if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['id'])) {
    header("Location: pro-crud-upd01.php");
    exit;
}

$id = $_POST['id'];


$sql = "SELECT p.*, 
               c.name AS categoryname, 
               s.company 
        FROM product p
        JOIN category c ON p.categoryid = c.id
        JOIN supplier s ON p.supplierid = s.id
        WHERE p.id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: pro-crud-upd01.php");
    exit;
}

$isActive = ($product['isactive'] == "J");

$titel = $isActive ? "De-activeren van product" : "(opnieuw) activeren van product";
$vraag = $isActive ? "Wilt u bovenstaand product de-activeren" : "Wilt u bovenstaand product opnieuw activeren";
$knop = $isActive ? "De-activeren" : "Activeer";
?>

<h2><?= $titel ?></h2>

<p>ID: <?= $product['id'] ?></p>
<p>Naam: <?= $product['productname'] ?></p>
<p>Ingrediënten: <?= $product['ingredients'] ?></p>
<p>Allergenen: <?= $product['allergens'] ?></p>
<p>Categorie: <?= $product['categoryname'] ?></p>
<p>Leverancier: <?= $product['company'] ?></p>
<p>Prijs: <?= $product['price'] ?></p>

<p><strong><?= $vraag ?></strong></p>

<form action="pro-crud-update01.php" method="post">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">
    <button type="submit"><?= $knop ?></button>
</form>

<br>

<form action="pro-crud-upd01.php">
    <button type="submit">Breek af</button>
</form>