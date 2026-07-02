<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: beh-log.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Geen bestelregel geselecteerd.";
    exit;
}

$stmt = $db->prepare("DELETE FROM purchaseline WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: pro-ord-get.php");
exit;
