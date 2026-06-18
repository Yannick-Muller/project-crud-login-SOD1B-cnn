<?php
require_once "check-login.php";
require_once "database.php";

$ID = (int)($_POST["ID"] ?? 0);


$stmt = $conn->prepare("SELECT COUNT(*) AS aantal FROM product WHERE categoryid = ?");
$stmt->bind_param("i", $ID);
$stmt->execute();
$check = $stmt->get_result()->fetch_assoc();

if ($check["aantal"] > 0) {
    $_SESSION["foutmelding"] = "Deze categorie kan niet verwijderd worden, want hij wordt nog gebruikt door één of meer producten.";
    header("Location: cat-crud-get.php");
    exit;
}
$stmt->close();

$stmt = $conn->prepare("DELETE FROM category WHERE ID = ?");
$stmt->bind_param("i", $ID);
$stmt->execute();
$stmt->close();

$_SESSION["succesmelding"] = "Categorie is verwijderd.";
header("Location: cat-crud-get.php");
exit;
