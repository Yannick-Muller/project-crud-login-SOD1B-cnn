<?php
session_start();

// Alleen voor ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: beh-log.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Beheerdersmenu</title>
</head>
<body>

<h1>Welkom, <?= htmlspecialchars($_SESSION['username']) ?></h1>

<h2>Menu</h2>
<ul>
    <li><a href="pro-crud-get.php">Producten beheren</a></li>
    <li><a href="pur-crud-del.php">Bestellingen beheren</a></li>
</ul>

<a href="beh-uit.php"><button>Uitloggen</button></a>

</body>
</html>
