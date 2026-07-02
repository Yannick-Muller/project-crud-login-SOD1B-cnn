<?php
session_start();

// Naam en autorisatie: alleen relevant voor ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: beh-log.php");
    exit;
}

// SESSION variabelen individueel verwijderen
unset($_SESSION['userid']);
unset($_SESSION['username']);
unset($_SESSION['userrole']);

// De hele sessie opruimen
session_destroy();

// Terug naar de homepage
header("Location: index.php");
exit;
