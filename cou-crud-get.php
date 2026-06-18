<?php
require_once "check-login.php";
require_once "database.php";

$pageTitle = "Onderhoud countries";
include "nav.html";

$result = $conn->query("SELECT idcountry, name, code FROM country ORDER BY name");
?>

<h2>Onderhoud countries</h2>

<?php if (isset($_SESSION["succesmelding"])): ?>
    <p style="color:green;"><?php echo htmlspecialchars($_SESSION["succesmelding"]); unset($_SESSION["succesmelding"]); ?></p>
<?php endif; ?>
<?php if (isset($_SESSION["foutmelding"])): ?>
    <p style="color:red;"><?php echo htmlspecialchars($_SESSION["foutmelding"]); unset($_SESSION["foutmelding"]); ?></p>
<?php endif; ?>

<p><a href="cou-crud-add.php">Country toevoegen</a></p>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Code</th>
        <th>Acties</th>
    </tr>
    <?php while ($rij = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($rij["idcountry"]); ?></td>
        <td><?php echo htmlspecialchars($rij["name"]); ?></td>
        <td><?php echo htmlspecialchars($rij["code"]); ?></td>
        <td>
            <a href="cou-crud-upd.php?ID=<?php echo (int)$rij["idcountry"]; ?>">Wijzigen</a>
            |
            <a href="cou-crud-del.php?ID=<?php echo (int)$rij["idcountry"]; ?>">Verwijderen</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php require "footer.php"; ?>
