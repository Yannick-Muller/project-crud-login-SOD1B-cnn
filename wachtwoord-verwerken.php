<?php
session_start();
require 'config.php'; // jouw database connectie


if (!isset($_SESSION['klantid'])) {
    header("Location: login.php");
    exit;
}

$klantid = $_SESSION['klantid'];


$huidig = $_POST['huidig'];
$nieuw1 = $_POST['nieuw1'];
$nieuw2 = $_POST['nieuw2'];


$sql = "SELECT wachtwoord FROM klanten WHERE klantid = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $klantid]);
$klant = $stmt->fetch();

if (!$klant) {
    echo "Fout: klant niet gevonden.";
    exit;
}

$hash_oud = $klant['wachtwoord'];


if (!password_verify($huidig, $hash_oud)) {
    echo "Huidig wachtwoord is onjuist.";
    exit;
}


if ($nieuw1 !== $nieuw2) {
    echo "De nieuwe wachtwoorden komen niet overeen.";
    exit;
}


$nieuw_hash = password_hash($nieuw1, PASSWORD_DEFAULT);

// 7. Update database
$sql = "UPDATE klanten SET wachtwoord = :nieuw WHERE klantid = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'nieuw' => $nieuw_hash,
    'id' => $klantid
]);


header("Location: index.php?msg=wachtwoord_gewijzigd");
exit;

?>