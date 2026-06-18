<?php
require_once "check-login.php";
require_once "database.php";

$name = trim($_POST["name"] ?? "");

// Controleer: is naam ingevuld en bestaat hij alleen uit letters en spaties?
if ($name === "" || !preg_match("/^[A-Za-z\s]+$/", $name)) {
    $_SESSION["foutmelding"] = "Naam is verplicht en mag alleen letters en spaties bevatten.";
    $_SESSION["ingevoerdeNaam"] = $name;
    header("Location: cat-crud-add.php");
    exit;
}

// Controleer: komt naam al voor in de database?
$stmt = $conn->prepare("SELECT ID FROM category WHERE name = ?");
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION["foutmelding"] = "Deze categorienaam bestaat al.";
    $_SESSION["ingevoerdeNaam"] = $name;
    header("Location: cat-crud-add.php");
    exit;
}
$stmt->close();

// Opslaan
$stmt = $conn->prepare("INSERT INTO category (name) VALUES (?)");
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->close();

$_SESSION["succesmelding"] = "Categorie is toegevoegd.";
header("Location: cat-crud-get.php");
exit;
