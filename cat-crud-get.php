<?php
require_once "check-login.php";
require_once "database.php";

$pageTitle = "Onderhoud categorieën";
require "header.php";

$result = $conn->query("SELECT ID, name FROM category ORDER BY name");
?>

<h2>Onderhoud categorieën</h2>

<?php if (isset($_SESSION["succesmelding"])): ?>
    <p style="color:green;"><?php echo htmlspecialchars($_SESSION["succesmelding"]); unset($_SESSION["succesmelding"]); ?></p>
<?php endif; ?>
<?php if (isset($_SESSION["foutmelding"])): ?>
    <p style="color:red;"><?php echo htmlspecialchars($_SESSION["foutmelding"]); unset($_SESSION["foutmelding"]); ?></p>
<?php endif; ?>

<p><a href="cat-crud-add.php">Category toevoegen</a></p>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Acties</th>
    </tr>
    <?php while ($rij = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($rij["ID"]); ?></td>
        <td><?php echo htmlspecialchars($rij["name"]); ?></td>
        <td>
            <a href="cat-crud-upd.php?ID=<?php echo (int)$rij["ID"]; ?>">Wijzigen</a>
            |
            <a href="cat-crud-del.php?ID=<?php echo (int)$rij["ID"]; ?>">Verwijderen</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php require "footer.php"; ?>
