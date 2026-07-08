<?php

session_start();

if (
    !isset($_SESSION["benJeErAl"]) ||
    $_SESSION["benJeErAl"] !== true ||
    !isset($_SESSION["SoortToegang"]) ||
    $_SESSION["SoortToegang"] !== "Beheer"
) {
    die("<p>Je hebt geen toegang tot deze pagina. Log eerst in als beheerder.</p>");
}
