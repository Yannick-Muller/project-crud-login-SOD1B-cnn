<?php
require_once "check-login.php";
require_once "database.php";

$ID = (int)($_POST["ID"] ?? 0);
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
    header("Location: cou-crud-upd.php?ID=" . $ID);
    exit;
}

$stmt = $conn->prepare("SELECT idcountry FROM country WHERE name = ? AND idcountry <> ?");
$stmt->bind_param("si", $name, $ID);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION["foutmelding"] = "Deze landnaam bestaat al.";
    $_SESSION["ingevoerdeNaam"] = $name;
    $_SESSION["ingevoerdeCode"] = $code;
    header("Location: cou-crud-upd.php?ID=" . $ID);
    exit;
}
$stmt->close();

$stmt = $conn->prepare("SELECT idcountry FROM country WHERE code = ? AND idcountry <> ?");
$stmt->bind_param("si", $code, $ID);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION["foutmelding"] = "Deze landcode bestaat al.";
    $_SESSION["ingevoerdeNaam"] = $name;
    $_SESSION["ingevoerdeCode"] = $code;
    header("Location: cou-crud-upd.php?ID=" . $ID);
    exit;
}
$stmt->close();

$stmt = $conn->prepare("UPDATE country SET name = ?, code = ? WHERE idcountry = ?");
$stmt->bind_param("ssi", $name, $code, $ID);
$stmt->execute();
$stmt->close();

$_SESSION["succesmelding"] = "Land is gewijzigd.";
header("Location: cou-crud-get.php");
exit;
