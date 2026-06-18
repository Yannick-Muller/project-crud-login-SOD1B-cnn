<?php
require_once "check-login.php";
require_once "database.php";


$name = trim($_POST["name"] ?? "");
$code = trim($_POST["code"] ?? "");

if (
    $name === "" || $code === "" ||
    !preg_match("/^[A-Za-z\s]+$/", $name) ||
    !preg_match("/^[A-Za-z\s]+$/", $code)
) {
    $_SESSION["foutmelding"] = "Naam en code zijn verplicht en mogen alleen letters en spaties bevatten.";
    $_SESSION["ingevoerdeNaam"] = $name;
    $_SESSION["ingevoerdeCode"] = $code;
    header("Location: cou-crud-add.php");
    exit;
}

// Controleer: komt name al voor in database?
$stmt = $conn->prepare("SELECT idcountry FROM country WHERE name = ?");
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION["foutmelding"] = "Deze landnaam bestaat al.";
    $_SESSION["ingevoerdeNaam"] = $name;
    $_SESSION["ingevoerdeCode"] = $code;
    header("Location: cou-crud-add.php");
    exit;
}
$stmt->close();

// Controleer: komt code al voor in database?
$stmt = $conn->prepare("SELECT idcountry FROM country WHERE code = ?");
$stmt->bind_param("s", $code);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION["foutmelding"] = "Deze landcode bestaat al.";
    $_SESSION["ingevoerdeNaam"] = $name;
    $_SESSION["ingevoerdeCode"] = $code;
    header("Location: cou-crud-add.php");
    exit;
}
$stmt->close();

// Opslaan
$stmt = $conn->prepare("INSERT INTO country (name, code) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $code);
$stmt->execute();
$stmt->close();

$_SESSION["succesmelding"] = "Land is toegevoegd.";
header("Location: cou-crud-get.php");
exit;
