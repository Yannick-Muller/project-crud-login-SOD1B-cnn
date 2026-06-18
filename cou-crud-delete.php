<?php
require_once "check-login.php";
require_once "database.php";

$ID = (int)($_POST["ID"] ?? 0);

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS aantal
     FROM product p
     JOIN supplier s ON p.supplierid = s.ID
     WHERE s.countryid = ?"
);
$stmt->bind_param("i", $ID);
$stmt->execute();
$check = $stmt->get_result()->fetch_assoc();

if ($check["aantal"] > 0) {
    $_SESSION["foutmelding"] = "Dit land kan niet verwijderd worden, want het wordt via een leverancier nog gebruikt door één of meer producten.";
    header("Location: cou-crud-get.php");
    exit;
}
$stmt->close();

$stmt = $conn->prepare("DELETE FROM country WHERE idcountry = ?");
$stmt->bind_param("i", $ID);
$stmt->execute();
$stmt->close();

$_SESSION["succesmelding"] = "Land is verwijderd.";
header("Location: cou-crud-get.php");
exit;
