<?php
require 'dbconnect.php';

$id = $_GET['id'];

// Check of het product bestaat
$sql = "SELECT * FROM product WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product met ID $id bestaat niet.");
}

// Verwijder product
$sql = "DELETE FROM product WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $id]);

// Terug naar overzicht
header("Location: pro-crud-get.php");
exit;