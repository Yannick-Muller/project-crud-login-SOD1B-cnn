<?php
session_start();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>

<h1>Welkom</h1>

<h2>Menu</h2>

<?php if (isset($_SESSION['userrole']) && $_SESSION['userrole'] == 'admin'): ?>

    <p>U bent ingelogd als beheerder<?= isset($_SESSION['username']) ? ' (' . htmlspecialchars($_SESSION['username']) . ')' : '' ?>.</p>
    <ul>
        <li><a href="pro-crud-get.php">Producten beheren</a></li>
        <li><a href="pur-crud-del.php">Bestellingen beheren</a></li>
        <li><a href="beh-uit.php">Uitloggen</a></li>
    </ul>

<?php elseif (isset($_SESSION['userrole']) && $_SESSION['userrole'] == 'klant'): ?>

    <p>U bent ingelogd als klant.</p>
    <ul>
        <li><a href="pur-crud-add.php">Product bestellen</a></li>
        <li><a href="pur-crud-shw.php">Mijn bestellingen</a></li>
        <li><a href="pur-crud-upd.php">Bestelling wijzigen</a></li>
    </ul>

<?php else: ?>

    <p>U bent niet ingelogd.</p>
    <ul>
        <li><a href="beh-log.php">Inloggen als beheerder</a></li>
    </ul>

<?php endif; ?>

</body>
</html>
