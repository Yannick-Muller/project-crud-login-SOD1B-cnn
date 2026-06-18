<?php
require_once "check-login.php";
require_once "database.php";

$pageTitle = "Overzicht categorieën";
require "header.php";

$result = $conn->query("SELECT ID, name FROM category ORDER BY name");
?>

<h2>Overzicht categorieën</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Naam</th>
    </tr>
    <?php while ($rij = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($rij["ID"]); ?></td>
        <td><?php echo htmlspecialchars($rij["name"]); ?></td>
    </tr>
    <?php endwhile; ?>
</table>

