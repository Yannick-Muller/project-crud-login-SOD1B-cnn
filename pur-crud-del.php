<?php
require 'dbconnect.php';
session_start();

// Alleen admin mag verwijderen
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: login.php");
    exit;
}

// ID ophalen uit URL
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Geen product geselecteerd.";
    exit;
}


$sql = "DELETE FROM product WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $id]);

header("Location: pro-crud-get.php");
exit;