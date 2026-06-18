<?php
session_start();
require 'config.php'; // database connectie

// 1. Check of klant is ingelogd
if (!isset($_SESSION['klantid'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wachtwoord wijzigen</title>
</head>
<body>

<h2>Wachtwoord wijzigen</h2>

<form action="wachtwoord-verwerken.php" method="post">

    <label>Huidig wachtwoord:</label><br>
    <input type="password" name="huidig" required><br><br>

    <label>Nieuw wachtwoord:</label><br>
    <input type="password" name="nieuw1" required><br><br>

    <label>Herhaal nieuw wachtwoord:</label><br>
    <input type="password" name="nieuw2" required><br><br>

    <button type="submit">Wachtwoord wijzigen</button>

</form>

</body>
</html>