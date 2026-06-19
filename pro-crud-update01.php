<?php
session_start();
require 'config.php';


if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header("Location: login.php");
    exit;
}


if (!isset($_POST['id'])) {
    header("Location: pro-crud-upd01.php");
    exit;
}

$id = $_POST['id'];


$sql = "SELECT isactive FROM product WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: pro-crud-upd01.php");
    exit;
}


$nieuw = ($product['isactive'] == "J") ? "N" : "J";


$sql = "UPDATE product SET isactive = :nieuw WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'nieuw' => $nieuw,
    'id' => $id
]);


$melding = ($nieuw == "J") 
    ? "Het product is actief gemaakt" 
    : "Het product is inactief gemaakt";

echo "<h2>$melding</h2>";
echo "<a href='pro-crud-upd01.php'>Terug naar overzicht</a>"