<?php
session_start();
require 'dbconnect.php';

// Alleen ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    header("Location: pur-crud-del.php");
    exit;
}

$action     = $_POST['action'];
$purchaseid = (int) $_POST['purchaseid'];

if ($action === 'regel') {
    $lineid = (int) $_POST['lineid'];

    // Tellen hoeveel regels deze purchase nog heeft
    $sql = "SELECT COUNT(*) FROM purchaseline WHERE purchaseid = :purchaseid";
    $stmt = $db->prepare($sql);
    $stmt->execute([':purchaseid' => $purchaseid]);
    $aantalregels = $stmt->fetchColumn();

    if ($aantalregels <= 1) {
        // Laatste regel: eerst waarschuwen, nog niets verwijderen
        ?>
        <!DOCTYPE html>
        <html lang="nl">
        <head><meta charset="UTF-8"><title>Waarschuwing</title></head>
        <body>

        <?php require 'menu.php'; ?>

        <h1>Waarschuwing</h1>
        <p>Laatste product bij deze aankoop. Wilt u het verwijderen afbreken of wilt u de hele aankoop verwijderen?</p>

        <a href="pur-crud-del.php"><button type="button">Afbreken</button></a>

        <form method="post" action="pur-crud-delete.php" style="display:inline">
            <input type="hidden" name="purchaseid" value="<?= htmlspecialchars($purchaseid) ?>">
            <button type="submit" name="action" value="aankoop">Verwijder aankoop</button>
        </form>

        </body>
        </html>
        <?php
        exit;
    }

    // Niet de laatste regel: gewoon deze regel verwijderen
    $sql = "DELETE FROM purchaseline WHERE id = :lineid AND purchaseid = :purchaseid";
    $stmt = $db->prepare($sql);
    $stmt->execute([':lineid' => $lineid, ':purchaseid' => $purchaseid]);

    $_SESSION['delmessage'] = "Bestelregel is verwijderd.";
    header("Location: pur-crud-del.php");
    exit;
}

if ($action === 'aankoop') {
    // LET OP: eerst purchaselines verwijderen, dan pas het purchase record (foreign key volgorde)
    $sql = "DELETE FROM purchaseline WHERE purchaseid = :purchaseid";
    $stmt = $db->prepare($sql);
    $stmt->execute([':purchaseid' => $purchaseid]);

    $sql = "DELETE FROM purchase WHERE id = :purchaseid";
    $stmt = $db->prepare($sql);
    $stmt->execute([':purchaseid' => $purchaseid]);

    $_SESSION['delmessage'] = "Aankoop is volledig verwijderd.";
    header("Location: pur-crud-del.php");
    exit;
}

header("Location: pur-crud-del.php");
exit;
