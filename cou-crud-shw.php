<?php
require_once "check-login.php";
require_once "database.php";

$pageTitle = "Overzicht landen";
require "header.php";

$result = $conn->query("SELECT idcountry, name, code FROM country ORDER BY name");
?>

<h2>Overzicht landen</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Code</th>
    </tr>
    <?php while ($rij = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($rij["idcountry"]); ?></td>
        <td><?php echo htmlspecialchars($rij["name"]); ?></td>
        <td><?php echo htmlspecialchars($rij["code"]); ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<?php require "footer.php"; ?>
