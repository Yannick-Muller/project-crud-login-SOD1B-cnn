<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: login.php");
    exit;
}

function deletePurchase($db, $purchaseid) {
    // LET OP: eerst de regels verwijderen, daarna de aankoop zelf
    $stmt = $db->prepare("DELETE FROM purchaseline WHERE purchaseid = :purchaseid");
    $stmt->execute([':purchaseid' => $purchaseid]);

    $stmt = $db->prepare("DELETE FROM purchase WHERE id = :purchaseid");
    $stmt->execute([':purchaseid' => $purchaseid]);
}

// Bevestigde verwijdering van een hele aankoop (vanuit de waarschuwing hieronder)
if (isset($_GET['confirmpurchase'])) {
    deletePurchase($db, $_GET['confirmpurchase']);

    header("Location: pur-crud-del.php");
    exit;
}

$type = $_GET['type'] ?? null;
$id   = $_GET['id'] ?? null;

if (!$type || !$id) {
    echo "Geen actie geselecteerd.";
    exit;
}

if ($type == 'purchase') {
    // Knop "Aankoop": hele aankoop verwijderen
    deletePurchase($db, $id);

    header("Location: pur-crud-del.php");
    exit;
}

if ($type == 'line') {
    // Knop "Regel": eerst checken of dit de laatste regel van de aankoop is
    $stmt = $db->prepare("SELECT purchaseid FROM purchaseline WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $line = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$line) {
        echo "Regel niet gevonden.";
        exit;
    }

    $purchaseid = $line['purchaseid'];

    $stmt = $db->prepare("SELECT COUNT(*) FROM purchaseline WHERE purchaseid = :purchaseid");
    $stmt->execute([':purchaseid' => $purchaseid]);
    $count = $stmt->fetchColumn();

    if ($count <= 1) {
        // Laatste regel: waarschuwing tonen, nog niets verwijderen
        ?>
        <!DOCTYPE html>
        <html lang="nl">
        <head>
            <meta charset="UTF-8">
            <title>Waarschuwing</title>
        </head>
        <body>

        <h1>Laatste product bij deze aankoop</h1>
        <p>Wilt u het verwijderen afbreken of wilt u de hele aankoop verwijderen?</p>

        <a href="pur-crud-del.php">
            <button>Afbreken</button>
        </a>
        <a href="pur-crud-delete.php?confirmpurchase=<?= $purchaseid ?>">
            <button>Verwijder aankoop</button>
        </a>

        </body>
        </html>
        <?php
        exit;
    }

    // Niet de laatste regel: gewoon deze regel verwijderen
    $stmt = $db->prepare("DELETE FROM purchaseline WHERE id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: pur-crud-del.php");
    exit;
}

echo "Onbekende actie.";
