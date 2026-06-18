<?php
session_start();
require 'config.php';


if (!isset($_SESSION['klantid'])) {
    header("Location: login.php");
    exit;
}

$klantid = $_SESSION['klantid'];


$huidig = $_POST['huidig'];
$nieuw1 = $_POST['nieuw1'];
$nieuw2 = $_POST['nieuw2'];


if (empty($huidig)) {
    echo "Email en wachtwoord stemmen niet overeen";
    exit;
}


$sql = "SELECT wachtwoord FROM klanten WHERE klantid = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $klantid]);
$klant = $stmt->fetch();

if (!$klant) {
    echo "Email en wachtwoord stemmen niet overeen";
    exit;
}

$hash_oud = $klant['wachtwoord'];


if (!password_verify($huidig, $hash_oud)) {
    echo "Email en wachtwoord stemmen niet overeen";
    exit;
}


if ($nieuw1 !== $nieuw2) {
    echo "Email en wachtwoord stemmen niet overeen";
    exit;
}


$nieuw_hash = password_hash($nieuw1, PASSWORD_DEFAULT);


$sql = "UPDATE klanten SET wachtwoord = :nieuw WHERE klantid = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'nieuw' => $nieuw_hash,
    'id' => $klantid
]);


header("Location: index.php?msg=wachtwoord_gewijzigd");
exit;

?>