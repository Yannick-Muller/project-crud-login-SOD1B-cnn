<?php
session_start();
require 'dbconnect.php';

// Naam en autorisatie: is er al een beheerder ingelogd? Dan direct door naar het menu
if (isset($_SESSION['userrole']) && $_SESSION['userrole'] == 'admin') {
    header("Location: beh-menu.php");
    exit;
}

$emailerror = '';
$passworderror = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Controle email-adres: eerst het formaat, dan of het bestaat
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailerror = "Vul een geldig e-mailadres in.";
    } else {
        $stmt = $db->prepare("SELECT id, name, password FROM beheerder WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $beheerder = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$beheerder) {
            $emailerror = "Dit e-mailadres is niet bekend.";
        } else {
            // Controle wachtwoord
            if ($password === '' || !password_verify($password, $beheerder['password'])) {
                $passworderror = "Het ingevoerde wachtwoord is onjuist.";
            } else {
                // Opslaan in SESSION
                $_SESSION['userid']   = $beheerder['id'];
                $_SESSION['username'] = $beheerder['name'];
                $_SESSION['userrole'] = 'admin';

                // Vervolgscherm: door naar het beheerdersmenu
                header("Location: beh-menu.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen beheerder</title>
</head>
<body>

<h1>Inloggen beheerder</h1>

<form method="post">
    <label>E-mailadres:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
    <?php if ($emailerror): ?>
        <br><span style="color:red;"><?= $emailerror ?></span>
    <?php endif; ?>
    <br><br>

    <label>Wachtwoord:</label><br>
    <input type="password" name="password" required>
    <?php if ($passworderror): ?>
        <br><span style="color:red;"><?= $passworderror ?></span>
    <?php endif; ?>
    <br><br>

    <button type="submit">Inloggen</button>
    <a href="index.php"><button type="button">Terug</button></a>
</form>

</body>
</html>
