<?php
session_start();
require 'dbconnect.php';

// Alleen ingelogde klanten
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'client') {
    header("Location: login.php");
    exit;
}

// Mag alleen bereikt worden na bevestiging op pur-crud-upd01.php
if (!isset($_SESSION['pendingupdate'])) {
    header("Location: pur-crud-upd.php");
    exit;
}

$sql = "UPDATE purchaseline SET quantity = :quantity WHERE id = :id";
$stmt = $db->prepare($sql);

foreach ($_SESSION['pendingupdate'] as $r) {
    $stmt->execute([
        ':quantity' => $r['newquantity'],
        ':id'       => $r['lineid']
    ]);
}

unset($_SESSION['pendingupdate']);
$_SESSION['ordermessage'] = "Wijziging is opgeslagen.";

header("Location: pur-crud-upd.php");
exit;
