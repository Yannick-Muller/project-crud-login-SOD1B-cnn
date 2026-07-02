<?php
session_start();

// Naam en autorisatie: alleen voor ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: beh-log.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Uitloggen</title>
</head>
<body>

<h1>Uitloggen</h1>

<p>Weet u zeker dat u wilt uitloggen?</p>

<a href="beh-uit-verwerk.php">
    <button>Ja, uitloggen</button>
</a>
<a href="beh-menu.php">
    <button>Breek af</button>
</a>

</body>
</html>
