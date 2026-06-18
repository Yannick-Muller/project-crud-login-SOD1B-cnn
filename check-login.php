<?php
// check-login.php
// Controleert of er een ingelogde BEHEERDER is, gebaseerd op de sessievariabelen
// die het (dummy) inlogsysteem zet: benJeErAl en SoortToegang.
//
// AANNAME: SoortToegang == "Beheer" betekent dat de ingelogde gebruiker beheerder is
// (zie dummy_admin_login.php). Pas dit aan als jouw echte inlogsysteem andere
// sessievariabelen gebruikt.


session_start();

if (
    !isset($_SESSION["benJeErAl"]) ||
    $_SESSION["benJeErAl"] !== true ||
    !isset($_SESSION["SoortToegang"]) ||
    $_SESSION["SoortToegang"] !== "Beheer"
) {
    die("<p>Je hebt geen toegang tot deze pagina. Log eerst in als beheerder.</p>");
}
