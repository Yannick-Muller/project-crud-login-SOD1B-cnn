<?php
// menu.php - Rolgebaseerd menu (Bezoeker / Klant / Beheerder)
// LET OP: dit bestand verwacht dat session_start() AL is aangeroepen
// in het bestand dat menu.php include't.

$role = $_SESSION['userrole'] ?? null;
?>
<nav>
<ul>
    <!-- Statische pagina's + algemene overzichten: voor iedereen zichtbaar -->
    <li><a href="over-ons.php">Over ons</a></li>
    <li><a href="medewerkers.php">Medewerkers</a></li>
    <li><a href="pro-crud-get-public.php">Actieve producten</a></li>
    <li><a href="cat-crud-get-public.php">Categorieën</a></li>
    <li><a href="sup-crud-get-public.php">Leveranciers</a></li>
    <li><a href="lan-crud-get-public.php">Landen</a></li>

    <?php if ($role === null): ?>
        <!-- BEZOEKER: alleen zichtbaar als niemand is ingelogd -->
        <li><a href="register.php">Registreren</a></li>
        <li><a href="login-client.php">Inloggen als klant</a></li>
        <li><a href="login-admin.php">Inloggen als beheerder</a></li>

    <?php elseif ($role === 'client'): ?>
        <!-- KLANT: alleen zichtbaar als er een klant is ingelogd -->
        <li><a href="pur-crud-shw.php">Mijn bestellingen</a></li>
        <li><a href="pur-crud-add.php">Nieuwe bestelling</a></li>
        <li><a href="pur-crud-upd.php">Bestelling wijzigen</a></li>
        <li><a href="cli-crud-upd.php">Mijn gegevens wijzigen</a></li>
        <li><a href="cli-crud-pwd.php">Wachtwoord wijzigen</a></li>
        <li><a href="logout.php">Uitloggen</a></li>

    <?php elseif ($role === 'admin'): ?>
        <!-- BEHEERDER: alleen zichtbaar als er een beheerder is ingelogd -->
        <li><a href="pro-crud-get.php">Producten beheren</a></li>
        <li><a href="sup-crud-get.php">Leveranciers beheren</a></li>
        <li><a href="lan-crud-get.php">Landen beheren</a></li>
        <li><a href="cat-crud-get.php">Categorieën beheren</a></li>
        <li><a href="cli-crud-get.php">Klanten overzicht</a></li>
        <li><a href="adm-crud-get.php">Beheerders overzicht</a></li>
        <li><a href="pur-crud-del.php">Bestellingen beheren</a></li>
        <li><a href="logout.php">Uitloggen</a></li>
    <?php endif; ?>
</ul>
</nav>
