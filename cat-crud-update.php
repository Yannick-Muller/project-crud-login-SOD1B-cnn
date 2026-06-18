<?php
require_once "check-login.php";
require_once "database.php";


$ID = (int)($_POST["ID"] ?? 0);
$name = trim($_POST["name"] ?? "");

if ($name === "" || !preg_match("/^[A-Za-z\s]+$/", $name)) {
    $_SESSION["foutmelding"] = "Naam is verplicht en mag alleen letters en spaties bevatten.";
    $_SESSION["ingevoerdeNaam"] = $name;
    header("Location: cat-crud-upd.php?ID=" . $ID);
    exit;
}

$stmt = $conn->prepare("SELECT ID FROM category WHERE name = ? AND ID <> ?");
$stmt->bind_param("si", $name, $ID);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION["foutmelding"] = "Deze categorienaam bestaat al.";
    $_SESSION["ingevoerdeNaam"] = $name;
    header("Location: cat-crud-upd.php?ID=" . $ID);
    exit;
}
$stmt->close();

$stmt = $conn->prepare("UPDATE category SET name = ? WHERE ID = ?");
$stmt->bind_param("si", $name, $ID);
$stmt->execute();
$stmt->close();

$_SESSION["succesmelding"] = "Categorie is gewijzigd.";
header("Location: cat-crud-get.php");
exit;
